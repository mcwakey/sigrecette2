<div class="modal fade" id="kt_modal_add_budgets" tabindex="-1" aria-hidden="true" wire:ignore.self data-bs-backdrop='static'>
    <div class="modal-dialog modal-dialog-centered mw-950px">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_budgets_header">
                @if(!$edit_mode)
                    <h2 class="fw-bold">{{ __('create Budget') }}</h2>
                @else
                    <h2 class="fw-bold">{{ __('Mettre à jour les informations du Budget') }}</h2>
                @endif
                <!--end::Modal title-->
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross', 'fs-1') !!}

                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body px-5 my-7">
                <form wire:submit.prevent="submit">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>TaxLabel</th>
                            <th>Montant attendu</th>
                        </tr>
                        </thead>
                        <tbody>


                        @php
                        $s_index=$this->budgets?count($this->budgets)-1:0;
                        @endphp
                            <tr>
                                <td>
                                    <select class="form-control"
                                            wire:model="current_budget.tax_label_id">
                                        <option value="">-- Sélectionnez une Taxe --</option>
                                        @foreach ($availableTaxLabels as $taxLabel)
                                            @if ($budgets && !in_array($taxLabel->id, array_column($budgets, 'tax_label_id')) || $taxLabel->id == $current_budget['tax_label_id']))
                                            <option value="{{ $taxLabel->id }}">{{ $taxLabel->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" step="0.01" class="form-control"
                                           wire:model="current_budget.expected_amount">
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <button type="button" class="btn  btn-primary" wire:click="addBudgetRow">
                        Ajouter une ligne
                    </button>
                    <button type="submit" class="btn btn-success ">
                        <span class="indicator-label" wire:loading.remove>{{ __('add') }}</span>
                        <span class="indicator-progress" wire:loading wire:target="submit">
                            {{ __('chargenment ...') }}
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </form>

                <h4 class="mt-4">Récapitulatif du budget</h4>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Taxe</th>
                        <th>Montant attendu</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($budgets as $index => $budget)
                        <tr>
                            @php
                                $taxLabel = $availableTaxLabels->firstWhere('id', $budget['tax_label_id']);
                            @endphp
                            @if ($taxLabel)
                                <td>{{ $taxLabel->name }}</td>
                                <td>{{ number_format($budget['expected_amount'], 2) }} FCFA</td>
                            <td>
                                <button type="button" class="btn btn-danger" wire:click="removeBudgetRow({{ $index }})">
                                   <span class="indicator-label">
                                                          <span class="indicator-label">
                                                        <i class="ki-duotone ki-trash">
                                                             <span class="path1"></span>
                                                             <span class="path2"></span>
                                                             <span class="path3"></span>
                                                             <span class="path4"></span>
                                                             <span class="path5"></span>
                                                            </i>
                                                    </span>
                                                    </span>
                                </button>
                            </td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            </div>
        </div>
</div>
