<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'from_user_id',
        'perte_id',
        'type',
        'title',
        'content',
        'action_url',
        'icon',
        'color',
        'is_read',
        'read_at',
        'expires_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    /**
     * Relation avec l'utilisateur destinataire
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec l'utilisateur expéditeur (agent)
     */
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    /**
     * Relation avec la déclaration concernée
     */
    public function perte()
    {
        return $this->belongsTo(Perte::class);
    }

    /**
     * Marquer comme lu
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    /**
     * Scope pour les notifications non lues
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope pour les notifications non expirées
     */
    public function scopeNotExpired($query)
    {
        return $query->where(function($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Créer une notification de validation
     */
    public static function createValidationNotification($perte, $agent)
    {
        return self::create([
            'user_id' => $perte->user_id,
            'from_user_id' => $agent->id,
            'perte_id' => $perte->id,
            'type' => 'validation',
            'title' => '✅ Déclaration validée',
            'content' => "Votre déclaration #{$perte->id} a été validée par un agent.",
            'action_url' => route('perte.show', $perte->id),
            'icon' => '✅',
            'color' => 'success',
            'expires_at' => now()->addDays(30)
        ]);
    }

    /**
     * Créer une notification de rejet
     */
    public static function createRejectionNotification($perte, $agent, $motif)
    {
        return self::create([
            'user_id' => $perte->user_id,
            'from_user_id' => $agent->id,
            'perte_id' => $perte->id,
            'type' => 'rejet',
            'title' => '❌ Déclaration rejetée',
            'content' => "Votre déclaration #{$perte->id} a été rejetée. Motif : {$motif}",
            'action_url' => route('perte.show', $perte->id),
            'icon' => '❌',
            'color' => 'danger',
            'expires_at' => now()->addDays(30)
        ]);
    }

    /**
     * Créer une notification de message
     */
    public static function createMessageNotification($user, $agent, $message, $perte = null)
    {
        $data = [
            'user_id' => $user->id,
            'from_user_id' => $agent->id,
            'type' => 'message',
            'title' => '💬 Nouveau message',
            'content' => $message,
            'icon' => '💬',
            'color' => 'info',
            'expires_at' => now()->addDays(30)
        ];

        if ($perte) {
            $data['perte_id'] = $perte->id;
            $data['action_url'] = route('perte.show', $perte->id);
        }

        return self::create($data);
    }
}