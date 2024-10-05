<div class="overflow-x-auto relative shadow-md sm:rounded-lg bg-dark-100">
    <table class="w-full text-sm text-left text-white">
        <thead class="text-xs uppercase text-white">
        <tr>
            <th scope="col" class="py-3 px-6 text-center">
                Posizione
            </th>
            <th scope="col" class="py-3 px-6">
                Nome Squadra
            </th>
            <th scope="col" class="py-3 px-6">
                Proprietario
            </th>

            @if (!$isPhase2InFuture)
                <th scope="col" class="py-3 px-6 text-center">
                    Punteggio Extra
                </th>
            @endif

            <th scope="col" class="py-3 px-6 text-center">
                Punteggio {{ !$isPhase2InFuture ? 'Finale' : 'Parziale' }}
            </th>
        </tr>
        </thead>
        <tbody>
        @php
            // Ordina le squadre per punteggio in ordine decrescente
            $sortedTeams = $teams->sortByDesc('team_score');
            $position = 1;
        @endphp
        @foreach ($sortedTeams as $team)
            <tr class="bg-dark border-b">
                <td class="py-4 px-6 text-primary text-center">
                    {{ $position++ }}
                </td>
                <td class="py-4 px-6">
                    {{ $team['name'] }}
                </td>
                <td class="py-4 px-6">
                    {{ $team['user']['name'] }} {{ $team['user']['surname'] }}
                </td>
                @if (!$isPhase2InFuture)
                    <td class="py-4 px-6 font-semibold bg-primary text-white text-center">
                        {{ $team['extra_team_score'] ?? 0 }}
                    </td>
                @endif
                <td class="py-4 px-6 font-semibold bg-primary text-white text-center">
                    {{ $team['final_team_score'] ?? 0 }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
