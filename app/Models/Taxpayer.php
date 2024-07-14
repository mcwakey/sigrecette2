<?php

namespace App\Models;

use App\Enums\TaxpayerStaticsEnums;
use App\Helpers\Constants;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Taxpayer extends Model
{
    use HasFactory;
    use SoftDeletes;


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

        'file_no',
        'category_work',
        'work',
        'other_work',
        'authorisation',
        'auth_reference',
        'nif',
        'social_work',

        'town_id',
        'erea_id',
        'zone_id',
        'email',
        'password',
        'last_login_at',
        'last_login_ip',
        'profile_photo_path',
        'activity_id',
        'category_id'
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

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getDefaultAddressAttribute()
    {
        return $this->addresses?->first();
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public static function getInvoiceAndPayments($id):array
    {
        $result = [];
        $items= [];
        $taxpayer = Taxpayer::find($id);
        if($taxpayer instanceof  Taxpayer){
            $result[] = $taxpayer;
            foreach ($taxpayer->invoices()->get() as $invoice) {
                $items[] = $invoice;
                foreach ($invoice->payments()->get() as $payment){
                    $items[] = $payment;
                }
            }
            $result[] = $items;
        }
        //dd($result[1]);
        //$compareByDate = function ($a, $b) {$dateA = $a instanceof Invoice ? ($a->delivery_date ?? $a->created_at) : $a->created_at;$dateB = $b instanceof Invoice ? ($b->delivery_date ?? $b->created_at) : $b->created_at;return strcmp($dateA, $dateB);};usort($result[1], $compareByDate);


        return $result;
    }

    /**
     * Search for a given value in multiple columns.
     *
     * @param string $value
     * @return QueryBuilder
     */
    public static function search(string $value): QueryBuilder
    {

        $columns = [
            'id',
            'tnif',
            'name',
            'id_number',
            'mobilephone',
            'telephone',
            'address',
            'authorisation',
            'auth_reference',
            'nif',
            'email'
        ];

        $query = self::query();

        foreach ($columns as $column) {
            $query->orWhere($column, 'like', "%{$value}%");
        }

        return $query;
    }
}
