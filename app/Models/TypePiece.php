<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypePiece extends Model
{
    use HasFactory;

    /**
     * Nom de la table
     */
    protected $table = 'types_pieces';

    /**
     * Les attributs qui peuvent être assignés en masse
     */
    protected $fillable = [
        'nom',
        'code',
        'categorie',
        'description',
        'is_active',
        'delai_traitement',
        'prix',
        'documents_requis',
    ];

    /**
     * Les attributs qui doivent être castés
     */
    protected $casts = [
        'is_active' => 'boolean',
        'prix' => 'decimal:2',
        'delai_traitement' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relation avec les pertes/déclarations
     * Une pièce peut avoir plusieurs déclarations de perte
     */
    public function pertes()
    {
        return $this->hasMany(Perte::class, 'type_piece_id');
    }

    /**
     * Scope pour récupérer uniquement les types actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour filtrer par catégorie
     */
    public function scopeByCategorie($query, $categorie)
    {
        return $query->where('categorie', $categorie);
    }

    /**
     * Accesseur pour formater le prix
     */
    public function getPrixFormateAttribute()
    {
        if ($this->prix) {
            return number_format($this->prix, 0, ',', ' ') . ' FCFA';
        }
        return 'Gratuit';
    }

    /**
     * Accesseur pour obtenir le statut en texte
     */
    public function getStatutTextAttribute()
    {
        return $this->is_active ? 'Actif' : 'Inactif';
    }
}