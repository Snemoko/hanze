<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'city',
        'address',
        'email_address',
        'phone_number',
    ];

    public function visits(): HasMany{
        return $this->hasMany(Visit::class);
    }

    /**
     * returns customers based on given ID
     *
     * @param integer $id
     */
    public function scopeWhereId($query, $id){
        return $query
            ->where("id", $id);
    }
}
