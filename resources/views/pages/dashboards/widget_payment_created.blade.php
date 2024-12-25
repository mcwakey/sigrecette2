

<div class="card mt-4">
    <div class="card-header pt-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold text-gray-900">Graphique - avis</span>
            <span class="text-gray-500 mt-1 fw-semibold fs-6">Par date</span>
        </h3>
        <!--end::Toolbar-->
    </div>
    <div class="card-body">
        <div class="chart-payment_taxpayer" style="position: relative; width: 100%; height: 400px;">
            <canvas id="paymentEvolutionChart"></canvas>
        </div>
    </div>

</div>

@push('scripts')


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom"></script>
    <script>



        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('paymentEvolutionChart').getContext('2d');

            const rawData = {!! json_encode($payments_data) !!};

            console.log(rawData);
            const labels = rawData.map(log => log.date);
            const totalPayments = rawData.map(log => log.total_payments);
            const totalAmount = rawData.map(log => log.total_amount);
            const payStatuses = rawData.map(log => log.pay_status);

            const colors = {
                'OWING': 'rgba(255, 99, 132, 0.5)',
                'PART PAID': 'rgba(255, 159, 64, 0.5)',
                'PAID': 'rgba(75, 192, 192, 0.5)'
            };

            const datasets = ['OWING', 'PART PAID', 'PAID'].map(status => {
                const dataForStatus = totalPayments.map((_, index) => payStatuses[index] === status ? totalPayments[index] : null);
                return {
                    label: status,
                    data: dataForStatus,
                    backgroundColor: colors[status],
                    borderColor: colors[status],
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    pointRadius: 5,
                    pointHoverRadius: 7
                };
            });

            const chart = new Chart(ctx, {
                type: 'line',
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
                                    const date = labels[tooltipItem.dataIndex];
                                    const paymentsOnDate = totalPayments[tooltipItem.dataIndex];
                                    const amount = totalAmount[tooltipItem.dataIndex];
                                    return `Date : ${date}, Paiements : ${paymentsOnDate} ${amount}`;
                                }
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
                                text: 'Nombre de Paiements'
                            }
                        }
                    }
                }
            });
        });





    </script>
@endpush

