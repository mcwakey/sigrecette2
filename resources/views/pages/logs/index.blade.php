<x-default-layout>
    @section('title')
        {{ __("Statistiques d'utilisation") }}
    @endsection

    @section('breadcrumbs')
            {{ Breadcrumbs::render('user-management.users.index') }}
    @endsection
        <div class="card">


            <form method="GET" action="{{ route('user-management.user-activity.index') }}">
                <div class="row mb-3">
                    <div class="col">
                        <input type="date" name="s_date" class="form-control" value="{{ request('s_date') }}">
                    </div>
                    <div class="col">
                        <input type="date" name="e_date" class="form-control" value="{{ request('e_date') }}">
                    </div>
                    <div class="col">
                        <input type="text" name="user_id" class="form-control" placeholder="ID utilisateur" value="{{ request('user_id') }}">
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-primary">Filtrer</button>
                    </div>
                </div>
            </form>

        </div>
        <div class="card mt-4">
            <div class="card-body">
                <h3>Statistiques Globales</h3>
                <div class="chart-container" style="position: relative; width: 100%; height: 400px;">
                    <canvas id="activityChart"></canvas>
                </div>
            </div>

        </div>
        <div class="card">
            <h1>Statistiques d'utilisation</h1>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>IP</th>
                    <th>Requête</th>
                    <th>Réponse</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                @foreach($logs_tab as $log)
                    <tr>
                        <td class="  d-flex align-items-center"><div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                <a href="{{ route('user-management.users.show', $log->user) }}">
                                    @if($log->user->profile_photo_url)
                                        <div class="symbol-label">
                                            <img src="{{ $user->profile_photo_url }}" class="w-100"/>
                                        </div>
                                    @else
                                        <div class="symbol-label fs-3 {{ app(\App\Actions\GetThemeType::class)->handle('bg-light-? text-?', $log->user->name) }}">
                                            {{ substr($log->user->name, 0, 1) }}
                                        </div>
                                    @endif
                                </a>
                            </div>
                            <div class="d-flex flex-column">
                                <a href="{{ route('user-management.users.show', $log->user) }}" class="text-gray-800 text-hover-primary mb-1">
                                    {{ $log->user->name }}-{{ $log->user->id}}
                                </a>
                                <span>{{ $log->user->email }}</span>
                            </div></td>
                        <td>{{ $log->ip_address }}</td>
                        <td>{{  json_decode($log->request)?->path }}</td>
                        <td>{{ json_decode($log->response)?->status }}</td>
                        <td>{{ $log->created_at?->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-center mt-3">
                {{ $logs_tab->onEachSide(1)->links('pagination::bootstrap-4') }}
            </div>
        </div>
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const ctx = document.getElementById('activityChart').getContext('2d');

                    const rawData = {!! json_encode($logs) !!};

                    const labels = rawData.map(log => log.date);
                    const data = rawData.map(log => log.total_requests);
                    const topUsers = rawData.map(log => ({
                        name:log.user_name,
                        id:log.user_id
                    }));


                    const chart = new Chart(ctx, {
                        type: 'line',
                        fill: true,
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Total des Requêtes par Jour',
                                data: data,
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.3,
                                pointRadius: 5,
                                pointHoverRadius: 7
                            }]
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
                                        text: 'Nombre de Requêtes'
                                    }
                                }
                            }
                        }
                    });
                });


            </script>
        @endpush


</x-default-layout>
