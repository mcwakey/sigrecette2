<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Commune extends Model
{
    protected $fillable = [
        'name',
        'title',
        'region_name',
        'mayor_name',
        'phone_number',
        'address',
        'treasury_name',
        'treasury_address',
        'treasury_rib',
        'longitude',
        'latitude',
        'limit_json',
        'logo_path',
        'email',
        'url'
    ];

    //TODO Site web,et addresse email

    /**
     * Get the first commune.
     *
     * @return Commune|null
     */
    public static function getFirstCommune(): ?Commune
    {
        return Commune::orderBy('id')->first();
    }
    /**
     * Get the full URL of the image.
     *
     * @return string
     */
    public function getImageUrlAttributeDirect()
    {

        if ($this->logo_path) {
            return asset( 'storage/' . $this->logo_path);
        }
        return $this->logo_path;
    }
    /**
     * Get the full URL of the image.
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {

        if ($this->logo_path) {
            return 'storage/' . $this->logo_path;
        }
        return $this->logo_path;
    }

}
