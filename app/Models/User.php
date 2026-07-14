<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles; // <--- Esto hace toda la magia

class User extends Authenticatable
{
    // HasRoles agrega las relaciones roles(), permissions(), etc.
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        // 'role_id', <--- BORRADO (Ya no existe en la BD)
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        // 'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        //'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // --- RELACIONES ---

    // ¡¡BORRADA la función role()!! 
    // Ahora para ver el rol usarás: $user->roles o $user->getRoleNames()

    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'user_id');
    }
    
    public function secretary()
    {
        return $this->hasOne(Secretary::class, 'user_id');
    }
}