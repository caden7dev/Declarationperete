<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perte extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pertes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'last_name',
        'first_name',
        'contact',
        'email',
         'type_piece',  
        'type_piece_id',
        'numero_piece',
        'date_delivrance',
        'autorite_delivrance',
        'date_perte',
        'lieu_perte',
        'circonstances',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
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
     * Get the user that owns the declaration.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the type piece associated with the declaration.
     */
    public function typePiece()
    {
        return $this->belongsTo(TypePiece::class, 'type_piece_id');
    }

    /**
     * Get the agent who validated/rejected the declaration.
     */
    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    /**
     * Scope a query to only include pending declarations.
     */
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    /**
     * Scope a query to only include validated declarations.
     */
    public function scopeValidees($query)
    {
        return $query->where('statut', 'validee');
    }

    /**
     * Scope a query to only include rejected declarations.
     */
    public function scopeRejetees($query)
    {
        return $query->where('statut', 'rejetee');
    }

    /**
     * Check if the declaration is pending.
     */
    public function isEnAttente(): bool
    {
        return $this->statut === 'en_attente';
    }

    /**
     * Check if the declaration is validated.
     */
    public function isValidee(): bool
    {
        return $this->statut === 'validee';
    }

    /**
     * Check if the declaration is rejected.
     */
    public function isRejetee(): bool
    {
        return $this->statut === 'rejetee';
    }

    /**
     * Get the full name of the declarant.
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Generate a unique declaration number.
     */
    public function generateNumeroDeclaration()
    {
        $year = now()->year;
        $count = self::whereYear('created_at', $year)
            ->whereNotNull('numero_declaration')
            ->count() + 1;
            
        return 'DECL-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Generate declaration number when created
        static::creating(function ($perte) {
            if (empty($perte->numero_declaration)) {
                $perte->numero_declaration = $perte->generateNumeroDeclaration();
            }
        });
    }
}