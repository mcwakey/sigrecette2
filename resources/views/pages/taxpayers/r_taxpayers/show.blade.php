<x-default-layout>

    @section('title')
        {{ __("Analyse des données") }}
    @endsection
    @section('breadcrumbs')
        {{ Breadcrumbs::render('taxpayers.index') }}
    @endsection
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #printable-area, #printable-area * {
                visibility: visible;
            }

            #printable-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>

    <div class="container mt-3">
        <div class="d-flex justify-content-end">
            <button class="btn btn-primary" onclick="window.print()">
                🖨️ Imprimer la page
            </button>
        </div>
        <div class="bg-white p-4 rounded mt-5 shadow-sm border">
            <div class="d-flex align-items-center mb-3">
                <i class="fas fa-info-circle text-info fs-4 me-2"></i>
                <h4 class="text-info mb-0">
                    {{ __('Procédure') }}
                </h4>
            </div>
            <p class="text-muted fs-6">
                {{ __("Cette page vous permet de consulter les statistiques des avis créés sur une période donnée.
                Pour afficher les données, appliquez les filtres de date et sélectionnez la période souhaitée.

                Si vous avez récemment mis à jour votre base de données, comme après un recensement,
                choisissez la période allant du début du recensement jusqu'à la date de fin d'émission des avis sur titre
                (ou la date du jour pour voir les données actuelles).") }}
            </p>
        </div>

    </div>
    <div class="container " id="printable-area">

        <div class="container mt-5 ">
            <div class="row g-3">
                <div class="col-md-3 col-sm-6">
                    <div class="border border-gray-300 border-dashed rounded-lg shadow-sm bg-light p-4 text-center">
                        <div class="d-flex justify-content-center align-items-center mb-2">
                    <span class="svg-icon fs-3 text-success me-2">
                        <i class="bi bi-cash-stack"></i>
                    </span>
                            <div class="fs-2 fw-bold text-success" data-kt-countup="true"
                                 data-kt-countup-value="{{ $invoices_total }}">0
                            </div>
                        </div>
                        <div class="fw-semibold fs-6">Total Montant Avis</div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="border border-gray-300 border-dashed rounded-lg shadow-sm bg-light p-4 text-center">
                        <div class="d-flex justify-content-center align-items-center mb-2">
                    <span class="svg-icon fs-3 text-info me-2">
                        <i class="bi bi-people"></i>
                    </span>
                            <div class="fs-2 fw-bold text-info" data-kt-countup="true"
                                 data-kt-countup-value="{{ $taxpayer_count }}">0
                            </div>
                        </div>
                        <div class="fw-semibold fs-6">Nombre de Contribuables</div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="border border-gray-300 border-dashed rounded-lg shadow-sm bg-light p-4 text-center">
                        <div class="d-flex justify-content-center align-items-center mb-2">
                    <span class="svg-icon fs-3 text-primary me-2">
                        <i class="bi bi-box-seam"></i>
                    </span>
                            <div class="fs-2 fw-bold text-primary" data-kt-countup="true"
                                 data-kt-countup-value="{{ $taxables_count }}">0
                            </div>
                        </div>
                        <div class="fw-semibold fs-6">Nombre de Matière(sur un avis)</div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="border border-gray-300 border-dashed rounded-lg shadow-sm bg-light p-4 text-center">
                        <div class="d-flex justify-content-center align-items-center mb-2">
                    <span class="svg-icon fs-3 text-danger me-2">
                        <i class="bi bi-receipt"></i>
                    </span>
                            <div class="fs-2 fw-bold text-danger" data-kt-countup="true"
                                 data-kt-countup-value="{{ $invoice_count }}">0
                            </div>
                        </div>
                        <div class="fw-semibold fs-6">Nombre d'Avis</div>
                    </div>
                </div>
            </div>
        </div>
        <h3 class="mt-5">Contribuables par zone fiscale et par genre</h3>
        <div class="row">
            <div class="col-md-6">
                <canvas id="taxpayerPieChart"></canvas>
            </div>
            <div class="col-md-6">
                <canvas id="genderPieChart"></canvas>
            </div>
        </div>
        <h3 class="mt-5">Total avis par Libellés Fiscaux et Matières Taxables</h3>
        <div class="row">
            <div class="col-12">
                <canvas id="labelsChart" class="w-100" style="height: 300px;"></canvas>
            </div>
            <div class="col-12 mt-3">
                <canvas id="taxablesChart" class="w-100" style="height: 300px;"></canvas>
            </div>
        </div>


        <h3 class="mt-5">Détails Montant avis par Libellés Fiscaux </h3>
        <table class="table table-bordered">
            <thead class="table-dark">
            <tr>

                <th>Code</th>
                <th>Nom</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($labels as $id => $label)
                <tr>
                    <td>{{ $label['code'] }}</td>
                    <td>{{ $label['name'] }}</td>
                    <td>{{ number_format($label['total'], 2, ',', ' ') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <h3 class="mt-5">Détails Montant avis par Matières Taxables </h3>
        <table class="table table-bordered">
            <thead class="table-dark">
            <tr>
                <th>Code</th>
                <th>Nom</th>

                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($taxables as $id => $taxable)
                <tr>
                    <td>{{ $taxable['code'] }}</td>
                    <td>{{ $taxable['name'] }}</td>
                    <td>{{ number_format($taxable['total'], 2, ',', ' ') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @push('scripts')
            <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}" defer></script>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
    function generateColors(length, opacity = 0.6) {
        return Array.from({ length }, () => {
            const r = Math.floor(Math.random() * 255);
            const g = Math.floor(Math.random() * 255);
            const b = Math.floor(Math.random() * 255);
            return `rgba(${r}, ${g}, ${b}, ${opacity})`;
        });
    }

    const labels = @json($labels ? array_column($labels, 'name') : []);
    const labelsTotals = @json($labels ? array_column($labels, 'total') : []);
    if (labels.length > 0) {
        new Chart(document.getElementById('labelsChart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total par Libellés Fiscaux',
                    data: labelsTotals,
                    backgroundColor: generateColors(labels.length, 0.5),
                    borderColor: generateColors(labels.length, 1),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: false,
                        ticks: {
                            callback: function(value) {
                                return Number(value).toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }

    const taxables = @json($taxables ? array_column($taxables, 'name') : []);
    const taxablesTotals = @json($taxables ? array_column($taxables, 'total') : []);
    if (taxables.length > 0) {
        new Chart(document.getElementById('taxablesChart'), {
            type: 'bar',
            data: {
                labels: taxables,
                datasets: [{
                    label: 'Total par  Matières Taxables',
                    data: taxablesTotals,
                    backgroundColor: generateColors(taxables.length, 0.5),
                    borderColor: generateColors(taxables.length, 1),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: false,
                        ticks: {
                            callback: function(value) {
                                return Number(value).toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }

    const zoneLabels = @json($zoneLabels ?? []);
    const zoneTotals = @json($zoneTotals ?? []);
    if (zoneLabels.length > 0) {
        new Chart(document.getElementById('taxpayerPieChart'), {
            type: 'pie',
            data: {
                labels: zoneLabels,
                datasets: [{
                    label: 'Contribuables par Zone',
                    data: zoneTotals,
                    backgroundColor: generateColors(zoneLabels.length),
                    borderColor: generateColors(zoneLabels.length, 1),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                  plugins: {
                    tooltip: {
                        enabled: true
                    },
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }, animation: {
                    onComplete: function () {
                        const ctx = this.ctx;
                        ctx.font = 'bold 14px Arial';
                        ctx.fillStyle = '#ffffff';
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';

                        this.data.datasets.forEach((dataset, i) => {
                            const meta = this.getDatasetMeta(i);
                            meta.data.forEach((arc, index) => {
                                const center = arc.tooltipPosition();
                                const value = dataset.data[index];
                                ctx.fillText(value, center.x, center.y);
                            });
                        });
                    }
                 }
            }
        });
    }

    const genderLabels = @json($genderLabels ?? []);
    const genderTotals = @json($genderTotals ?? []);
    if (genderLabels.length > 0) {
        new Chart(document.getElementById('genderPieChart'), {
            type: 'pie',
            data: {
                labels: genderLabels,
                datasets: [{
                    label: 'Répartition des contribuables par genre',
                    data: genderTotals,
                    backgroundColor: generateColors(genderLabels.length),
                    borderColor: generateColors(genderLabels.length, 1),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        enabled: true
                    },
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },

animation: {
                    onComplete: function () {
                        const ctx = this.ctx;
                        ctx.font = 'bold 14px Arial';
                        ctx.fillStyle = '#ffffff';
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';

                        this.data.datasets.forEach((dataset, i) => {
                            const meta = this.getDatasetMeta(i);
                            meta.data.forEach((arc, index) => {
                                const center = arc.tooltipPosition();
                                const value = dataset.data[index];
                                ctx.fillText(value, center.x, center.y);
                            });
                        });
                    }
                 }
            }
        });
    }
});

        </script>

    @endpush
</x-default-layout>
