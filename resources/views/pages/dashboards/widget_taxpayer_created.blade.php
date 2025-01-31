

<div class="card mt-4">
    <div class="card-header pt-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold text-gray-900">Graphique - Contribuables</span>
            <span class="text-gray-500 mt-1 fw-semibold fs-6">Par date et createur</span>
        </h3>
        <!--end::Toolbar-->
    </div>
    <div class="card-body">
        <div class="chart-container_taxpayer" style="position: relative; width: 100%; height: 400px;">
            <canvas id="taxpayerChart"></canvas>
        </div>
    </div>

</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('taxpayerChart').getContext('2d');

            const rawData = {!! json_encode($taxpayer_by_created_at) !!};


            const labels = rawData.map(log => log.date);
            const dataCreate = rawData.map(log => log.total_create);
            const dataUpdate = rawData.map(log => log.total_update);
            const topUsers = rawData.map(log => ({
                id: log.created_by,
                name: log.user_name
            }));

            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Total des contribuables créés par Jour',
                            data: dataCreate,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.3,
                            pointRadius: 5,
                            pointHoverRadius: 7
                        },
                        {
                            label: 'Total des contribuables mis à jour par Jour',
                            data: dataUpdate,
                            backgroundColor: 'rgba(255, 159, 64, 0.2)',
                            borderColor: 'rgba(255, 159, 64, 1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.3,
                            pointRadius: 5,
                            pointHoverRadius: 7
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
                                    const topUser = topUsers[tooltipItem.dataIndex];
                                    return `Utilisateur le plus actif : ${topUser ? topUser.name + ' (ID: ' + topUser.id + ')' : 'N/A'}`;
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
                                text: "Nombre de contribuables"
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

