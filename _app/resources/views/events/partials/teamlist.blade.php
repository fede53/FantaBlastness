@foreach ($teams as $team)

    @php
        $totalCost = $team['members']->sum('cost');
    @endphp
    <div class="flex bg-white shadow-lg rounded-lg overflow-hidden p-6 mb-6 items-start">
        <!-- Colonna sinistra: Grafico delle caratteristiche della squadra -->
        <div class="w-1/3">
            <canvas id="teamRadarChart-{{ $team['id'] }}" width="300" height="300"></canvas>
        </div>

        <!-- Colonna destra: Dettagli della squadra -->
        <div class="w-2/3 pl-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-2xl font-semibold text-gray-800">{{ $team['name'] }} - <span class="text-lg">{{ $team['user']['name'] }} {{ $team['user']['surname'] }}</span></h3>
                <p class="text-xl font-semibold text-gray-600">Total Cost: {{ $totalCost }}</p>
            </div>

            <!-- Griglia membri della tua squadra -->
            <div class="grid grid-cols-4 gap-4">
                @foreach ($team['members'] as $member)
                    @if ($member['captain'])
                        <div class="col-span-1 row-span-2 bg-blue-100 p-4 text-center shadow-md rounded-lg" data-characteristics='@json($member["characteristics"])'>
                            <img src="{{ asset('storage/' . $member['thumbnail']) }}" alt="Captain Image" class="w-24 h-24 object-cover rounded-full mx-auto">
                            <h4 class="text-xl font-bold text-gray-800 mt-2">{{ $member['name'] }}</h4>
                        </div>
                    @else
                        <div class="col-span-1 row-span-2 bg-white p-4 text-center shadow-md rounded-lg" data-characteristics='@json($member["characteristics"])'>
                            <img src="{{ asset('storage/' . $member['thumbnail']) }}" alt="Member Image" class="w-16 h-16 object-cover rounded-full mx-auto">
                            <h4 class="text-lg font-semibold text-gray-800 mt-2">{{ $member['name'] }}</h4>
                        </div>
                    @endif
                @endforeach
            </div>

        </div>
    </div>
@endforeach


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @foreach ($teams as $team)
        var ctx = document.getElementById('teamRadarChart-{{ $team['id'] }}').getContext('2d');

        var characteristics = @json($team['team_characteristics_average']);
        var characteristicData = [
            characteristics.pazzia,
            characteristics.alcolismo,
            characteristics.resistenza,
            characteristics.socialita,
            characteristics.seduzione,
            characteristics.professionalita,
        ];
        new Chart(ctx, {
            type: 'radar',
            data: {
                labels: ['Pazzia', 'Alcolismo', 'Resistenza', 'Socialita', 'Seduzione', 'Professionalita'],
                datasets: [{
                    label: 'Team Characteristics',
                    data: characteristicData,
                    borderWidth: 1,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                }]
            },
            options: {
                scales: {
                    r: {
                        min: 0,
                        max: 10,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
        @endforeach
    });



</script>
