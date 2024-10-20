<div class="d-flex flex-column">
    <form class="form" action="#" wire:submit="submit" enctype="multipart/form-data">
        @if($taxpayer_taxable->invoice_id == null)
            @if($taxpayer_taxable->billable == 1)
            <input name="billable" class="form-check-input" type="checkbox" value="{{ $taxpayer_taxable->billable }}" data-kt-user-id="{{ $taxpayer_taxable->id }}" data-kt-action="update_checkbox" checked />
            @else
            <input name="billable" class="form-check-input" type="checkbox" value="{{ $taxpayer_taxable->billable }}" data-kt-user-id="{{ $taxpayer_taxable->id }}" data-kt-action="update_checkbox"/>
            @endif
        @else
        <input name="billable" class="form-check-input" type="checkbox" value="{{ $taxpayer_taxable->billable }}" data-kt-user-id="{{ $taxpayer_taxable->id }}" data-kt-action="update_checkbox" disabled />
        @endif
    </form>
</div>
