<?php

namespace App\Livewire\Taxable;


use App\Models\Taxable;
use App\Models\TaxLabel;
use App\Traits\DispatchesMessages;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;


class AddTaxableModal extends Component
{
    use WithFileUploads;
    use DispatchesMessages;

    public $taxable_id;
    public $name;
    public $tariff;
    public $tariff_type;
    public $unit;
    public $unit_type;
    public $modality;
    public $periodicity;
    public $penalty;
    public $penalty_type;
    public $tax_label_id;
    public $use_second_formula=false;


    public $edit_mode = false;

    protected function rules(){
        $rules = [
            'name' => 'required|string|unique:taxables,name,' . ($this->edit_mode ?  $this->taxable_id : ''),
            'tariff' => 'required|numeric',
            'tariff_type' => 'required|string',
            'unit' => 'required|string',
            'unit_type' => 'required|string',
            'periodicity' => 'required|string',
            'tax_label_id' => 'required|int',
        ];

        if ($this->edit_mode) {
            // En mode d'édition, nous ignorons l'ID actuel pour la validation d'unicité
            $rules['name'] = 'required|string|unique:taxables,name,' . $this->taxable_id;
        }

        return $rules;
    }


    protected $listeners = [
        'delete_user' => 'deleteUser',
        'update_user' => 'updateUser',
        'close_taxable_modal' => 'closeTaxableModal',
    ];

    public function render()
    {
        $tax_labels = TaxLabel::all();
        return view('livewire.taxable.add-taxable-modal', compact('tax_labels'));
    }
    public function mount()

    {
        if($this->edit_mode){
            $taxable = Taxable::find($this->taxable_id);

            if($taxable){
                $this->use_second_formula =(bool)$taxable->use_second_formula;
            }
        }
    }
    public function submit()
    {
        // Validate the form input data
        $this->validate();
        DB::transaction(function () {
            $data = [
                'name' => $this->name,
                'tariff' => $this->tariff,
                'tariff_type' => $this->tariff_type,
                'unit' => $this->unit,
                'unit_type' => $this->unit_type,
                'modality' => $this->modality,
                'periodicity' => $this->periodicity,
                'penalty' => $this->penalty,
                'penalty_type' => $this->penalty_type,
                'tax_label_id' => $this->tax_label_id,
                'use_second_formula'=>$this->use_second_formula
            ];

            $taxable = Taxable::find($this->taxable_id) ?? Taxable::create($data);

            if ($this->edit_mode) {
                foreach ($data as $k => $v) {
                    $taxable->$k = $v;
                }
                $taxable->save();
            }


            if ($this->edit_mode) {

                $this->dispatchMessage('Matière Taxable', 'update');
            } else {
                $this->dispatchMessage('Matière Taxable');
            }
        });

        // Reset the form fields after successful submission
        $this->reset();
    }

    public function deleteUser($id)
    {
        Taxable::destroy($id);
        $this->dispatchMessage('Matière Taxable', 'delete');
    }

    public function updateUser($id)
    {
        $this->edit_mode = true;

        $taxable = Taxable::find($id);

        $this->taxable_id = $taxable->id;
        $this->use_second_formula = $taxable->use_second_formula;
        $this->tax_label_id = $taxable->tax_label_id;
        $this->name = $taxable->name;
        $this->tariff = $taxable->tariff;
        $this->tariff_type = $taxable->tariff_type;
        $this->unit = $taxable->unit;
        $this->unit_type = $taxable->unit_type;
        $this->modality = $taxable->modality;
        $this->periodicity = $taxable->periodicity;
        $this->penalty = $taxable->penalty;
        $this->penalty_type = $taxable->penalty_type;

    }
    public function rendering($view, $data)
    {
        //
    }

    public function closeTaxableModal()
    {
        $this->edit_mode = false;
        $this->taxable_id='';
        $this->name='';
        $this->tariff='';
        $this->tariff_type='';
        $this->unit='';
        $this->unit_type='';
        $this->modality='';
        $this->periodicity='';
        $this->penalty='';
        $this->penalty_type='';
        $this->tax_label_id='';
        $this->use_second_formula=false;
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
