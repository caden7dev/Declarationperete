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
        'perte_matchee_id',
        'date_restitution',
        'numero_declaration',
        'notes_admin',
    ];

    protected $casts = [
        'date_decouverte' => 'date',
        'date_restitution' => 'datetime',
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
     * Relation avec la perte matchée
     */
    public function perte()
    {
        return $this->belongsTo(Perte::class, 'perte_matchee_id');
    }

    // Scopes
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeMatches($query)
    {
        return $query->where('statut', 'matche');
    }

    public function scopeRestitues($query)
    {
        return $query->where('statut', 'restitue');
    }

    /**
     * Chercher des correspondances avec des pertes
     */
    public function chercherCorrespondances()
    {
        return Perte::where('statut', 'en_attente')
            ->where(function($q) {
                $q->where('numero_piece', $this->numero_document)
                  ->orWhere('type_piece', $this->type_document)
                  ->orWhere('last_name', $this->nom_sur_document)
                  ->orWhere('first_name', $this->prenom_sur_document);
            })
            ->get();
    }

    /**
     * Matcher avec une perte
     */
    public function matcherAvecPerte($perteId)
    {
        $this->update([
            'perte_matchee_id' => $perteId,
            'statut' => 'matche'
        ]);

        $perte = Perte::find($perteId);
        if ($perte) {
            $perte->update([
                'statut' => 'trouve',
                'document_retrouve' => true,
                'date_retrouvaille' => now(),
                'document_trouve_id' => $this->id
            ]);
        }
    }
    public function perteMatchee()
{
    return $this->belongsTo(Perte::class, 'perte_matchee_id');
}

    /**
     * Marquer comme restitué
     */
    public function marquerRestitue()
    {
        $this->update([
            'statut' => 'restitue',
            'date_restitution' => now()
        ]);
    }
}