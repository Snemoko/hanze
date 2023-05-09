<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

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

    protected $dates = ['appointment_date'];

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

    /**
     *
     * Returns visits that are in the future
     */
    public function scopeWhereInFuture($query){
        return $query
            ->where("appointment_date", ">", Carbon::now()->format("Y-m-d"));
    }
}
