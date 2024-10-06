@foreach ($teams as $team)

    @php
        $totalCost = $team['members']->sum('cost');
    @endphp
    <div class="flex bg-dark-100 shadow-lg rounded-lg overflow-hidden p-6 mb-6 items-start myteam-cnt">
        <!-- Colonna sinistra: Grafico delle caratteristiche della squadra -->
        <div class="w-3/12">
            <canvas id="teamRadarChart-{{ $team['id'] }}" width="300" height="300"></canvas>

        </div>

        <!-- Colonna destra: Dettagli della squadra -->
        <div class="w-3/4 pl-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-2xl font-semibold text-white name-myteam">{{ $team['name'] }} - <span class="text-lg text-primary">{{ $team['user']['name'] }} {{ $team['user']['surname'] }}</span></h3>
                <p class="text-xl font-semibold text-white cost-myteam">Total Cost: {{ $totalCost }}</p>
            </div>

            <!-- Griglia membri della tua squadra -->
            <div class="grid grid-cols-7 gap-4">
                @foreach ($team['members'] as $member)
                    @if ($member['captain'])
                        <div class="col-span-7 row-span-2 bg-dark dark:bg-dark p-4 text-center shadow-md rounded-lg border-b-2 border-primary" data-characteristics='@json($member["characteristics"])'>
                            <img src="{{ asset('storage/' . $member['thumbnail']) }}" alt="Captain Image" class="w-10 h-10 object-cover rounded-full mx-auto">
                            <h4 class="captain-name text-xl font-bold text-white mt-2"><div class="captain-star"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                        <!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                        <path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"/></svg>
                                </div>{{ $member['name'] }}</h4>
                                @if (!$isPhase2InFuture)
                                    <h2 class="text-lg text-primary">{{ (!$isPhase2InFuture) ? $member['scoreWithExtra'] : $member['score'] }}</h2>
                                @endif
                        </div>
                    @else
                        <div class="col-span-1 row-span-2 text-center bg-dark-100 p-4 text-center shadow-md rounded-lg" data-characteristics='@json($member["characteristics"])'>
                            <img src="{{ asset('storage/' . $member['thumbnail']) }}" alt="Member Image" class="w-10 h-10 object-cover rounded-full mx-auto">
                            <h4 class="text-base font-semibold text-white mt-2">{{ $member['name'] }}</h4>
                            @if (!$isPhase2InFuture)
                                <h2 class="text-lg text-primary">{{ (!$isPhase2InFuture) ? $member['scoreWithExtra'] : $member['score'] }}</h2>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>

        </div>
    </div>

    @if (!$isPhase2InFuture)
        <!-- Sezione per visualizzare l'array extra dei membri con attributi extra -->
        <div class="bg-dark-200 p-6 rounded-lg mb-6">
            <h4 class="text-xl font-bold text-primary mb-4">Informazioni Extra</h4>
            @if (!empty($team['extra_array']))
                <table class="w-full text-sm text-left text-white">
                @foreach ($team['extra_array'] as $extraInfo)
                    <tr>
                        <td class="w-2/12 text-xl font-bold text-primary">{{ ($extraInfo['extra']>0 ? '+' : '') . $extraInfo['extra'] }}</td>
                        <td class="w-3/12"><h5 class="text-lg font-semibold text-white">{{ $extraInfo['name'] }}</h5></td>
                        <td><p class="text-sm text-white">{{ $extraInfo['extra_message'] }}</p></td>
                    </tr>
                @endforeach
                </table>
            @else
                <tr>
                    <td>
                        <p class="text-white">Non ci sono extra per questa squadra.</p>
                    </td>
                </tr>
            @endif
        </div>
    @endif

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
        /* new Chart(ctx, {
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
        });*/
        new Chart(ctx, {
            type: 'radar',
            data: {
                labels: ['Pazzia', 'Alcolismo', 'Resistenza', 'Socialita', 'Seduzione', 'Professionalita'],
                datasets: [{
                    label: 'Team Characteristics',
                    data: characteristicData,
                    borderWidth: 1,
                    backgroundColor: 'rgba(224, 51, 138,0.37)', // Colore dell'area riempita, con trasparenza
                    borderColor: '#e0338a',        // Colore del bordo del dataset
                    pointBackgroundColor: '#e0338a', // Colore dei punti per evidenziare meglio
                    pointBorderColor: 'rgba(255, 255, 255, 1)'     // Bordo bianco per punti per contrastare lo sfondo
                }]
            },
            options: {
                scales: {
                    r: {
                        min: 0,
                        max: 10,
                        ticks: {
                            stepSize: 1,
                            display: false
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.2)'  // Colore della griglia in modalità scura
                        },
                        angleLines: {
                            color: 'rgba(255, 255, 255, 0.2)' // Linee angolari del radar in modalità scura
                        },
                        pointLabels: {
                            color: 'rgba(255, 255, 255, 0.85)' // Colore delle etichette dei punti (categorie)
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: 'rgba(255, 255, 255, 0.85)' // Colore delle etichette della legenda
                        },
                        display: false
                    }
                },
                responsive: true,
            }
        });

        @endforeach
    });



</script>
