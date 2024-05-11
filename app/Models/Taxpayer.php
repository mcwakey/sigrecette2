<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function Symfony\Component\Translation\t;

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

    public static function countTaxpayers(){
        return Taxpayer::selectRaw('gender, count(*) as count')
            ->groupBy('gender')
            ->pluck('count', 'gender')
            ->merge(['Total' =>Taxpayer::count()])
            ->toArray();
    }

    public static function countTaxpayersByActivity()
    {
        $categories = Category::all()->pluck('name', 'id');

        $counts = Taxpayer::selectRaw('category_id, count(*) as count')
            ->groupBy('category_id')
            ->get()
            ->map(function ($item) use ($categories) {
                $categoryName = $categories[$item->category_id] ?? 'Unknown category';
                return ['value' => $item->count, 'category' => $categoryName];
            })
            ->toArray();

        return $counts;
    }

    public static function countTaxpayersByCanton()
    {
        $cantons = Canton::all()->pluck('name', 'id');
        $counts = Taxpayer::selectRaw('town_id, count(*) as count')
            ->groupBy('town_id')
            ->get()
            ->map(function ($item) use ($cantons) {
                $categoryName = $item->town ? $cantons[$item->town->canton_id] ?? 'Unknown canton' : 'Unknown town';
                return ['value' => $item->count, 'category' => $categoryName];
            })
            ->unique(function ($item) {
                return $item['category'];
            })
            ->toArray();

        return array_values($counts);
    }

    public static function countTaxpayersState()
    {
        $count_valid = 0;
        $count_no_valid = 0;
        $taxpayers_without_invoices=0;

        $taxpayers = Taxpayer::all();
        foreach ($taxpayers as $taxpayer) {
            if ($taxpayer->invoices->isNotEmpty()) {
                $is_valid = true;
                foreach ($taxpayer->invoices as $invoice) {
                    if ($invoice->pay_status == 'OWING'|| $invoice->pay_status == 'PART PAID') {
                        $is_valid = false;
                        break;
                    }
                }
                if ($is_valid) {
                    $count_valid++;
                }else {
                    $count_no_valid++;
                }
            }else{
                $taxpayers_without_invoices+=1;
            }
        }

        // Retourner les compteurs
        return [
            ['value' => $count_valid, 'category' => "A jour"],
            ['value' => $count_no_valid, 'category' => "Non à jour"],
            ['value' => $taxpayers_without_invoices, 'category' => "Sans avis"],
        ];
    }


    public static function countTaxpayersByTaxables()
    {
        $taxables = Taxable::all()->pluck('name', 'id');
        $counts = TaxpayerTaxable::selectRaw('taxable_id, count(*) as count')
            ->groupBy('taxable_id')
            ->get()
            ->map(function ($item) use ($taxables) {
                $categoryName = $taxables[$item->taxable_id];
                return ['value' => $item->count, 'category' => $categoryName];
            })
            ->unique(function ($item) {
                return $item['category'];
            })
            ->toArray();


        return array_values($counts);
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


}
