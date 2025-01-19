<?php
namespace App\Models;
use App\Contracts\FormatDateInterface;
use App\Enums\InvoicePayStatusEnums;
use App\Enums\InvoiceStatusEnums;
use App\Helpers\Constants;
use App\Traits\InvoiceTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Spatie\Permission\Models\Role;
use ZeroDaHero\LaravelWorkflow\Traits\WorkflowTrait;
class Invoice extends Model implements FormatDateInterface
{
    use HasFactory;
    use WorkflowTrait;
    use InvoiceTrait;
    protected $fillable = [
        'invoice_id',
        'taxpayer_id',
        'invoice_no',
        'order_no',
        'amount',
        'reduce_amount',
        'qty',
        'from_date',
        'to_date',
        'pay_status',
        'status',
        'uuid',
        'delivery_date',
        'delivery_to',
        'type',
        'delivery',
        'edition_state',
        'notes'
    ];
    protected $attributes = [
        'status' => InvoiceStatusEnums::DRAFT,
    ];
    public function can(string $state)
    {
        $workflow = $this->workflow_get();
        return $workflow->can($this, $state);
    }
    public function submitToState(string $state)
    {
        $workflow = $this->workflow_get();
        if ($this->can($state)) {
            $workflow->apply($this, $state);
            $this->save();
            return true;
        }
        return false;
    }
    public function get_remains_to_be_paid()
    {
        if ($this->status == InvoiceStatusEnums::REDUCED || $this->status == InvoiceStatusEnums::CANCELED || $this->status == InvoiceStatusEnums::REJECTED) {
            return "-";
        } elseif ($this->status == InvoiceStatusEnums::APPROVED_CANCELLATION) {
            $invoice = Invoice::where('invoice_no', $this->invoice_no)->first();
            return Payment::getRestToPaid($invoice);
        } else {
            return Payment::getRestToPaid($this);
        }
    }
    public function canGetPayment(): bool
    {
        return ($this->status != InvoiceStatusEnums::CANCELED &&
                $this->status != InvoiceStatusEnums::REDUCED &&
                $this->pay_status != InvoicePayStatusEnums::PAID)
            && ($this->delivery_date != null || $this->type == Constants::INVOICE_TYPE_COMPTANT);
    }
    public function is_editions_is_generate_for_approve_state(): bool
    {
        return (bool) $this->edition_state;
    }
    public function canPrint(): bool
    {
        return true;
    }
    public function getAvailableTransitions(): array
    {
        $workflow = $this->workflow_get();
        $enabledTransitions = $workflow->getEnabledTransitions($this);
        $transitions = [];
        foreach ($enabledTransitions as $transition) {
            $transition[] = $transition->getName();
        }
        return $transitions;
    }
    public function printFiles()
    {
        return $this->belongsToMany(PrintFile::class);
    }
    public function taxpayer()
    {
        return $this->belongsTo(Taxpayer::class);
    }
    public function taxpayer_taxables()
    {
        return $this->hasMany(TaxpayerTaxable::class);
    }
    public function getDefaulttaxpayer_taxableAttribute()
    {
        return $this->taxpayer_taxables()->first();
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    public function invoiceitems()
    {
        return $this->hasMany(InvoiceItem::class);
    }
    public static function boot()
    {
        parent::boot();
        static::creating(function ($invoice) {
            $invoice->uuid = Uuid::uuid4()->toString();
        });
    }
    public function getCreatedDate(): string
    {
        return $this->created_at->format('Y-m-d');
    }
    public function setDeliveryToNow(string $status)
    {
        $this->status = $status;
        $this->delivery = "DELIVERED";
        $this->delivery_date = now();
    }
    public function processOnInvoicesByUser(string $roleName): Invoice
    {
        $role = Role::where('name', "=",$roleName)->first();
        if ($role) {
            $user = auth()->user();
            if ($user->hasRole($roleName)) {
                $this->setDeliveryToNow(InvoiceStatusEnums::APPROVED);
            }
        }
        return $this;
    }
    public static function saveNotes(int $previousInvoiceId=null, string $remainingAmount=null, $freeText=null)
    {
       return json_encode([
            'previous_invoice_id' => $previousInvoiceId,
            'remaining_amount' => $remainingAmount,
            'free_text' => $freeText,
        ]);
    }
    /**
     * Vérifie si le champ `notes` suit la structure JSON attendue.
     *
     * @return bool
     */
    public function hasValidNotesStructure()
    {
        $notesData = json_decode($this->notes, true);

        if (is_array($notesData)) {
            return array_key_exists('previous_invoice_id', $notesData)
                && array_key_exists('remaining_amount', $notesData)
                && array_key_exists('free_text', $notesData);
        }

        return false;
    }
    public  function migrateNotes()
    {

        if (!$this->hasValidNotesStructure()) {
            $structuredNotes = [
                'previous_invoice_id' => null,
                'remaining_amount' => null,
                'free_text' => $this->notes,
            ];
            $this->notes = json_encode($structuredNotes);
            $this->save();
        }
    }

    public function getNotes()
    {
        if($this->hasValidNotesStructure()) {
            return json_decode($this->notes, true) ;
        }else{
            return $this->notes;
        }
    }
}
