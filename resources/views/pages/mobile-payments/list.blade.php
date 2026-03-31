<x-default-layout>

    @section('title')
        Paiements mobiles
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('mobile-payments.index') }}
    @endsection

    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <div class="d-flex align-items-center">
                    <div class="d-flex align-items-center position-relative my-1">
                        {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                        <input type="text" data-kt-payment-table-filter="search" class="form-control w-250px ps-13"
                            placeholder="{{ __('search') }}" id="mySearchInput" />
                    </div>
                </div>
            </div>
            <!--end::Card title-->

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <div class="d-flex justify-content-end gap-3">
                    <a href="{{ route('mobile-payments.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-light' }}">Tous</a>
                    <a href="{{ route('mobile-payments.index', ['status' => 'verifying']) }}" class="btn btn-sm {{ request('status') === 'verifying' ? 'btn-info' : 'btn-light' }}">En vérification</a>
                    <a href="{{ route('mobile-payments.index', ['status' => 'failed']) }}" class="btn btn-sm {{ request('status') === 'failed' ? 'btn-danger' : 'btn-light' }}">Échoués</a>
                    <a href="{{ route('mobile-payments.index', ['status' => 'expired']) }}" class="btn btn-sm {{ request('status') === 'expired' ? 'btn-dark' : 'btn-light' }}">Expirés</a>
                    <a href="{{ route('mobile-payments.index', ['status' => 'success']) }}" class="btn btn-sm {{ request('status') === 'success' ? 'btn-success' : 'btn-light' }}">Réussis</a>
                </div>
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body py-4">
            <div class="table-responsive">
                {{ $dataTable->table() }}
            </div>
        </div>
        <!--end::Card body-->
    </div>

    <livewire:payment.mobile-payment-actions />

    @push('scripts')
        {{ $dataTable->scripts() }}
        <script>
            document.getElementById('mySearchInput').addEventListener('keyup', function() {
                window.LaravelDataTables['mobile-payments-table'].search(this.value).draw();
            });

            document.addEventListener('livewire:init', function() {
                Livewire.on('retryMobileVerification', function(data) {
                    Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id')).retryVerification(data.id);
                });

                Livewire.on('refreshTable', function() {
                    window.LaravelDataTables['mobile-payments-table'].ajax.reload();
                });

                Livewire.on('notify', function(data) {
                    var icon = data.type === 'success' ? 'success' : (data.type === 'error' ? 'error' : 'info');
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            text: data.message,
                            icon: icon,
                            buttonsStyling: false,
                            confirmButtonText: 'OK',
                            customClass: { confirmButton: 'btn btn-primary' }
                        });
                    } else {
                        alert(data.message);
                    }
                });
            });
        </script>
    @endpush

</x-default-layout>
