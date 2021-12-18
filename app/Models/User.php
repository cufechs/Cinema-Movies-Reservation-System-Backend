<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'username', 'role', 'mobile_number'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    // public function customer(): \Illuminate\Database\Eloquent\Relations\HasOne
    // {
    //     return $this->hasOne(Customer::class);
    // }

    // public function manager(): \Illuminate\Database\Eloquent\Relations\HasOne
    // {
    //     return $this->hasOne(Manager::class);
    // }

    // public function admin(): \Illuminate\Database\Eloquent\Relations\HasOne
    // {
    //     return $this->hasOne(Admin::class);
    // }

    // public function customerReservations(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    // {
    //     return $this->belongsToMany(CustomerReservation::class);
    // }
}
