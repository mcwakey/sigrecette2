

<div class="card mt-4">
    <div class="card-header pt-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold text-gray-900">Graphique - avis</span>
            <span class="text-gray-500 mt-1 fw-semibold fs-6">Par date de creaction</span>
        </h3>

    </div>

    <div class="card-body">
        <div class="chart-container_taxpayer" style="position: relative; width: 100%; height: 400px;">
            <canvas id="invoiceChart"></canvas>
        </div>
    </div>

</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('invoiceChart').getContext('2d');

            const rawData = {!! json_encode($invoice_by_created_at) !!};

            const labels = rawData.map(invoice => invoice.date);
            const dataCreate = rawData.map(invoice => invoice.total_create);
            const dataUpdate = rawData.map(invoice => invoice.total_update);
            const topTaxpayers = rawData.map(invoice => invoice.name || 'N/A');
            const statusData = rawData.map(invoice => invoice.status);

            const statusCounts = {
                OWING: statusData.filter(s => s === 'OWING').length,
                PART_PAID: statusData.filter(s => s === 'PART PAID').length,
                PAID: statusData.filter(s => s === 'PAID').length
            };


            const colors = {
                'OWING': 'rgba(255, 99, 132, 0.5)',
                'PART PAID': 'rgba(255, 193, 7, 0.7)',
                'PAID': 'rgba(75, 192, 192, 0.5)'
            };

            const borderColors = {
                'OWING': 'rgba(255, 99, 132, 1)',
                'PART PAID': 'rgba(255, 205, 86, 1)',
                'PAID': 'rgba(75, 192, 192, 1)'
            };

            const datasets = ['OWING', 'PART PAID', 'PAID'].map(status => ({
                label: `Factures ${status}`,
                data: rawData.map(invoice => (invoice.status === status ? invoice.total_create : 0)),
                backgroundColor: colors[status],
                borderColor: borderColors[status],
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }));

            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: datasets
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
                                    const status = statusData[tooltipItem.dataIndex];
                                    return `Contribuable : ${taxpayer}\nStatut : ${status}`;
                                }
                            }
                        },
                        zoom: {
                            pan: {
                                enabled: true,
                                mode: 'x'
                            },
                            zoom: {
                                wheel: {
                                    enabled: true
                                },
                                mode: 'x',
                                speed: 0.1
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Date'
                            },
                            ticks: {
                                maxRotation: 45,
                                minRotation: 0
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

