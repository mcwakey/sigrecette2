<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taxpayer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tnif',
        'name',
        'gender',
        'id_type',
        'id_number',
        'mobilephone',
        'telephone',
        'longitude',
        'latitude',
        'address',
        //'canton',
        'town_id',
        'erea_id',
        'zone_id',
        'email',
        'password',
        'last_login_at',
        'last_login_ip',
        'profile_photo_path',
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
        'last_login_at' => 'datetime',
    ];

    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo_path) {
            return asset('storage/' . $this->profile_photo_path);
        }

        return $this->profile_photo_path;
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function town()
    {
        return $this->belongsTo(Town::class);
    }

    public function erea()
    {
        return $this->belongsTo(Erea::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function taxpayer_taxables()
    {
        return $this->hasMany(TaxpayerTaxable::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function getDefaultAddressAttribute()
    {
        return $this->addresses?->first();
    }
}
