<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentOfficiel extends Model
{
    use HasFactory;

    /**
     * ✅ FORCER LE NOM DE LA TABLE
     */
    protected $table = 'documents_officiels';

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type_piece',
        'numero_document',
        'nom_complet',
        'nom',
        'prenom',
        'date_naissance',
        'lieu_naissance',
        'date_delivrance',
        'date_expiration',
        'autorite_delivrance',
        'lieu_delivrance',
        'est_valide',
        'est_volé',
        'est_perdu',
        'est_suspendu',
        'remarques',
        'photo_url',
        'derniere_verification',
        'source',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_naissance' => 'date',
        'date_delivrance' => 'date',
        'date_expiration' => 'date',
        'derniere_verification' => 'datetime',
        'est_valide' => 'boolean',
        'est_volé' => 'boolean',
        'est_perdu' => 'boolean',
        'est_suspendu' => 'boolean',
    ];

    /**
     * Vérifier si le document est encore valide (non expiré)
     */
    public function estValide(): bool
    {
        if (!$this->est_valide) {
            return false;
        }

        if ($this->est_volé || $this->est_perdu || $this->est_suspendu) {
            return false;
        }

        if ($this->date_expiration && $this->date_expiration->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Vérifier si le document est expiré
     */
    public function estExpiré(): bool
    {
        return $this->date_expiration && $this->date_expiration->isPast();
    }

    /**
     * Obtenir le statut du document
     */
    public function getStatutAttribute(): string
    {
        if (!$this->est_valide) {
            return 'invalide';
        }

        if ($this->est_volé) {
            return 'volé';
        }

        if ($this->est_perdu) {
            return 'perdu';
        }

        if ($this->est_suspendu) {
            return 'suspendu';
        }

        if ($this->estExpiré()) {
            return 'expiré';
        }

        return 'valide';
    }

    /**
     * Obtenir la couleur du statut
     */
    public function getStatutCouleurAttribute(): string
    {
        $statut = $this->statut;
        
        if ($statut === 'valide') {
            return 'green';
        }
        
        if ($statut === 'expiré') {
            return 'orange';
        }
        
        if (in_array($statut, ['volé', 'perdu', 'suspendu'])) {
            return 'red';
        }
        
        if ($statut === 'invalide') {
            return 'gray';
        }
        
        return 'gray';
    }

    /**
     * Obtenir le libellé du statut
     */
    public function getStatutLibelleAttribute(): string
    {
        $statut = $this->statut;
        
        if ($statut === 'valide') {
            return '✅ Valide';
        }
        
        if ($statut === 'expiré') {
            return '⚠️ Expiré';
        }
        
        if ($statut === 'volé') {
            return '🚨 Volé';
        }
        
        if ($statut === 'perdu') {
            return '❌ Perdu';
        }
        
        if ($statut === 'suspendu') {
            return '⛔ Suspendu';
        }
        
        if ($statut === 'invalide') {
            return '❌ Invalide';
        }
        
        return '❓ Inconnu';
    }

    /**
     * Scope pour les documents valides
     */
    public function scopeValides($query)
    {
        return $query->where('est_valide', true)
            ->where('est_volé', false)
            ->where('est_perdu', false)
            ->where('est_suspendu', false)
            ->where(function($q) {
                $q->whereNull('date_expiration')
                  ->orWhere('date_expiration', '>', now());
            });
    }

    /**
     * Scope pour les documents expirés
     */
    public function scopeExpires($query)
    {
        return $query->where('date_expiration', '<', now());
    }

    /**
     * Scope pour les documents volés
     */
    public function scopeVoles($query)
    {
        return $query->where('est_volé', true);
    }

    /**
     * Scope pour les documents perdus
     */
    public function scopePerdus($query)
    {
        return $query->where('est_perdu', true);
    }

    /**
     * Rechercher un document par son numéro
     */
    public static function findByNumero($numeroDocument)
    {
        return self::where('numero_document', $numeroDocument)->first();
    }

    /**
     * Vérifier si un numéro de document existe
     */
    public static function existe($numeroDocument): bool
    {
        return self::where('numero_document', $numeroDocument)->exists();
    }

    /**
     * Vérifier si le document est valide en base officielle
     */
    public static function verifierDocument($typePiece, $numeroDocument): array
    {
        $document = self::where('numero_document', $numeroDocument)
            ->where('type_piece', $typePiece)
            ->first();

        if (!$document) {
            return [
                'valide' => false,
                'trouve' => false,
                'message' => 'Document non trouvé dans la base officielle.'
            ];
        }

        return [
            'valide' => $document->estValide(),
            'trouve' => true,
            'document' => $document,
            'statut' => $document->statut,
            'statut_libelle' => $document->statut_libelle,
            'nom_complet' => $document->nom_complet,
            'date_expiration' => $document->date_expiration,
            'message' => $document->estValide() 
                ? '✅ Document valide' 
                : '❌ Document non valide (statut: ' . $document->statut_libelle . ')'
        ];
    }
}