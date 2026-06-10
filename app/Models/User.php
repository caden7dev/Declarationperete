<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'contact',
        'birth_date',
        'address',
        'nationality',
        'gender',
        'first_name',      // Si utilisé
        'last_name',       // Si utilisé
        'phone',   
         'theme',        // Si utilisé
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birth_date' => 'date',
        'preferences' => 'array',
        // 'password' => 'hashed', // ← ENLEVÉ (seulement Laravel 10+)
    ];

    /**
     * Get all declarations made by this user.
     */
    public function pertes()
    {
        return $this->hasMany(Perte::class);
    }

    /**
     * Get declarations validated by this user (if agent).
     */
    public function validatedPertes()
    {
        return $this->hasMany(Perte::class, 'validated_by');
    }

    /**
     * Check if user is an agent.
     */
    public function isAgent(): bool
    {
        // Implement your agent check logic here
        // Example: return $this->role === 'agent';
        return false;
    }

    /**
     * Get user's full name.
     */
    public function getFullNameAttribute(): string
    {
        if ($this->first_name && $this->last_name) {
            return $this->first_name . ' ' . $this->last_name;
        }
        return $this->name;
    }

    /**
     * Get user's initials for avatar.
     */
    public function getInitialsAttribute(): string
    {
        if ($this->first_name && $this->last_name) {
            return strtoupper(substr($this->first_name, 0, 1) . substr($this->last_name, 0, 1));
        }
        return strtoupper(substr($this->name, 0, 1));
    }

    /**
     * Get user's age from birth date.
     */
    public function getAgeAttribute(): ?int
    {
        if (!$this->birth_date) {
            return null;
        }
        return $this->birth_date->age;
    }
}