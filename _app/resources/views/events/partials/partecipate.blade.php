@php
    use Carbon\Carbon;

    $isPhase2InFuture = !Carbon::parse($event['date_phase_2'])->lessThan(Carbon::now());

    // Definisci $topTeams come array vuoto di default
    $topTeams = [];

    if (!$isPhase2InFuture) {
        // Ordina le squadre in base al punteggio
        $sortedTeams = $teams->sortByDesc('team_score');
        $currentRank = 0;
        $previousScore = null;

        // Prendi le prime 3 squadre, considerando eventuali ex aequo
        foreach ($sortedTeams as $team) {
            if ($previousScore === null || $previousScore !== $team['team_score']) {
                $currentRank++;
            }
            if ($currentRank > 3) {
                break;
            }
            if (!isset($topTeams[$currentRank])) {
                $topTeams[$currentRank] = [];
            }
            $topTeams[$currentRank][] = $team;
            $previousScore = $team['team_score'];
        }
    }
@endphp

    <!-- Titolo dell'evento e Countdown -->
<div class="flex justify-between items-center mb-8">
    <h2 class="text-3xl font-semibold text-white">{{ $event['name'] }}</h2>
</div>

@if (!empty($topTeams))
    <!-- Podio dei Vincitori -->
    <div class="podium-container mb-8">
        <div class="podium">
            @foreach ($topTeams as $rank => $teamsAtRank)
                <div class="podium-block {{ $rank == 1 ? 'first' : ($rank == 2 ? 'second' : 'third') }}">
                    <div class="rank">{{ $rank }}</div>
                    @foreach ($teamsAtRank as $team)
                        <div class="team-info">
                            <div class="team-name font-semibold">{{ $team['name'] }}</div>
                            <div class="user-name text-xs">{{ $team['user']['name'] }} {{ $team['user']['surname'] }}</div>
                            <div class="score font-bold">{{ $team['team_score'] }}</div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
@endif


<!-- Countdown per diverse fasi -->
@if (Carbon::parse($event['date_phase_1'])->greaterThanOrEqualTo(Carbon::now()) &&
            Carbon::parse($event['date_phase_2'])->isFuture() && !$event['eventScoreCheck'])
    <div class="countdown text-white font-semibold" data-countdown="{{ $event['date_phase_1'] }}" data-msg="Ora puoi compilare i tuoi bonus e malus!">
        Potrai compilare i tuoi bonus e malus tra: <span class="text-primary text-xl"></span>
    </div>
@elseif (Carbon::parse($event['date_phase_1'])->lessThan(Carbon::now()) &&
        Carbon::parse($event['date_phase_2'])->isFuture())
    <div class="countdown text-white font-semibold" data-countdown="{{ $event['date_phase_2'] }}" data-msg="Visualizza i risultati!">
        I risultati verranno pubblicati tra: <span class="text-primary text-xl"></span>
    </div>
@endif

@if (Carbon::parse($event['date_phase_1'])->lessThan(Carbon::now()) && Carbon::parse($event['date_phase_2'])->isFuture() && !$event['eventScoreCheck'])
    <div class="flex">
        <a href="{{ route('score.create', $event['id']) }}" class="px-3 bg-primary hover:bg-primary text-white font-bold py-4 px-8 rounded-lg text-xl shadow-lg transition duration-300 ease-in-out">
            Compila i tuoi bonus e malus
        </a>
    </div>
@endif

<!-- Navigazione a Tab -->
<div class="flex space-x-4 mb-10-2 pt-10 !mb-6 tab-list">
    <button class="tab-button px-6 py-3 font-semibold text-xl text-white bg-dark border-b-2 transition duration-300 active-tab" data-target="classifica-tab">
        Classifica {{ !$isPhase2InFuture ? 'Finale' : 'Parziale' }}
    </button>
    <button class="tab-button px-6 py-3 font-semibold text-xl text-white bg-dark border-b-2 transition duration-300" data-target="team-tab">
        La mia Squadra
    </button>

    @if ($isPhase2InFuture)
        <button class="tab-button px-6 py-3 font-semibold text-xl text-white bg-dark border-b-2 transition duration-300" data-target="other-teams-tab">
            Altre Squadre
        </button>
    @endif

    @if (!$isPhase2InFuture)
        <button class="tab-button px-6 py-3 font-semibold text-xl text-white bg-dark border-b-2 transition duration-300" data-target="score-tab">
            Punteggi
        </button>
    @endif


</div>

<div class="tab-content" id="classifica-tab">
    @include('events.partials.classifica', ['teams' => $teams, 'isPhase2InFuture' => $isPhase2InFuture])
</div>
<div class="tab-content hidden" id="team-tab">
    @include('events.partials.teamlist', ['teams' => $teams->filter(fn($team) => $team['user']['id'] === auth()->user()->id), 'isPhase2InFuture' => $isPhase2InFuture])
</div>
@if ($isPhase2InFuture)
    <div class="tab-content hidden" id="other-teams-tab">
        @include('events.partials.teamlist', ['teams' => $teams->filter(fn($team) => $team['user']['id'] !== auth()->user()->id), 'isPhase2InFuture' => $isPhase2InFuture])
    </div>
@endif
@if (!$isPhase2InFuture)
    <div class="tab-content hidden" id="score-tab">
        @include('events.partials.score', ['teams' => $teams->filter(fn($team) => $team['user']['id'] !== auth()->user()->id), 'isPhase2InFuture' => $isPhase2InFuture])
    </div>
@endif


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                tabContents.forEach(content => content.classList.add('hidden'));
                tabButtons.forEach(btn => {
                    btn.classList.remove('bg-primary', 'active-tab');
                });

                const target = this.getAttribute('data-target');
                document.getElementById(target).classList.remove('hidden');

                this.classList.add('bg-primary', 'active-tab');
            });
        });

        if (tabButtons.length > 0) {
            tabButtons[0].click();
        }
    });
</script>

<style>
    .podium-container {
        display: flex;
        justify-content: center;
        align-items: flex-end;
        margin-top: 50px;
    }

    .podium {
        display: flex;
        gap: 10px;
        align-items: flex-end;
    }

    .podium-block {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-end;
        width: 120px;
        border-radius: 10px;
        color: #fff;
        text-align: center;
        padding: 10px;
    }

    .podium-block .rank {
        font-size: 2rem;
        font-weight: bold;
    }

    .podium-block.first {
        background-color: #bda106;
        height: 230px;
    }

    .podium-block.second {
        background-color: silver;
        height: 180px;
    }

    .podium-block.third {
        background-color: #cd7f32;
        height: 130px;
    }

    

    .podium-block {
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    }

    .podium-block.first {
        animation: bounce 1s ease-in-out infinite alternate;
    }

    @keyframes bounce {
        0% {
            transform: translateY(0);
        }
        100% {
            transform: translateY(-10px);
        }
    }

    .tab-button {
        margin-right: 8px;
    }

    .tab-content {
        padding-top: 1rem;
    }
</style>
