<x-default-layout>
    @section('title')
        {{ "Budget actuel ".__('contribuables') }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('taxpayers.index') }}
    @endsection

    <div class="card">

        <div class="card-header d-flex justify-content-between border-0 mb-2 pt-6 w-100">
            <!--begin::Card title-->
            <div class="card-title">

                <div class="d-flex align-items-center">
                    <!--begin::Input group-->
                    <div class="d-flex align-items-center position-relative my-1">
                        {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                        <input type="text" data-kt-taxpayer-table-filter="search" class="form-control w-250px ps-13"
                               placeholder="{{ __('search') }}" id="mySearchInput" />
                    </div>
                    <!--end::Input group-->
                    <!--begin:Action-->
                    <div class="d-flex align-items-center ms-5">
                        <a href="#" id="kt_horizontal_search_advanced_link" data-kt-rotate="true"
                           class="btn btn-outline btn-outline-dashed btn-outline-secondary btn-active-light-secondary me-5 rotate"
                           data-bs-toggle="collapse" data-bs-target="#kt_advanced_search_form">
                            {{ __('advanced search') }} <i
                                class="ki-duotone ki-black-right-line fs-2 rotate-270 ms-3"><span
                                    class="path1"></span><span class="path2"></span></i></a>
                    </div>


                </div>





            </div>

                <div class="card-toolbar">


                        @can('peut créer un contribuable')
                            <div class="d-flex justify-content-end" data-kt-stock_request-table-toolbar="base">
                                <!--begin::Add user-->
                                <button type="button" class="btn btn-light-success h-45px ms-auto" data-bs-toggle="modal"
                                        data-bs-target="#kt_modal_add_budgets">
                                    {!! getIcon('plus', 'fs-2', '', 'i') !!}
                                    {{ __('new budgets') }}
                                </button>
                                <!--end::Add user-->
                            </div>
                        @endcan
                        <div class="d-flex justify-content-end" data-kt-stock_request-table-toolbar="base">
                            <!--begin::Add user-->
                            <a href="#" class="ms-5 mt-1" data-bs-toggle="collapse" data-bs-target="#kt_tutorial_form">
                                <span>
                                    <i class="ki-outline ki-information fs-2tx text-warning"></i>
                                </span>
                            </a>
                            <!--end::Add user-->
                        </div>

                </div>

        </div>


        <div class="card-body py-4">

            <form action="#">
                <div class="collapse" id="kt_advanced_search_form">
                    <div class="separator separator-dashed mt-5 mb-5"></div>
                </div>

                <div class="collapse" id="kt_tutorial_form">
                    <div class="notice d-flex bg-light-danger rounded border-warning border border-dashed p-6">
                        <i class="ki-outline ki-information fs-2tx text-warning me-4"></i>
                    </div>

                    <div class="separator separator-dashed mt-5 mb-5"></div>
                </div>
            </form>
            <canvas id="budgetChart" width="400" height="200"></canvas>



            <div class="table-responsive">
                {{ $dataTable->table() }}
            </div>
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>

    <livewire:budget.add-budget />

    @push('scripts')

        {{ $dataTable->scripts() }}
            <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

            <script>
            $(document).ready(function() {
                var table = $('#budgets-table').DataTable();

                table.on('xhr', function() {
                    var json = table.ajax.json();
                    if (json.data.length === 0) {
                        $('#no-data-message').hide();
                    } else {
                        $('#no-data-message').show();

                    }
                });
            });
            document.getElementById('mySearchInput').addEventListener('keyup', function() {
                window.LaravelDataTables['budgets-table'].search(this.value).draw();
            });






            document.addEventListener('livewire:init', function() {
                Livewire.on('success', function() {
                    $('#kt_modal_add_budgets').modal('hide');
                    window.LaravelDataTables['budgets-table'].ajax.reload();
                });
            });

            const budgetData = @json($stats);

            const labels = budgetData.map(item => item.tax_label);
            const expectedAmounts = budgetData.map(item => item.expected_amount);
            const actualAmounts = budgetData.map(item => item.actual_amount);
            const colors = actualAmounts.map(amount => amount < 10 ? 'red' : 'rgba(75, 192, 192, 0.6)');

            // Configuration du graphique
            const ctx = document.getElementById('budgetChart').getContext('2d');
            const budgetChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Montant attendu (Budget)',
                            data: expectedAmounts,
                            backgroundColor: 'rgba(54, 162, 235, 0.6)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                        },
                        {
                            label: 'Montant reçu (Paiements)',
                            data: actualAmounts,
                            backgroundColor: colors,
                            borderColor: colors.map(c => c.replace('0.6', '1')),
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    plugins: {
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        },
                        legend: {
                            display: true,
                        },
                        datalabels: {
                            display: true,
                            color: 'black',
                            anchor: 'end',
                            align: 'top',
                            formatter: (value) => value.toLocaleString(),
                        },
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                        },
                        y: {
                            type: 'logarithmic',
                            title: {
                                display: true,
                                text: 'Montants (en unités monétaires)',
                            },
                            min: 1,
                        },
                    },
                },
            });

        </script>
    @endpush

</x-default-layout>
