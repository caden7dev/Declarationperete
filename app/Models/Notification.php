<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

    // =============================================
    // 🔒 SÉCURITÉ : Bloquer toute création sans user_id
    // =============================================
    protected static function booted()
    {
        static::creating(function ($notification) {
            if (empty($notification->user_id)) {
                Log::error('🔴 Blocage : tentative de création d\'une notification sans user_id', [
                    'notification' => $notification->toArray(),
                    'trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5)
                ]);
                // En mode debug, on lance une exception pour identifier la source
                if (config('app.debug')) {
                    throw new \InvalidArgumentException('Impossible de créer une notification sans destinataire (user_id manquant).');
                }
                return false; // empêche la création
            }
            return true;
        });
    }

    // =============================================
    // RELATIONS
    // =============================================

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function perte()
    {
        return $this->belongsTo(Perte::class);
    }

    public function documentTrouve()
    {
        return $this->belongsTo(DocumentTrouve::class);
    }

    // =============================================
    // MÉTHODES
    // =============================================

    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    // =============================================
    // SCOPES
    // =============================================

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeNotExpired($query)
    {
        return $query->where(function($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    public function scopeExpired($query)
    {
        return $query->whereNotNull('expires_at')
            ->where('expires_at', '<=', now());
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    // =============================================
    // ACCESSORS
    // =============================================

    public function isRead()
    {
        return $this->is_read === true;
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at <= now();
    }

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
            'document_trouve_matche' => '✅',
            'system' => '🔔',
        ];
        
        return $icons[$this->type] ?? '🔔';
    }

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
            'document_trouve_matche' => 'success',
            'system' => 'info',
        ];
        
        return $colors[$this->type] ?? 'info';
    }

    // =============================================
    // MÉTHODES DE CRÉATION (avec vérifications)
    // =============================================

    /**
     * Créer une notification système (validation, rejet, non-retrouvé, etc.)
     * 
     * @param User|null $user L'utilisateur destinataire (doit être une instance unique de User)
     * @param string $title
     * @param string $content
     * @param string|null $action_url
     * @param Perte|null $perte
     * @param string $icon
     * @param string $color
     * @return Notification|null
     */
    public static function createSystemNotification($user, $title, $content, $action_url = null, $perte = null, $icon = '🔔', $color = 'info')
    {
        // 🔒 Vérification stricte
        if (!$user instanceof User) {
            Log::error('Tentative de création d\'une notification sans utilisateur valide', [
                'user' => $user,
                'title' => $title,
                'content' => $content,
            ]);
            return null;
        }

        if (!$user->exists) {
            Log::warning('Tentative de notification pour un utilisateur non persisté', ['user_id' => $user->id]);
            return null;
        }

        $data = [
            'user_id' => $user->id,
            'type' => 'system',
            'title' => $title,
            'content' => $content,
            'action_url' => $action_url,
            'icon' => $icon,
            'color' => $color,
            'expires_at' => now()->addDays(30)
        ];

        if ($perte) {
            $data['perte_id'] = $perte->id;
        }

        return self::create($data);
    }

    public static function createValidationNotification($perte, $agent)
    {
        if (!$perte->user) {
            Log::warning('Notification de validation impossible : pas de propriétaire', ['perte_id' => $perte->id]);
            return null;
        }
        $typeNom = $perte->typePiece ? $perte->typePiece->nom : $perte->type_piece;
        return self::createSystemNotification(
            $perte->user,
            '✅ Déclaration validée',
            "Votre déclaration de perte pour {$typeNom} a été validée avec succès. Votre numéro de déclaration est : " . ($perte->numero_declaration ?? '#'.$perte->id),
            route('perte.show', $perte->id),
            $perte,
            '✅',
            'success'
        );
    }

    public static function createRejectionNotification($perte, $agent, $motif)
    {
        if (!$perte->user) {
            Log::warning('Notification de rejet impossible : pas de propriétaire', ['perte_id' => $perte->id]);
            return null;
        }
        $typeNom = $perte->typePiece ? $perte->typePiece->nom : $perte->type_piece;
        return self::createSystemNotification(
            $perte->user,
            '❌ Déclaration rejetée',
            "Votre déclaration de perte pour {$typeNom} a été rejetée.\n\nMotif : {$motif}\n\nVous pouvez faire une nouvelle déclaration en corrigeant les informations.",
            route('perte.show', $perte->id),
            $perte,
            '❌',
            'danger'
        );
    }

    public static function createMessageNotification($user, $agent, $message, $perte = null)
    {
        if (!$user instanceof User) {
            Log::error('Tentative de message sans utilisateur destinataire valide');
            return null;
        }
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

    public static function createDocumentTrouveNotification($perte, $documentTrouve)
    {
        if (!$perte->user) {
            Log::warning('Notification document trouvé impossible : pas de propriétaire', ['perte_id' => $perte->id]);
            return null;
        }
        $typeNom = $perte->typePiece ? $perte->typePiece->nom : $perte->type_piece;
        return self::createSystemNotification(
            $perte->user,
            '🎉 Votre document a peut-être été trouvé !',
            "Un document correspondant à votre déclaration (perte de {$typeNom}) a été trouvé le " . 
            $documentTrouve->date_decouverte->format('d/m/Y') . " à " . 
            $documentTrouve->lieu_decouverte . ". Notre équipe va vérifier et vous contactera.",
            route('perte.show', $perte->id),
            $perte,
            '🎉',
            'warning'
        );
    }

    public static function createDocumentRetrouveNotification($perte, $documentTrouve, $agent)
    {
        if (!$perte->user) {
            Log::warning('Notification document retrouvé impossible : pas de propriétaire', ['perte_id' => $perte->id]);
            return null;
        }
        $typeNom = $perte->typePiece ? $perte->typePiece->nom : $perte->type_piece;
        return self::createSystemNotification(
            $perte->user,
            '🎉 Bonne nouvelle ! Votre document a été retrouvé',
            "Votre {$typeNom} perdu(e) a été retrouvé(e) à {$documentTrouve->lieu_decouverte}. Veuillez contacter l'agent pour récupérer votre document.",
            route('perte.show', $perte->id),
            $perte,
            '🎉',
            'success'
        );
    }

    public static function createMatchNotificationForFinder($documentTrouve, $agent)
    {
        if (!$documentTrouve->user_id) return null;
        
        $finder = $documentTrouve->user;
        if (!$finder instanceof User) {
            Log::warning('Le trouveur du document n\'est pas un utilisateur valide', ['document_id' => $documentTrouve->id]);
            return null;
        }

        return self::createSystemNotification(
            $finder,
            '✅ Votre signalement a permis de retrouver un propriétaire !',
            "Le document que vous avez trouvé correspond à une déclaration de perte. Merci pour votre geste citoyen !",
            route('agent.documents-trouves.show', $documentTrouve->id),
            null,
            '✅',
            'primary'
        );
    }

    public static function createRestitutionNotification($perte, $agent)
    {
        if (!$perte->user) {
            Log::warning('Notification de restitution impossible : pas de propriétaire', ['perte_id' => $perte->id]);
            return null;
        }
        $typeNom = $perte->typePiece ? $perte->typePiece->nom : $perte->type_piece;
        return self::createSystemNotification(
            $perte->user,
            '✅ Document restitué',
            "Votre {$typeNom} a été officiellement restitué. Merci d'utiliser notre plateforme !",
            route('perte.show', $perte->id),
            $perte,
            '✅',
            'success'
        );
    }

    public static function createRestitutionCompleteeNotification($documentTrouve, $agent)
    {
        if (!$documentTrouve->user_id) return null;
        
        $finder = $documentTrouve->user;
        if (!$finder instanceof User) {
            Log::warning('Le trouveur du document n\'est pas un utilisateur valide pour la restitution', ['document_id' => $documentTrouve->id]);
            return null;
        }

        $typeNom = $documentTrouve->type_document ?? 'document';
        return self::createSystemNotification(
            $finder,
            '🎉 Restitution complétée',
            "Merci d'avoir rapporté ce {$typeNom} ! La restitution a été officiellement enregistrée.",
            route('agent.documents-trouves.show', $documentTrouve->id),
            null,
            '🎉',
            'success'
        );
    }

    public static function createAnnulationNotification($perte, $agent)
    {
        if (!$perte->user) {
            Log::warning('Notification d\'annulation impossible : pas de propriétaire', ['perte_id' => $perte->id]);
            return null;
        }
        return self::createSystemNotification(
            $perte->user,
            '🔄 Annulation de traitement',
            'Votre déclaration a été remise en attente pour réexamen.',
            route('perte.show', $perte->id),
            $perte,
            '🔄',
            'warning'
        );
    }

    public static function createCorrespondanceNotification($perte, $documentTrouve)
    {
        if (!$perte->user) {
            Log::warning('Notification de correspondance impossible : pas de propriétaire', ['perte_id' => $perte->id]);
            return null;
        }
        $typeNom = $perte->typePiece ? $perte->typePiece->nom : $perte->type_piece;
        return self::createSystemNotification(
            $perte->user,
            '🔍 Correspondance trouvée !',
            "Un document correspondant à votre déclaration de {$typeNom} a été signalé comme trouvé. Un agent va vérifier et vous contactera.",
            route('perte.show', $perte->id),
            $perte,
            '🔍',
            'info'
        );
    }
}