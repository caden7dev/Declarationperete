<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Page de liste des notifications du citoyen
     */
    public function index()
    {
        $user = Auth::user();

        $notifications = Notification::where('user_id', $user->id)
            ->where('type', '!=', 'agent_message')
            ->notExpired()                         // ← exclut les expirées
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // ─── FIX : même filtre que getUnreadCount ───────────────────
        $unreadCount = Notification::where('user_id', $user->id)
            ->where('type', '!=', 'agent_message')
            ->notExpired()                         // ← AJOUTÉ : cohérence avec le badge
            ->unread()
            ->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Marquer une notification comme lue
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->findOrFail($id);

        $notification->markAsRead();

        return back()->with('success', 'Notification marquée comme lue');
    }

    /**
     * Marquer TOUTES les notifications comme lues
     * ─── FIX : répond en JSON si appel AJAX, sinon redirect ─────────
     */
    public function markAllAsRead(Request $request)
    {
        Notification::where('user_id', Auth::id())
            ->where('type', '!=', 'agent_message')
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        // Si la requête vient du JS (fetch depuis le dashboard/badge)
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Toutes les notifications ont été marquées comme lues',
            ]);
        }

        // Sinon redirection classique (bouton dans la page notifications)
        return back()->with('success', 'Toutes les notifications ont été marquées comme lues');
    }

    /**
     * Compteur pour le badge (polling AJAX depuis le dashboard)
     * ─── FIX : notExpired() ajouté pour cohérence avec la vue ────────
     */
    public function getUnreadCount()
    {
        $count = Notification::where('user_id', Auth::id())
            ->where('type', '!=', 'agent_message')
            ->notExpired()                         // ← AJOUTÉ : même filtre que la vue
            ->unread()
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Dernières notifications pour le popup (AJAX)
     */
    public function getLatest()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->where('type', '!=', 'agent_message')
            ->notExpired()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $unreadCount = Notification::where('user_id', Auth::id())
            ->where('type', '!=', 'agent_message')
            ->notExpired()
            ->unread()
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count'  => $unreadCount,
        ]);
    }

    /**
     * Supprimer une notification
     */
    public function destroy($id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->findOrFail($id);

        $notification->delete();

        return back()->with('success', 'Notification supprimée');
    }
}