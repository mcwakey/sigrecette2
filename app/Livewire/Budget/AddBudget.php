<?php

namespace App\Livewire\Budget;

use App\Traits\DispatchesMessages;
use Livewire\Component;
use App\Models\Year;
use App\Models\TaxLabel;
use App\Models\Budget;
use Illuminate\Support\Facades\DB;

class AddBudget extends Component
{
    use DispatchesMessages;

    public $yearId;
    public $budgets = [];
    public $availableTaxLabels;
    public $edit_mode = false;
    public $current_budget=[];
    protected $rules = [
        'budgets.*.tax_label_id' => 'required|distinct|exists:tax_labels,id',
        'budgets.*.expected_amount' => 'required|numeric|min:0',
    ];
    public function mount()
    {
        $year = Year::getActiveYear();
        $last_budgets=$year->getBudgetsByTaxLabel();
        $this->yearId = $year->id;
        $this->availableTaxLabels = TaxLabel::all();
        $this->budgets = $last_budgets->map(function ($budget) {
            return [
                'tax_label_id' => $budget->tax_label_id,
                'expected_amount' => $budget->expected_amount,
                'id'=>$budget->id,
            ];
        })->toArray();

        $this->cleanCurrentBudget();
        if (!empty($this->budgets)) {
            $this->edit_mode = true;
        }
    }
    public function cleanCurrentBudget()
    {
        $this->current_budget = ['tax_label_id' => '', 'expected_amount' => 0, 'id'=>null];
    }
    public function addBudgetRow()
    {

        $this->budgets[] = $this->current_budget;
        $this->cleanCurrentBudget();

    }

    public function removeBudgetRow($index)
    {
        if($this->budgets[$index]['id']!=null){
            $this->deleteSavedBudget($this->budgets[$index]['id']);
        }
        unset($this->budgets[$index]);
        $this->budgets = array_values($this->budgets);
    }
    public function deleteSavedBudget($id)
    {
        Budget::find($id)?->delete();
    }
    public function submit()
    {
        if (!auth()->user()->hasPermissionTo('peut émettre un avis sur titre')) {
            abort(403, 'Accès interdit');
        }
        DB::transaction(function () {
            foreach ($this->budgets as $budget) {
                if($budget['tax_label_id']){
                    Budget::updateOrCreateBudget($this->yearId, $budget['tax_label_id'],$budget['expected_amount']);
                }

            }
            if ($this->edit_mode) {
                $this->dispatchMessage('Budget', 'update');
            } else {
                $this->dispatchMessage('Budget');
            }
        });

    }
    public function render()
    {
        return view('livewire.budget.add-budget');
    }
}
