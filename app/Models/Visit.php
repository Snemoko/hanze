<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_id',
        'report',
        'appointment_date',
        'appointment_time',
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo{
        return $this->belongsTo(Customer::class);
    }

    /**
     * returns visits based on given ID
     *
     * @param integer $id
     */
    public function scopeWhereId($query, $id){
        return $query
            ->where("id", $id);
    }
}
