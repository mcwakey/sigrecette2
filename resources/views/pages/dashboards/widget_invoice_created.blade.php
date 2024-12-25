

<div class="card mt-4">
    <div class="card-header pt-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold text-gray-900">Graphique - avis</span>
            <span class="text-gray-500 mt-1 fw-semibold fs-6">Par date</span>
        </h3>
        <!--end::Toolbar-->
    </div>
    <div class="card-body">
        <div class="chart-container_taxpayer" style="position: relative; width: 100%; height: 400px;">
            <canvas id="invoiceChart"></canvas>
        </div>
    </div>

</div>

@push('scripts')


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('invoiceChart').getContext('2d');

            const rawData = {!! json_encode($invoice_by_created_at) !!};

            // Préparation des données
            const labels = rawData.map(invoice => invoice.date);
            const dataCreate = rawData.map(invoice => invoice.total_create);
            const dataUpdate = rawData.map(invoice => invoice.total_update);
            const topTaxpayers = rawData.map(invoice => invoice.name || 'N/A');

            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Avis Créées',
                            data: dataCreate,
                            backgroundColor: 'rgba(75, 192, 192, 0.5)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Avis Mises à Jour',
                            data: dataUpdate,
                            backgroundColor: 'rgba(255, 159, 64, 0.5)',
                            borderColor: 'rgba(255, 159, 64, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                afterLabel: function (tooltipItem) {
                                    const taxpayer = topTaxpayers[tooltipItem.dataIndex];
                                    return `Contribuable : ${taxpayer}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: "Nombre d'avis"
                            }
                        }
                    }
                }
            });
        });





    </script>

    <script>
        function initializeCharts() {

        }
        initializeCharts();
    </script>
@endpush

