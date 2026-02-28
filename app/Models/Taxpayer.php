<?php

namespace App\Models;

use App\Enums\InvoicePayStatusEnums;
use App\Enums\InvoiceStatusEnums;
use App\Enums\TaxpayerStateEnums;
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
        'category_id',
        'from_mobile_and_validate_state',
        'deleted_at',
        'created_by',
        'updated_by',
        'type'
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
    protected static function boot()
    {
        parent::boot();
        $auth_id = auth()->id();
        if ($auth_id) {
            static::creating(function ($model) use ($auth_id) {
                $model->created_by = $auth_id;
            });
            static::updating(function ($model) use ($auth_id) {
                $model->updated_by = $auth_id;
            });
        }
    }
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
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public static function getInvoiceAndPayments($id): array
    {
        $result = [];
        $items = [];
        $taxpayer = Taxpayer::find($id);
        if ($taxpayer instanceof Taxpayer) {
            $result[] = $taxpayer;
            foreach ($taxpayer->invoices()->get() as $invoice) {
                $items[] = $invoice;
                foreach ($invoice->payments()->get() as $payment) {
                    $items[] = $payment;
                }
            }
            $result[] = $items;
        }
        return $result;
    }
    public function getStatus(): bool
    {
        return Invoice::where('taxpayer_id', '=', $this->id)
            ->where('to_date', '>', now())
            ->whereNotIn('status', [
                InvoiceStatusEnums::REJECTED_BY_OR,
                InvoiceStatusEnums::REJECTED,
                InvoiceStatusEnums::CANCELED,
                InvoiceStatusEnums::REDUCED
            ])
            ->where('pay_status', '!=', InvoicePayStatusEnums::PAID)
            ->where('validity', 'VALID')
            ->exists();
    }

    /**
     * Search for a given value in multiple columns.
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


    public static function merge($names = ['BRASSERIE BB LOME', 'SOCIETE NOUVELLE DE BOISSON', 'MOOV AFRICA', 'YAS TOGO'])
    {
        foreach ($names as $name) {
            $normalizedName = strtolower(str_replace(' ', '', $name));

            $taxpayers = Taxpayer::whereRaw("LOWER(REPLACE(name, ' ', '')) LIKE ?", ["%{$normalizedName}(equipe%)%"])
                ->orderBy('id')
                ->get();
            if ($taxpayers->count() < 2) {
                continue;
            }
            $firstTaxpayer = $taxpayers->first();
            foreach ($taxpayers->skip(1) as $taxpayer) {
                TaxpayerTaxable::where('taxpayer_id', $taxpayer->id)
                    ->update(['taxpayer_id' => $firstTaxpayer->id]);
                $taxpayer->delete();
            }
        }
    }
    public static function getTaxpayers()
    {
        return Taxpayer::where('type', '=', Constants::TITRE)->where(function ($q) {
            $q->whereNotIn('taxpayers.from_mobile_and_validate_state', [TaxpayerStateEnums::REJECTED, TaxpayerStateEnums::PENDING])->orWhereNull('taxpayers.from_mobile_and_validate_state');
        })->get();
    }
    public static function taxpayersWithoutInvoice()
    {
        return Taxpayer::getTaxpayers()->filter(fn($taxpayer) => !Invoice::where('taxpayer_id', $taxpayer->id)->where('to_date', '>', now())->whereNotIn('status', [InvoiceStatusEnums::REJECTED_BY_OR, InvoiceStatusEnums::REJECTED, InvoiceStatusEnums::CANCELED, InvoiceStatusEnums::REDUCED])
            ->where('pay_status', '!=', InvoicePayStatusEnums::PAID)
            ->where('validity', 'VALID')
            ->exists());
    }
    public static function taxpayersWithMultipleInvoice($start_date, $end_date)
    {
        return Taxpayer::getTaxpayers()->filter(fn($taxpayer) => $taxpayer->invoices->filter(fn($invoice) => $invoice->created_at->between($start_date, $end_date) && $invoice->isValid())->count() > 1);
    }
}
