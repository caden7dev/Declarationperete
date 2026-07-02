<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CitizenMessageController extends Controller
{
    /**
     * Afficher la page de messagerie du citoyen
     */
    public function index()
    {
        $citizen = Auth::user();
        // Liste des agents avec qui le citoyen a échangé des messages
        $agents = User::whereIn('role', ['agent', 'admin'])
                      ->where(function($q) use ($citizen) {
                          $q->whereHas('sentNotifications', function($q2) use ($citizen) {
                              $q2->where('user_id', $citizen->id)
                                 ->where('type', 'agent_message');
                          })->orWhereHas('receivedNotifications', function($q2) use ($citizen) {
                              $q2->where('from_user_id', $citizen->id)
                                 ->where('type', 'agent_message');
                          });
                      })
                      ->distinct()
                      ->get();

        // Pour chaque agent, récupérer le dernier message échangé avec le citoyen
        foreach ($agents as $agent) {
            $lastMessage = Notification::where(function($q) use ($agent, $citizen) {
                                $q->where('from_user_id', $agent->id)
                                  ->where('user_id', $citizen->id);
                            })
                            ->orWhere(function($q) use ($agent, $citizen) {
                                $q->where('from_user_id', $citizen->id)
                                  ->where('user_id', $agent->id);
                            })
                            ->where('type', 'agent_message')
                            ->orderBy('created_at', 'desc')
                            ->first();
            $agent->last_message = $lastMessage ? $lastMessage->content : null;
            $agent->last_message_time = $lastMessage ? $lastMessage->created_at->format('H:i') : null;
            $agent->is_online = false; // à adapter si vous avez un système de présence
        }

        return view('citizen.messages', compact('agents'));
    }

    /**
     * Endpoint AJAX : liste filtrée des agents
     */
    public function getAgentsList(Request $request)
    {
        $citizen = Auth::user();
        $search = $request->get('search', '');
        $query = User::whereIn('role', ['agent', 'admin'])
                     ->where(function($q) use ($citizen) {
                         $q->whereHas('sentNotifications', function($q2) use ($citizen) {
                             $q2->where('user_id', $citizen->id)
                                ->where('type', 'agent_message');
                         })->orWhereHas('receivedNotifications', function($q2) use ($citizen) {
                             $q2->where('from_user_id', $citizen->id)
                                ->where('type', 'agent_message');
                         });
                     });
        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }
        $agents = $query->distinct()->get();

        $data = [];
        foreach ($agents as $agent) {
            $lastMessage = Notification::where(function($q) use ($agent, $citizen) {
                                $q->where('from_user_id', $agent->id)->where('user_id', $citizen->id);
                            })
                            ->orWhere(function($q) use ($agent, $citizen) {
                                $q->where('from_user_id', $citizen->id)->where('user_id', $agent->id);
                            })
                            ->where('type', 'agent_message')
                            ->orderBy('created_at', 'desc')
                            ->first();
            $data[] = [
                'id' => $agent->id,
                'name' => $agent->name,
                'avatarInitials' => $this->getInitials($agent->name),
                'status' => $this->isUserOnline($agent->id) ? 'online' : 'offline',
                'lastMessagePreview' => $lastMessage ? (strlen($lastMessage->content) > 42 ? substr($lastMessage->content, 0, 42).'…' : $lastMessage->content) : 'Aucun message',
                'lastMessageTime' => $lastMessage ? $lastMessage->created_at->format('H:i') : null,
            ];
        }
        return response()->json($data);
    }

    /**
     * Endpoint AJAX : historique des messages avec un agent
     */
    public function getMessages($agentId)
    {
        $citizenId = Auth::id();
        $messages = Notification::where(function($q) use ($agentId, $citizenId) {
                                    $q->where('from_user_id', $agentId)->where('user_id', $citizenId);
                                })
                                ->orWhere(function($q) use ($agentId, $citizenId) {
                                    $q->where('from_user_id', $citizenId)->where('user_id', $agentId);
                                })
                                ->where('type', 'agent_message')
                                ->orderBy('created_at', 'asc')
                                ->get(['id', 'from_user_id', 'user_id', 'content', 'created_at', 'is_read']);

        // Marquer les messages reçus par le citoyen comme lus
        Notification::where('user_id', $citizenId)
                    ->where('from_user_id', $agentId)
                    ->where('type', 'agent_message')
                    ->where('is_read', false)
                    ->update(['is_read' => true]);

        $formatted = [];
        foreach ($messages as $msg) {
            $formatted[] = [
                'id' => $msg->id,
                'direction' => ($msg->from_user_id == $citizenId) ? 'outgoing' : 'incoming',
                'text' => $msg->content,
                'statusText' => $msg->is_read ? 'Lu' : 'Envoyé',
                'timestamp' => $msg->created_at->format('H:i'),
            ];
        }
        return response()->json($formatted);
    }

    /**
     * Envoi de message (AJAX) par le citoyen vers un agent
     */
    public function sendMessageAjax(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|exists:users,id',
            'message' => 'required|string|min:1|max:1000'
        ]);

        $agent = User::findOrFail($request->agent_id);
        $citizen = Auth::user();

        // Créer une notification avec le citoyen comme expéditeur
        $notification = Notification::createMessageNotification($agent, $citizen, $request->message);

        return response()->json([
            'success' => true,
            'message' => 'Message envoyé',
            'data' => [
                'id' => $notification->id,
                'direction' => 'outgoing',
                'text' => $request->message,
                'statusText' => 'Envoyé',
                'timestamp' => now()->format('H:i'),
            ]
        ]);
    }

    /**
     * Récupérer le nombre de messages non lus pour le citoyen (badge dynamique)
     */
    public function unreadCount()
    {
        $citizenId = Auth::id();
        $count = Notification::where('user_id', $citizenId)
                    ->where('type', 'agent_message')
                    ->where('is_read', false)
                    ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Alias pour sendMessageAjax (si utilisé par les routes)
     */
    public function envoyerMessage(Request $request)
    {
        return $this->sendMessageAjax($request);
    }

    /**
     * Alias pour getMessages (si utilisé par les routes)
     */
    public function messageHistory($agentId)
    {
        return $this->getMessages($agentId);
    }

    // ===== HELPERS =====

    private function getInitials($name)
    {
        $parts = explode(' ', trim($name));
        $initials = '';
        foreach ($parts as $part) {
            if (!empty($part)) {
                $initials .= strtoupper(substr($part, 0, 1));
            }
        }
        return substr($initials, 0, 2);
    }

    private function isUserOnline($userId)
    {
        // À adapter selon votre logique (cache, session, colonne is_online)
        return false;
    }
}