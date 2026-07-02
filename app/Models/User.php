<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Constantes de rôle
    public const ROLE_CITOYEN = 'citoyen';
    public const ROLE_USER = 'user';
    public const ROLE_AGENT = 'agent';
    public const ROLE_ADMIN = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'contact',
        'birth_date',
        'address',
        'nationality',
        'gender',
        'first_name',
        'last_name',
        'phone',
        'theme',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'birth_date' => 'date',
        'preferences' => 'array',
    ];

    // Relations
    public function pertes()
    {
        return $this->hasMany(Perte::class);
    }

    public function validatedPertes()
    {
        return $this->hasMany(Perte::class, 'validated_by');
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    public function sentNotifications()
    {
        return $this->hasMany(Notification::class, 'from_user_id');
    }

    public function receivedNotifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    // Vérifications de rôle
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function isCitizen(): bool
    {
        return in_array($this->role, [self::ROLE_CITOYEN, self::ROLE_USER]);
    }

    public function isAgent(): bool
    {
        return $this->role === self::ROLE_AGENT;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    // Accesseur pour le libellé du rôle (compatible PHP 7.x)
    public function getRoleLabelAttribute(): string
    {
        $labels = [
            self::ROLE_CITOYEN => 'Citoyen',
            self::ROLE_USER    => 'Utilisateur',
            self::ROLE_AGENT   => 'Agent',
            self::ROLE_ADMIN   => 'Administrateur',
        ];
        return $labels[$this->role] ?? 'Inconnu';
    }

    // Autres accesseurs
    public function getFullNameAttribute(): string
    {
        if ($this->first_name && $this->last_name) {
            return $this->first_name . ' ' . $this->last_name;
        }
        return $this->name;
    }

    public function getInitialsAttribute(): string
    {
        if ($this->first_name && $this->last_name) {
            return strtoupper(substr($this->first_name, 0, 1) . substr($this->last_name, 0, 1));
        }
        return strtoupper(substr($this->name, 0, 1));
    }

    public function getAgeAttribute(): ?int
    {
        if (!$this->birth_date) {
            return null;
        }
        return $this->birth_date->age;
    }
}