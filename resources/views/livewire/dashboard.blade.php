<div class="p-6 bg-white rounded-lg shadow">
    <h2 class="text-xl font-bold mb-4">Statistiques globales</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-blue-100 p-4 rounded-lg text-center">
            <div class="text-3xl font-bold">{{ $totalPatients }}</div>
            <div>Patients</div>
        </div>
        <div class="bg-green-100 p-4 rounded-lg text-center">
            <div class="text-3xl font-bold">{{ $totalConsultations }}</div>
            <div>Consultations</div>
        </div>
        <div class="bg-purple-100 p-4 rounded-lg text-center">
            <div class="text-3xl font-bold">{{ $totalDicomImages }}</div>
            <div>DICOM importées</div>
        </div>
    </div>

    <h2 class="text-xl font-bold mb-4">Notifications récentes</h2>
    <ul class="mb-6">
        @forelse($recentNotifications as $notification)
            <li class="border-b py-2">
                <div class="font-semibold">{{ $notification->title }}</div>
                <div class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($notification->dateEnvoi)->format('d/m/Y H:i') }}</div>
                <div>{{ $notification->message }}</div>
            </li>
        @empty
            <li>Aucune notification récente.</li>
        @endforelse
    </ul>

    <h2 class="text-xl font-bold mb-4">Consultations par semaine</h2>
    <canvas id="consultationsChart" width="400" height="150"></canvas>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('livewire:load', function () {
        var ctx = document.getElementById('consultationsChart').getContext('2d');
        var labels = @json(array_keys($consultationsPerWeek));
        var data = @json(array_values($consultationsPerWeek));

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Consultations',
                    data: data,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: true,
                    tension: 0.3,
                }]
            },
            options: {
                scales: {
                    x: {
                        ticks: {
                            callback: function(value, index, ticks) {
                                var yearweek = this.getLabelForValue(value);
                                var year = yearweek.substring(0,4);
                                var week = yearweek.substring(4);
                                return 'S' + week + ' ' + year;
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        precision: 0
                    }
                }
            }
        });
    });
</script>
@endsection
