<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentTrouve extends Model
{
    use HasFactory;

    /**
     * Nom de la table associée.
     */
    protected $table = 'documents_trouves';

    protected $fillable = [
        'user_id',
        'nom_declarant',
        'prenom_declarant',
        'email_declarant',
        'telephone_declarant',
        'type_document',
        'nom_sur_document',
        'prenom_sur_document',
        'numero_document',
        'date_decouverte',
        'lieu_decouverte',
        'description',
        'circonstances',
        'photo_document',
        'statut',
        'perte_matchee_id',      // ID de la perte associée
        'date_restitution',
        'numero_declaration',
        'notes_admin',
        'perte_id',              // Pour cohérence avec l'autre relation (optionnel, à uniformiser)
    ];

    protected $casts = [
        'date_decouverte' => 'date',
        'date_restitution' => 'datetime',
    ];

    /**
     * Constantes de statuts pour les documents trouvés
     */
    const STATUT_EN_ATTENTE = 'en_attente';
    const STATUT_MATCHE = 'matche';
    const STATUT_RESTITUE = 'restitue';

    /**
     * Liste des statuts pour affichage
     */
    public static $statuts = [
        self::STATUT_EN_ATTENTE => 'En attente',
        self::STATUT_MATCHE => 'Matché',
        self::STATUT_RESTITUE => 'Restitué',
    ];

    /**
     * Génère un numéro de déclaration unique
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($document) {
            if (empty($document->numero_declaration)) {
                $document->numero_declaration = 'DT-' . strtoupper(uniqid()) . '-' . now()->year;
            }
        });
    }

    /**
     * Relation avec l'utilisateur qui a trouvé le document (si connecté)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec la perte matchée (via perte_matchee_id)
     */
    public function perte()
    {
        return $this->belongsTo(Perte::class, 'perte_matchee_id');
    }

    /**
     * Alias pour la relation avec la perte (pour uniformité)
     */
    public function perteMatchee()
    {
        return $this->belongsTo(Perte::class, 'perte_matchee_id');
    }

    /**
     * Scopes
     */
    public function scopeEnAttente($query)
    {
        return $query->where('statut', self::STATUT_EN_ATTENTE);
    }

    public function scopeMatches($query)
    {
        return $query->where('statut', self::STATUT_MATCHE);
    }

    public function scopeRestitues($query)
    {
        return $query->where('statut', self::STATUT_RESTITUE);
    }

    /**
     * Chercher des correspondances avec des pertes (en tenant compte des nouveaux statuts)
     */
    public function chercherCorrespondances()
    {
        return Perte::whereIn('statut', [Perte::STATUT_EN_ATTENTE, Perte::STATUT_EN_COURS])
            ->where(function($q) {
                $q->where('numero_piece', $this->numero_document)
                  ->orWhere('type_piece', $this->type_document)
                  ->orWhere('last_name', $this->nom_sur_document)
                  ->orWhere('first_name', $this->prenom_sur_document);
            })
            ->get();
    }

    /**
     * Associer ce document trouvé à une déclaration de perte.
     * Met à jour les deux modèles et change les statuts.
     */
    public function associerAPerte(Perte $perte)
    {
        // Vérifier que la perte est dans un statut compatible
        if (!in_array($perte->statut, [Perte::STATUT_EN_ATTENTE, Perte::STATUT_EN_COURS])) {
            throw new \Exception('La déclaration de perte ne peut pas être associée dans son statut actuel.');
        }

        // Mettre à jour le document trouvé
        $this->update([
            'perte_matchee_id' => $perte->id,
            'statut' => self::STATUT_MATCHE,
        ]);

        // Mettre à jour la perte
        $perte->update([
            'statut' => Perte::STATUT_CORRESPONDANCE_TROUVEE,
            'document_trouve_id' => $this->id,
            'date_traitement' => now(),
        ]);

        return $this;
    }

    /**
     * Marquer comme restitué
     */
    public function marquerRestitue()
    {
        $this->update([
            'statut' => self::STATUT_RESTITUE,
            'date_restitution' => now()
        ]);

        // Mettre à jour la perte associée si elle existe
        if ($this->perte_matchee_id) {
            $perte = Perte::find($this->perte_matchee_id);
            if ($perte && $perte->statut !== Perte::STATUT_RESTITUE) {
                $perte->update([
                    'statut' => Perte::STATUT_RESTITUE,
                    'date_restitution' => now(),
                ]);
            }
        }
    }

    /**
     * Vérifier si le document est en attente
     */
    public function isEnAttente(): bool
    {
        return $this->statut === self::STATUT_EN_ATTENTE;
    }

    /**
     * Vérifier si le document est matché
     */
    public function isMatche(): bool
    {
        return $this->statut === self::STATUT_MATCHE;
    }

    /**
     * Vérifier si le document est restitué
     */
    public function isRestitue(): bool
    {
        return $this->statut === self::STATUT_RESTITUE;
    }

    /**
     * Accesseur pour le badge de statut (HTML)
     */
    public function getStatutBadgeAttribute()
    {
        $classes = [
            self::STATUT_EN_ATTENTE => 'bg-warning',
            self::STATUT_MATCHE     => 'bg-primary',
            self::STATUT_RESTITUE   => 'bg-success',
        ];
        $class = $classes[$this->statut] ?? 'bg-secondary';
        $label = self::$statuts[$this->statut] ?? $this->statut;
        return "<span class='badge {$class}'>{$label}</span>";
    }

    /**
     * Texte du statut en français
     */
    public function getStatutTextAttribute()
    {
        return self::$statuts[$this->statut] ?? $this->statut;
    }
}