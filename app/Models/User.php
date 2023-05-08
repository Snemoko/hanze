<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'role',
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
    ];


    public function visits(): HasMany{
        return $this->hasMany(Visit::class);
    }


    /**
     * returns users based on given ID
     *
     * @param integer $id
     */
    public function scopeWhereId($query, $id){
        return $query
            ->where("id", $id);
    }

    /**
     * Returns all managers
     */
    public function scopeWhereManager($query){
        return $query
            ->where("role", 1);
    }

    /**
     * Returns all representatives
     */
    public function scopeWhereRepresentative($query){
        return $query
            ->where("role", 0);
    }
}
