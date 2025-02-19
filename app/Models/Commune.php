<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Commune extends Model
{
    use HasFactory;
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
        'sign_path',
        'email',
        'url',
        'qr_code_enabled',
        'carry_forward_previous_year',
    ];
    /**
     * Get the first commune.
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
    public function getImageUrlAttributeDirect(string $type='logo')
    {
        if($type == 'logo'){
            if ($this->logo_path) {
                return asset("storage/" . $this->logo_path);
            }
        }else{
            if ($this->sign_path) {
                return asset("storage/" . $this->sign_path);
            }
        }
        return null;
    }
    /**
     * Get the full URL of the image.
     *
     * @return string
     */
    public function getImageUrlAttribute(string $type='logo')
    {
        if($type == 'logo'){
            if ($this->logo_path) {
                return 'storage/' . $this->logo_path;
            }
            return $this->logo_path;
        }else{
            if ($this->sign_path) {
                return 'storage/' . $this->sign_path;
            }
            return $this->sign_path;
        }

    }


}
