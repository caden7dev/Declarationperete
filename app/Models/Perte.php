<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
        'date_expiration',
        'autorite_delivrance',
        'date_perte',
        'lieu_perte',
        'circonstances',
        'description',
        'statut',
        'statut_verification',
        'date_declaration',
        'date_traitement',
        'copie_piece',
        'declaration_vol',
        'document_complementaire',
        'numero_declaration',
        'validated_by',
        'validated_at',
        'verified_by',
        'verified_at',
        'motif_rejet',
        'document_trouve_id',
        'date_restitution',
        'pdf_recu',
        'lieu_recuperation',
        'date_preparation',
        'date_recuperation',
        'date_passage_non_retrouve',
        'assigned_to',
        'assigned_at',
        'is_locked',
    ];

    protected $attributes = [
        'statut' => self::STATUT_EN_ATTENTE,
        'statut_verification' => self::STATUT_VERIFICATION_AUTO,
    ];

    // ==========================
    // CONSTANTES STATUTS
    // ==========================

    const STATUT_EN_ATTENTE = 'en_attente';
    const STATUT_EN_ATTENTE_VERIFICATION = 'en_attente_verification';
    const STATUT_EN_COURS = 'en_cours';
    const STATUT_CORRESPONDANCE_TROUVEE = 'correspondance_trouvee';
    const STATUT_RESTITUE = 'restitue';
    const STATUT_NON_RETROUVE = 'non_retrouve';
    const STATUT_REJETEE = 'rejetee';
    const STATUT_VALIDEE = 'validee';
    const STATUT_RENOUVELLEMENT_EN_COURS = 'renouvellement_en_cours';
    const STATUT_PRET_RECUPERATION = 'pret_recuperation';

    // Constantes pour les statuts de vérification
    const STATUT_VERIFICATION_AUTO = 'auto';
    const STATUT_VERIFICATION_MANUELLE = 'manuelle';

    // Mise à jour : Ajout du nouveau statut
    public static $statuts = [
        self::STATUT_EN_ATTENTE => 'En attente',
        self::STATUT_EN_ATTENTE_VERIFICATION => 'En attente de vérification',
        self::STATUT_EN_COURS => 'En cours de traitement',
        self::STATUT_CORRESPONDANCE_TROUVEE => 'Correspondance trouvée',
        self::STATUT_RESTITUE => 'Restitué',
        self::STATUT_NON_RETROUVE => 'Non retrouvé',
        self::STATUT_REJETEE => 'Rejetée',
        self::STATUT_VALIDEE => 'Validée',
        self::STATUT_RENOUVELLEMENT_EN_COURS => 'Renouvellement en cours',
        self::STATUT_PRET_RECUPERATION => 'Prêt pour récupération',
    ];

    // Statuts de vérification
    public static $statutsVerification = [
        self::STATUT_VERIFICATION_AUTO => 'Vérification automatique',
        self::STATUT_VERIFICATION_MANUELLE => 'Vérification manuelle',
    ];

    // ==========================
    // CASTS
    // ==========================

    protected $casts = [
        'date_delivrance' => 'date',
        'date_expiration' => 'date',
        'date_perte' => 'date',
        'date_declaration' => 'datetime',
        'date_traitement' => 'datetime',
        'validated_at' => 'datetime',
        'verified_at' => 'datetime',
        'date_restitution' => 'datetime',
        'date_preparation' => 'datetime',
        'date_recuperation' => 'datetime',
        'date_passage_non_retrouve' => 'datetime',
        'assigned_at' => 'datetime',
        'is_locked' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==========================
    // RELATIONS
    // ==========================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function typePiece()
    {
        return $this->belongsTo(TypePiece::class);
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function assignedAgent()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function documentTrouve()
    {
        return $this->belongsTo(DocumentTrouve::class, 'document_trouve_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // ==========================
    // SCOPES
    // ==========================

    public function scopeEnAttente($query)
    {
        return $query->where('statut', self::STATUT_EN_ATTENTE);
    }

    public function scopeEnAttenteVerification($query)
    {
        return $query->where('statut', self::STATUT_EN_ATTENTE_VERIFICATION);
    }

    public function scopeEnCours($query)
    {
        return $query->where('statut', self::STATUT_EN_COURS);
    }

    public function scopeCorrespondanceTrouvee($query)
    {
        return $query->where('statut', self::STATUT_CORRESPONDANCE_TROUVEE);
    }

    public function scopeRestituees($query)
    {
        return $query->where('statut', self::STATUT_RESTITUE);
    }

    public function scopeNonRetrouvees($query)
    {
        return $query->where('statut', self::STATUT_NON_RETROUVE);
    }

    public function scopeRejetees($query)
    {
        return $query->where('statut', self::STATUT_REJETEE);
    }

    public function scopeVerificationAuto($query)
    {
        return $query->where('statut_verification', self::STATUT_VERIFICATION_AUTO);
    }

    public function scopeVerificationManuelle($query)
    {
        return $query->where('statut_verification', self::STATUT_VERIFICATION_MANUELLE);
    }

    public function scopeAvecDateExpiration($query)
    {
        return $query->whereNotNull('date_expiration');
    }

    public function scopeSansDateExpiration($query)
    {
        return $query->whereNull('date_expiration');
    }

    public function scopeExpirees($query)
    {
        return $query->where('date_expiration', '<', now());
    }

    public function scopeValides($query)
    {
        return $query->where('statut', self::STATUT_VALIDEE);
    }

    public function scopeEnAttenteOuVerification($query)
    {
        return $query->whereIn('statut', [self::STATUT_EN_ATTENTE, self::STATUT_EN_ATTENTE_VERIFICATION]);
    }

    // ==========================
    // ACCESSORS
    // ==========================

    public function getStatutBadgeAttribute()
    {
        $classes = [
            self::STATUT_EN_ATTENTE => 'bg-warning',
            self::STATUT_EN_ATTENTE_VERIFICATION => 'bg-warning',
            self::STATUT_EN_COURS => 'bg-info',
            self::STATUT_CORRESPONDANCE_TROUVEE => 'bg-primary',
            self::STATUT_RESTITUE => 'bg-success',
            self::STATUT_NON_RETROUVE => 'bg-secondary',
            self::STATUT_REJETEE => 'bg-danger',
            self::STATUT_VALIDEE => 'bg-success',
            self::STATUT_RENOUVELLEMENT_EN_COURS => 'bg-info',
            self::STATUT_PRET_RECUPERATION => 'bg-success',
        ];

        $class = $classes[$this->statut] ?? 'bg-secondary';
        $label = self::$statuts[$this->statut] ?? $this->statut;

        return "<span class='badge {$class}'>{$label}</span>";
    }

    public function getVerificationBadgeAttribute()
    {
        $classes = [
            self::STATUT_VERIFICATION_AUTO => 'bg-success',
            self::STATUT_VERIFICATION_MANUELLE => 'bg-warning',
        ];

        $class = $classes[$this->statut_verification] ?? 'bg-secondary';
        $label = self::$statutsVerification[$this->statut_verification] ?? $this->statut_verification;

        return "<span class='badge {$class}'>{$label}</span>";
    }

    public function getStatutTextAttribute()
    {
        return self::$statuts[$this->statut] ?? $this->statut;
    }

    public function getVerificationTextAttribute()
    {
        return self::$statutsVerification[$this->statut_verification] ?? $this->statut_verification;
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    // ✅ Nouveaux accesseurs pour la date d'expiration
    public function getDateExpirationFormattedAttribute(): string
    {
        return $this->date_expiration ? $this->date_expiration->format('d/m/Y') : 'Non renseignée';
    }

    public function getDateDelivranceFormattedAttribute(): string
    {
        return $this->date_delivrance ? $this->date_delivrance->format('d/m/Y') : 'Non renseignée';
    }

    public function getDatePerteFormattedAttribute(): string
    {
        return $this->date_perte ? $this->date_perte->format('d/m/Y') : 'Non renseignée';
    }

    // ==========================
    // MÉTHODES UTILITAIRES
    // ==========================

    public function hasExpirationDate(): bool
    {
        return !is_null($this->date_expiration);
    }

    public function isExpired(): bool
    {
        return $this->hasExpirationDate() && $this->date_expiration->isPast();
    }

    public function isValid(): bool
    {
        return !$this->isExpired();
    }

    public function needsManualVerification(): bool
    {
        return $this->statut_verification === self::STATUT_VERIFICATION_MANUELLE
            || $this->statut === self::STATUT_EN_ATTENTE_VERIFICATION;
    }

    public function canBeProcessedAutomatically(): bool
    {
        return $this->hasExpirationDate() 
            && $this->statut_verification === self::STATUT_VERIFICATION_AUTO;
    }

    public function isInVerification(): bool
    {
        return $this->statut === self::STATUT_EN_ATTENTE_VERIFICATION;
    }

    public function isWaiting(): bool
    {
        return $this->statut === self::STATUT_EN_ATTENTE;
    }

    public function isInProgress(): bool
    {
        return $this->statut === self::STATUT_EN_COURS;
    }

    public function isMatched(): bool
    {
        return $this->statut === self::STATUT_CORRESPONDANCE_TROUVEE;
    }

    public function isReturned(): bool
    {
        return $this->statut === self::STATUT_RESTITUE;
    }

    public function isNotFound(): bool
    {
        return $this->statut === self::STATUT_NON_RETROUVE;
    }

    public function isRejected(): bool
    {
        return $this->statut === self::STATUT_REJETEE;
    }

    public function isValidated(): bool
    {
        return $this->statut === self::STATUT_VALIDEE;
    }

    public function isReadyForPickup(): bool
    {
        return $this->statut === self::STATUT_PRET_RECUPERATION;
    }

    public function isLockedByMe(): bool
    {
        return $this->is_locked && $this->assigned_to === auth()->id();
    }

    // ==========================
    // GESTION PRISE EN CHARGE
    // ==========================

    public function isAssigned()
    {
        return !is_null($this->assigned_to);
    }

    public function isAssignedToMe()
    {
        return $this->assigned_to == auth()->id();
    }

    public function isLocked()
    {
        return $this->is_locked;
    }

    public function canBeTaken()
    {
        return in_array($this->statut, [
            self::STATUT_EN_ATTENTE,
            self::STATUT_EN_ATTENTE_VERIFICATION
        ]) && !$this->is_locked;
    }

    public function canBeTakenBy($userId)
    {
        return $this->canBeTaken()
            || ($this->assigned_to == $userId
            && in_array($this->statut, [
                self::STATUT_EN_COURS,
                self::STATUT_CORRESPONDANCE_TROUVEE
            ]));
    }

    public function lockForAgent($agentId)
    {
        $this->assigned_to = $agentId;
        $this->assigned_at = now();
        $this->is_locked = true;
        $this->statut = self::STATUT_EN_COURS;
        $this->save();
    }

    public function unlock()
    {
        $this->assigned_to = null;
        $this->assigned_at = null;
        $this->is_locked = false;
        $this->statut = self::STATUT_EN_ATTENTE;
        $this->save();
    }

    // ==========================
    // GENERATION NUMERO DECLARATION
    // ==========================

    public static function generateNumeroDeclaration()
    {
        $year = now()->year;
        $count = self::whereYear('created_at', $year)->count() + 1;
        return sprintf('DECL-%d-%05d', $year, $count);
    }

    // ==========================
    // BOOT
    // ==========================

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

            // Définir le statut de vérification par défaut
            if (empty($perte->statut_verification)) {
                $perte->statut_verification = $perte->hasExpirationDate() 
                    ? self::STATUT_VERIFICATION_AUTO 
                    : self::STATUT_VERIFICATION_MANUELLE;
            }

            // Définir le statut en fonction de la date d'expiration
            if (empty($perte->statut)) {
                $perte->statut = $perte->hasExpirationDate() 
                    ? self::STATUT_EN_ATTENTE 
                    : self::STATUT_EN_ATTENTE_VERIFICATION;
            }
        });

        static::updating(function ($perte) {
            // Si le statut passe à "Restitué", définir la date de restitution
            if ($perte->isDirty('statut') 
                && $perte->statut === self::STATUT_RESTITUE 
                && empty($perte->date_restitution)
            ) {
                $perte->date_restitution = now();
            }

            // Si la date d'expiration est ajoutée/modifiée, ajuster les statuts
            if ($perte->isDirty('date_expiration')) {
                if ($perte->hasExpirationDate()) {
                    // Si date d'expiration ajoutée, passer en vérification auto si en attente vérif
                    if ($perte->statut === self::STATUT_EN_ATTENTE_VERIFICATION) {
                        $perte->statut = self::STATUT_EN_ATTENTE;
                    }
                    $perte->statut_verification = self::STATUT_VERIFICATION_AUTO;
                } else {
                    // Si date d'expiration supprimée, passer en vérification manuelle
                    if ($perte->statut === self::STATUT_EN_ATTENTE) {
                        $perte->statut = self::STATUT_EN_ATTENTE_VERIFICATION;
                    }
                    $perte->statut_verification = self::STATUT_VERIFICATION_MANUELLE;
                }
            }

            // Si le statut de vérification change
            if ($perte->isDirty('statut_verification')) {
                // Si passé en vérification manuelle, ajouter la date de vérification
                if ($perte->statut_verification === self::STATUT_VERIFICATION_MANUELLE 
                    && empty($perte->verified_at)
                ) {
                    $perte->verified_at = now();
                }
            }

            // Mise à jour automatique de is_expired si le champ existe
            if ($perte->isDirty('date_expiration') && $perte->hasExpirationDate()) {
                $perte->is_expired = $perte->isExpired();
            }
        });
    }
}