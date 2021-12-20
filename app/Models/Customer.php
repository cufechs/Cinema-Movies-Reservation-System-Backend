<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Customer extends Model
{
    protected $fillable = [
        
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // public function customerReservations()
    // {
    //     return $this->belongsToMany(MovieReservation::class, 'Cust_Reservation');
    // }
}
