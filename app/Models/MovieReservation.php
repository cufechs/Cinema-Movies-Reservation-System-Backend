<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovieReservation extends Model
{
    protected $table = 'moviereservations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date', 'start_time', 'end_time', 'capacity', 'movie_id', 'vacant_reserved_seats', 'price'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];
}
