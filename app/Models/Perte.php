<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perte extends Model
{
    use HasFactory;

    /**
     * Nom de la table
     */
    protected $table = 'pertes';

    /**
     * Les attributs qui peuvent être assignés en masse
     * Je combine tous les champs des deux versions
     */
    protected $fillable = [
        'user_id',
        'type_piece_id',
        'last_name',
        'first_name',
        'contact',
        'email',
        'type_piece',
        'numero_piece',
        'date_delivrance',
        'autorite_delivrance',
        'date_perte',
        'lieu_perte',
        'circonstances',
        'description',
        'statut',
        'date_declaration',
        'date_traitement',
        'copie_piece',
        'declaration_vol',
        'document_complementaire',
        'numero_declaration',
        'validated_by',
        'validated_at',
        'motif_rejet',
    ];

    /**
     * Statuts possibles (constantes de la version distante)
     */
    const STATUT_EN_ATTENTE = 'en_attente';
    const STATUT_VALIDEE = 'validee';
    const STATUT_REJETEE = 'rejetee';

    /**
     * Les attributs qui doivent être castés
     * Je combine tous les casts
     */
    protected $casts = [
        'date_delivrance' => 'date',
        'date_perte' => 'date',
        'date_declaration' => 'datetime',
        'date_traitement' => 'datetime',
        'validated_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur (propriétaire de la déclaration)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec le type de pièce
     */
    public function typePiece()
    {
        return $this->belongsTo(TypePiece::class);
    }

    /**
     * Relation avec l'agent validateur
     */
    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    /**
     * Scope pour les déclarations en attente
     */
    public function scopeEnAttente($query)
    {
        return $query->where('statut', self::STATUT_EN_ATTENTE);
    }

    /**
     * Scope pour les déclarations validées
     */
    public function scopeValidees($query)
    {
        return $query->where('statut', self::STATUT_VALIDEE);
    }

    /**
     * Scope pour les déclarations rejetées
     */
    public function scopeRejetees($query)
    {
        return $query->where('statut', self::STATUT_REJETEE);
    }

    /**
     * Vérifie si la déclaration est en attente
     */
    public function isEnAttente(): bool
    {
        return $this->statut === self::STATUT_EN_ATTENTE;
    }

    /**
     * Vérifie si la déclaration est validée
     */
    public function isValidee(): bool
    {
        return $this->statut === self::STATUT_VALIDEE;
    }

    /**
     * Vérifie si la déclaration est rejetée
     */
    public function isRejetee(): bool
    {
        return $this->statut === self::STATUT_REJETEE;
    }

    /**
     * Accesseur pour le badge de statut (version distante améliorée)
     */
    public function getStatutBadgeAttribute()
    {
        $badges = [
            self::STATUT_EN_ATTENTE => '<span class="badge bg-warning">En Attente</span>',
            self::STATUT_VALIDEE => '<span class="badge bg-success">Validée</span>',
            self::STATUT_REJETEE => '<span class="badge bg-danger">Rejetée</span>',
        ];

        return $badges[$this->statut] ?? '<span class="badge bg-secondary">Inconnu</span>';
    }

    /**
     * Accesseur pour le statut en français
     */
    public function getStatutTextAttribute()
    {
        $statuts = [
            self::STATUT_EN_ATTENTE => 'En Attente',
            self::STATUT_VALIDEE => 'Validée',
            self::STATUT_REJETEE => 'Rejetée',
        ];

        return $statuts[$this->statut] ?? $this->statut;
    }

    /**
     * Obtenir le nom complet du déclarant
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Générer un numéro de déclaration unique
     */
    public static function generateNumeroDeclaration()
    {
        $year = now()->year;
        $count = self::whereYear('created_at', $year)->count() + 1;
        return sprintf('DECL-%d-%05d', $year, $count);
    }

    /**
     * Boot method pour générer automatiquement le numéro
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($perte) {
            if (empty($perte->numero_declaration)) {
                $perte->numero_declaration = self::generateNumeroDeclaration();
            }
            
            // Initialiser la date de déclaration si non définie
            if (empty($perte->date_declaration)) {
                $perte->date_declaration = now();
            }
        });
    }
}