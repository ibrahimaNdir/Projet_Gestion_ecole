<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Couchbase\Role;
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
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'nom_utilisateur',
        'email',
        'mot_de_passe',
        'role_id',
    ];

    protected $hidden = [
        'mot_de_passe',
        'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
