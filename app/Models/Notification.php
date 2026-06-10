<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'from_user_id',
        'perte_id',
        'document_trouve_id',
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
        return $this->belongsTo(User::class, 'user_id');
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
     * Relation avec le document trouvé
     */
    public function documentTrouve()
    {
        return $this->belongsTo(DocumentTrouve::class);
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
     * Scope pour les notifications lues
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
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
     * Scope pour les notifications expirées
     */
    public function scopeExpired($query)
    {
        return $query->whereNotNull('expires_at')
            ->where('expires_at', '<=', now());
    }

    /**
     * Scope pour les notifications par type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope pour les notifications récentes
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    /**
     * Vérifier si la notification est lue
     */
    public function isRead()
    {
        return $this->is_read === true;
    }

    /**
     * Vérifier si la notification est expirée
     */
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at <= now();
    }

    /**
     * Obtenir le style CSS pour la notification
     */
    public function getStyleAttribute()
    {
        $styles = [
            'success' => 'border-left-color: #10b981; background: #ecfdf5;',
            'danger' => 'border-left-color: #ef4444; background: #fef2f2;',
            'warning' => 'border-left-color: #f59e0b; background: #fffbeb;',
            'info' => 'border-left-color: #3b82f6; background: #eff6ff;',
            'primary' => 'border-left-color: #8b5cf6; background: #f5f3ff;',
        ];
        
        return $styles[$this->color] ?? $styles['info'];
    }

    /**
     * Obtenir l'icône par défaut selon le type
     */
    public function getDefaultIconAttribute()
    {
        $icons = [
            'validation' => '✅',
            'rejet' => '❌',
            'message' => '💬',
            'document_trouve' => '🎉',
            'document_retrouve' => '🎉',
            'document_matché' => '🔗',
            'restitution' => '✅',
            'restitution_completee' => '🎉',
            'correspondance_trouvee' => '🔍',
            'annulation' => '🔄',
            'agent_message' => '📩',
            'document_trouve_matche' => '✅'
        ];
        
        return $icons[$this->type] ?? '🔔';
    }

    /**
     * Obtenir la couleur par défaut selon le type
     */
    public function getDefaultColorAttribute()
    {
        $colors = [
            'validation' => 'success',
            'rejet' => 'danger',
            'message' => 'info',
            'document_trouve' => 'warning',
            'document_retrouve' => 'success',
            'document_matché' => 'primary',
            'restitution' => 'success',
            'restitution_completee' => 'success',
            'correspondance_trouvee' => 'info',
            'annulation' => 'warning',
            'agent_message' => 'info',
            'document_trouve_matche' => 'success'
        ];
        
        return $colors[$this->type] ?? 'info';
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
            'content' => "Votre déclaration de perte pour {$perte->type_piece} a été validée avec succès. Votre numéro de déclaration est : " . ($perte->numero_declaration ?? '#'.$perte->id),
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
            'content' => "Votre déclaration de perte pour {$perte->type_piece} a été rejetée.\n\nMotif : {$motif}\n\nVous pouvez faire une nouvelle déclaration en corrigeant les informations.",
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
            'type' => 'agent_message',
            'title' => '📩 Message de l\'agent',
            'content' => $message,
            'icon' => '📩',
            'color' => 'info',
            'expires_at' => now()->addDays(30)
        ];

        if ($perte) {
            $data['perte_id'] = $perte->id;
            $data['action_url'] = route('perte.show', $perte->id);
        }

        return self::create($data);
    }

    /**
     * Créer une notification pour un document trouvé
     */
    public static function createDocumentTrouveNotification($perte, $documentTrouve)
    {
        return self::create([
            'user_id' => $perte->user_id,
            'type' => 'document_trouve',
            'title' => '🎉 Votre document a peut-être été trouvé !',
            'content' => "Un document correspondant à votre déclaration (perte de {$perte->type_piece}) a été trouvé le " . 
                         $documentTrouve->date_decouverte->format('d/m/Y') . " à " . 
                         $documentTrouve->lieu_decouverte . ". Notre équipe va vérifier et vous contactera.",
            'perte_id' => $perte->id,
            'document_trouve_id' => $documentTrouve->id,
            'action_url' => route('perte.show', $perte->id),
            'icon' => '🎉',
            'color' => 'warning',
            'expires_at' => now()->addDays(30)
        ]);
    }

    /**
     * Créer une notification pour un document retrouvé (matché)
     */
    public static function createDocumentRetrouveNotification($perte, $documentTrouve, $agent)
    {
        return self::create([
            'user_id' => $perte->user_id,
            'from_user_id' => $agent->id,
            'perte_id' => $perte->id,
            'document_trouve_id' => $documentTrouve->id,
            'type' => 'document_retrouve',
            'title' => '🎉 Bonne nouvelle ! Votre document a été retrouvé',
            'content' => "Votre {$perte->type_piece} perdu(e) a été retrouvé(e) à {$documentTrouve->lieu_decouverte}. Veuillez contacter l'agent pour récupérer votre document.",
            'action_url' => route('perte.show', $perte->id),
            'icon' => '🎉',
            'color' => 'success',
            'expires_at' => now()->addDays(30)
        ]);
    }

    /**
     * Créer une notification pour le trouveur (match)
     */
    public static function createMatchNotificationForFinder($documentTrouve, $agent)
    {
        if (!$documentTrouve->user_id) return null;
        
        return self::create([
            'user_id' => $documentTrouve->user_id,
            'from_user_id' => $agent->id,
            'document_trouve_id' => $documentTrouve->id,
            'type' => 'document_matché',
            'title' => '✅ Votre signalement a permis de retrouver un propriétaire !',
            'content' => "Le document que vous avez trouvé correspond à une déclaration de perte. Merci pour votre geste citoyen !",
            'action_url' => route('agent.documents-trouves.show', $documentTrouve->id),
            'icon' => '✅',
            'color' => 'primary',
            'expires_at' => now()->addDays(30)
        ]);
    }

    /**
     * Créer une notification de restitution pour le propriétaire
     */
    public static function createRestitutionNotification($perte, $agent)
    {
        return self::create([
            'user_id' => $perte->user_id,
            'from_user_id' => $agent->id,
            'perte_id' => $perte->id,
            'type' => 'restitution',
            'title' => '✅ Document restitué',
            'content' => "Votre {$perte->type_piece} a été officiellement restitué. Merci d'utiliser notre plateforme !",
            'action_url' => route('perte.show', $perte->id),
            'icon' => '✅',
            'color' => 'success',
            'expires_at' => now()->addDays(30)
        ]);
    }

    /**
     * Créer une notification de restitution pour le trouveur
     */
    public static function createRestitutionCompleteeNotification($documentTrouve, $agent)
    {
        if (!$documentTrouve->user_id) return null;
        
        return self::create([
            'user_id' => $documentTrouve->user_id,
            'from_user_id' => $agent->id,
            'document_trouve_id' => $documentTrouve->id,
            'type' => 'restitution_completee',
            'title' => '🎉 Restitution complétée',
            'content' => "Merci d'avoir rapporté ce document ! La restitution a été officiellement enregistrée.",
            'action_url' => route('agent.documents-trouves.show', $documentTrouve->id),
            'icon' => '🎉',
            'color' => 'success',
            'expires_at' => now()->addDays(30)
        ]);
    }

    /**
     * Créer une notification d'annulation
     */
    public static function createAnnulationNotification($perte, $agent)
    {
        return self::create([
            'user_id' => $perte->user_id,
            'from_user_id' => $agent->id,
            'perte_id' => $perte->id,
            'type' => 'annulation',
            'title' => '🔄 Annulation de traitement',
            'content' => 'Votre déclaration a été remise en attente pour réexamen.',
            'action_url' => route('perte.show', $perte->id),
            'icon' => '🔄',
            'color' => 'warning',
            'expires_at' => now()->addDays(30)
        ]);
    }

    /**
     * Créer une notification pour une correspondance trouvée
     */
    public static function createCorrespondanceNotification($perte, $documentTrouve)
    {
        return self::create([
            'user_id' => $perte->user_id,
            'type' => 'correspondance_trouvee',
            'title' => '🔍 Correspondance trouvée !',
            'content' => "Un document correspondant à votre déclaration a été signalé comme trouvé. Un agent va vérifier et vous contactera.",
            'perte_id' => $perte->id,
            'document_trouve_id' => $documentTrouve->id,
            'action_url' => route('perte.show', $perte->id),
            'icon' => '🔍',
            'color' => 'info',
            'expires_at' => now()->addDays(30)
        ]);
    }
}