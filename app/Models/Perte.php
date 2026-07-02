<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perte extends Model
{
    use HasFactory;

    protected $table = 'pertes';

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
        'document_trouve_id',
        'date_restitution',
        'pdf_recu',
        'lieu_recuperation',
        'date_preparation',
        'date_recuperation',
        'date_passage_non_retrouve',
    ];

    // ✅ Définition des valeurs par défaut (correction)
    protected $attributes = [
        'statut' => self::STATUT_EN_ATTENTE,
    ];

    // Constantes des statuts
    const STATUT_EN_ATTENTE            = 'en_attente';
    const STATUT_EN_COURS              = 'en_cours';
    const STATUT_CORRESPONDANCE_TROUVEE = 'correspondance_trouvee';
    const STATUT_RESTITUE              = 'restitue';
    const STATUT_NON_RETROUVE           = 'non_retrouve';
    const STATUT_REJETEE               = 'rejetee';
    const STATUT_VALIDEE               = 'validee';
    const STATUT_RENOUVELLEMENT_EN_COURS = 'renouvellement_en_cours';
    const STATUT_PRET_RECUPERATION = 'pret_recuperation';

    public static $statuts = [
        self::STATUT_EN_ATTENTE            => 'En attente',
        self::STATUT_EN_COURS              => 'En cours de traitement',
        self::STATUT_CORRESPONDANCE_TROUVEE => 'Correspondance trouvée',
        self::STATUT_RESTITUE              => 'Restitué',
        self::STATUT_NON_RETROUVE           => 'Non retrouvé',
        self::STATUT_REJETEE               => 'Rejetée',
        self::STATUT_VALIDEE               => 'Validée',
    ];

    protected $casts = [
        'date_delivrance'   => 'date',
        'date_perte'        => 'date',
        'date_declaration'  => 'datetime',
        'date_traitement'   => 'datetime',
        'validated_at'      => 'datetime',
        'date_restitution'  => 'datetime',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
        'date_passage_non_retrouve' => 'datetime',
    ];

    // Relations...
    public function user() { return $this->belongsTo(User::class); }
    public function typePiece() { return $this->belongsTo(TypePiece::class); }
    public function validator() { return $this->belongsTo(User::class, 'validated_by'); }
    public function documentTrouve() { return $this->belongsTo(DocumentTrouve::class, 'document_trouve_id'); }
    public function notifications() { return $this->hasMany(Notification::class); }

    // Scopes
    public function scopeEnAttente($query) { return $query->where('statut', self::STATUT_EN_ATTENTE); }
    public function scopeEnCours($query) { return $query->where('statut', self::STATUT_EN_COURS); }
    public function scopeCorrespondanceTrouvee($query) { return $query->where('statut', self::STATUT_CORRESPONDANCE_TROUVEE); }
    public function scopeRestituees($query) { return $query->where('statut', self::STATUT_RESTITUE); }
    public function scopeNonRetrouvees($query) { return $query->where('statut', self::STATUT_NON_RETROUVE); }
    public function scopeRejetees($query) { return $query->where('statut', self::STATUT_REJETEE); }

    // Accesseurs
    public function getStatutBadgeAttribute()
    {
        $classes = [
            self::STATUT_EN_ATTENTE            => 'bg-warning',
            self::STATUT_EN_COURS              => 'bg-info',
            self::STATUT_CORRESPONDANCE_TROUVEE => 'bg-primary',
            self::STATUT_RESTITUE              => 'bg-success',
            self::STATUT_NON_RETROUVE           => 'bg-secondary',
            self::STATUT_REJETEE               => 'bg-danger',
            self::STATUT_VALIDEE               => 'bg-success',
        ];
        $class = $classes[$this->statut] ?? 'bg-secondary';
        $label = self::$statuts[$this->statut] ?? $this->statut;
        return "<span class='badge {$class}'>{$label}</span>";
    }

    public function getStatutTextAttribute()
    {
        return self::$statuts[$this->statut] ?? $this->statut;
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getTypePieceIconAttribute(): string
    {
        $map = [
            'Carte d\'identité (CNI)' => '🪪',
            'Passeport'               => '🛂',
            'Permis de conduire'      => '🚗',
            'Carte d\'électeur'       => '🗳️',
            'Acte de naissance'       => '📋',
            'Certificat de nationalité' => '📜',
        ];
        return $map[$this->type_piece] ?? '📄';
    }

    // Méthodes utilitaires
    public function isEnAttente(): bool { return $this->statut === self::STATUT_EN_ATTENTE; }
    public function isEnCours(): bool { return $this->statut === self::STATUT_EN_COURS; }
    public function isCorrespondanceTrouvee(): bool { return $this->statut === self::STATUT_CORRESPONDANCE_TROUVEE; }
    public function isRestitue(): bool { return $this->statut === self::STATUT_RESTITUE; }
    public function isNonRetrouve(): bool { return $this->statut === self::STATUT_NON_RETROUVE; }
    public function isRejetee(): bool { return $this->statut === self::STATUT_REJETEE; }

    public static function generateNumeroDeclaration()
    {
        $year = now()->year;
        $count = self::whereYear('created_at', $year)->count() + 1;
        return sprintf('DECL-%d-%05d', $year, $count);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($perte) {
            if (empty($perte->numero_declaration)) {
                $perte->numero_declaration = self::generateNumeroDeclaration();
            }
            if (empty($perte->date_declaration)) {
                $perte->date_declaration = now();
            }
        });

        static::updating(function ($perte) {
            if ($perte->isDirty('statut') && $perte->statut === self::STATUT_RESTITUE && empty($perte->date_restitution)) {
                $perte->date_restitution = now();
            }
        });
    }
}