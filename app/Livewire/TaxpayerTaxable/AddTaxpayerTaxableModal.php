<?php
namespace App\Livewire\TaxpayerTaxable;
use App\Models\Canton;
use App\Models\Erea;
use App\Models\Gender;
use App\Models\IdType;
use App\Models\Taxable;
use App\Models\TaxLabel;
use App\Models\Taxpayer;
use App\Models\TaxpayerTaxable;
use App\Models\Town;
use App\Traits\DispatchesMessages;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use mysql_xdevapi\CollectionRemove;
class AddTaxpayerTaxableModal extends Component
{
    use WithFileUploads;
    use DispatchesMessages;
    public $taxpayer_taxable_id;
    public $description;
    public $seize;
    public $location;
    public $longitude;
    public $latitude;
    public $taxable_id;
    public $authorisation;
    public $auth_reference;
    public $unit;
    public $length;
    public $width;
    public $taxlabel_id;
    public $taxables = [];
    public $edit_mode = false;
    public $option_calculus;
    protected $rules = [
        'description' => 'required|string',
        'seize' => 'required|numeric|gt:0',
        //'location' => 'required',
        'taxable_id' => 'required|int',
        // 'taxpayer_id' => 'required',
        'width' => 'nullable|numeric|min:0',
        'length' => 'nullable|numeric|min:0',
        // 'penalty' => 'nullable',
        // 'penalty_type' => 'nullable',
        //'tax_label' => 'required',
        // 'tax_label_id' => 'required',
        //'longitude' => 'required',
        //'latitude' => 'required',
        // 'canton' => 'required',
        // 'town' => 'required',
        // 'erea' => 'required',
        // 'address' => 'required|string',
        // 'zone_id' => 'required',
        // 'avatar' => 'nullable|sometimes|image|max:1024',
    ];
    protected $listeners = [
        'delete_taxpayer_taxable' => 'deleteTaxpayerTaxable',
        'update_taxable' => 'updateTaxpayerTaxable',
        'add_taxpayer_taxable' => 'addTaxpayerTaxable',
        'update_checkbox' => 'updateCheckbox',
        'load_drop' => 'load_drop',
        //'loadTaxable' => 'loadTaxable',
    ];
    public $taxpayer_id;
    public function mount($id)
    {
        $this->taxpayer_id = $id;
    }
    public function render()
    {
        $taxlabels = TaxLabel::where('category', 'LIKE', '%CATEGORY 1%')->where('status', '=', 'ACTIVE')->get();
        return view('livewire.taxpayer_taxable.add-taxpayer-taxable-modal', ['taxlabels' => $taxlabels]);
    }
    public function updatedTaxlabelId($value)
    {
        $this->taxables = Taxable::
        where('tax_label_id', $value)
            ->where('status', '=', 'ACTIVE')
            ->get(); // Load taxables based on tax label ID
    }
    public function updatedTaxableId($value)
    {
        $taxables = Taxable::find($value);
        $this->option_calculus = $taxables?->unit_type;
        $this->unit = $taxables?->unit;
    }
    public function updatedLength($value)
    {
        if (is_numeric($value) && $value > 0) {
            $this->makeCalculSeize();
        }
    }
    public function updatedWidth($value)
    {
        if (is_numeric($value) && $value > 0) {
            $this->makeCalculSeize();
        }
    }
    public function makeCalculSeize()
    {
        if ($this->length > 0 && $this->width > 0) {
            $this->width = floatval($this->width);
            $this->length = floatval($this->length);
            $this->seize = round($this->length * $this->width, 2);
        }
    }
    public function updateCheckbox($id)
    {
        $taxpayer_taxables = TaxpayerTaxable::findOrFail($id);
        if ($taxpayer_taxables->billable == 0) {
            $taxpayer_taxables->update([
                'billable' => '1'
            ]);
        } else {
            $taxpayer_taxables->update([
                'billable' => '0'
            ]);
        }
    }
    public function submit()
    {
        if (!auth()->user()->hasPermissionTo('peut créer une taxation')) {
            abort(403, 'Accès interdit');
        }
        $this->validate();
        DB::transaction(function () {
            // Prepare the data for creating a new Taxable
            $data = [
                'name' => $this->description,
                'length' => $this->length,
                'width' => $this->width,
                'seize' => $this->seize,
                'location' => $this->location,
                'taxpayer_id' => $this->taxpayer_id,
                'taxable_id' => $this->taxable_id,
                'authorisation' => $this->authorisation,
                'auth_reference' => $this->auth_reference,
                'longitude' => $this->longitude,
                'latitude' => $this->latitude,
            ];
            $taxpayer_taxable = TaxpayerTaxable::find($this->taxpayer_taxable_id) ?? TaxpayerTaxable::create($data);
            if ($this->edit_mode) {
                foreach ($data as $k => $v) {
                    $taxpayer_taxable->$k = $v;
                }
                $taxpayer_taxable->save();
            }
            if ($this->edit_mode) {
                $this->dispatchMessage('Taxation du contribuable', 'update');
            } else {
                $this->dispatchMessage('Taxation du contribuable');
            }
            $this->dispatch('updatesTaxpayerTaxables', ['id' => $this->taxpayer_id]);
        });
        // Reset the form fields after successful submission
        $this->reset();
    }
    public function deleteTaxpayerTaxable($id)
    {
        try {
            TaxpayerTaxable::destroy($id);
            $this->dispatchMessage('Taxation du contribuable', 'delete');
        }catch (\Exception $exception){
            $this->dispatchMessage('Taxation du contribuable', 'delete','error','erreur lors de la supression de la taxation du contribuable.');
        }

    }
    public function updateTaxpayerTaxable($id)
    {
        $this->edit_mode = true;
        $taxpayer_taxable = TaxpayerTaxable::find($id);
        $this->taxpayer_taxable_id = $taxpayer_taxable->id;
        $this->description = $taxpayer_taxable->name;
        $this->length = $taxpayer_taxable->length;
        $this->width = $taxpayer_taxable->width;
        $this->seize = $taxpayer_taxable->seize;
        $this->location = $taxpayer_taxable->location;
        $this->longitude = $taxpayer_taxable->longitude;
        $this->latitude = $taxpayer_taxable->latitude;
        $this->taxpayer_id = $taxpayer_taxable->taxpayer_id;
        $this->taxlabel_id = $taxpayer_taxable->taxable->tax_label_id;
        $this->taxables = Taxable::where('tax_label_id', $taxpayer_taxable->taxable->tax_label_id)->get();
        $this->taxable_id = $taxpayer_taxable->taxable_id;
        $this->authorisation = $taxpayer_taxable->authorisation;
        $this->auth_reference = $taxpayer_taxable->auth_reference;
        $this->option_calculus = $taxpayer_taxable->taxable->unit_type;
    }
    public function addTaxpayerTaxable($id)
    {
        $taxpayer = Taxpayer::find($id);
        $this->taxpayer_id = $taxpayer->id;
    }
    #[On('updateSharedTaxpayerId')]
    public function updateSharedTaxpayerId($id)
    {
        $taxpayer = Taxpayer::find($id);
        if ($taxpayer instanceof Taxpayer) {
            $this->taxpayer_id = $taxpayer->id;
        }
    }
    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
