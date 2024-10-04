

            <!-- Titolo dell'evento e Countdown -->
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-semibold text-white">{{ $event['name'] }}</h2>

                
            </div>

            <!-- Countdown per diverse fasi -->
            @if (\Carbon\Carbon::parse($event['date_phase_1'])->greaterThanOrEqualTo(\Carbon\Carbon::now()) &&
                        \Carbon\Carbon::parse($event['date_phase_2'])->isFuture())
                <div class="countdown text-white font-semibold" data-countdown="{{ $event['date_phase_1'] }}" data-msg="Ora puoi compilare i tuoi bonus e malus!">
                    Potrai compilare i tuoi bonus e malus tra: <span></span>
                </div>
            @elseif (\Carbon\Carbon::parse($event['date_phase_1'])->lessThan(\Carbon\Carbon::now()) &&
                    \Carbon\Carbon::parse($event['date_phase_2'])->isFuture())
                <div class="countdown text-white font-semibold" data-countdown="{{ $event['date_phase_2'] }}" data-msg="Visualizza i risultati!">
                    I risultati verranno pubblicati tra: <span></span>
                </div>
            @endif
            
            @if (\Carbon\Carbon::parse($event['date_phase_1'])->lessThan(\Carbon\Carbon::now()) && \Carbon\Carbon::parse($event['date_phase_2'])->isFuture())
                <div class="flex ">
                    <a href="{{ route('score.create', $event['id']) }}" class="px-3 bg-primary hover:bg-primary text-white font-bold py-4 px-8 rounded-lg text-xl shadow-lg transition duration-300 ease-in-out">
                        Compila i tuoi bonus e malus
                    </a>
                </div>
            @endif

        
            <!-- Navigazione a Tab -->
            <div class="flex space-x-4 mb-10-2 pt-10">
                <button class="tab-button px-6 py-3 font-semibold text-xl text-white bg-dark border-b-2 transition duration-300 active-tab" data-target="team-tab">
                    La tua Squadra
                </button>
                <button class="tab-button px-6 py-3 font-semibold text-xl text-white bg-dark border-b-2 transition duration-300" data-target="other-teams-tab">
                    Altre Squadre
                </button>

            </div>

        

            <!-- Contenuto dei Tab -->
            <div class="tab-content hidden" id="team-tab">
                <!-- Include per la lista della tua squadra -->
                @include('events.partials.teamlist', ['teams' => $teams->filter(fn($team) => $team['user']['id'] === auth()->user()->id)])
            </div>

            <div class="tab-content hidden" id="other-teams-tab">
                <!-- Include per la lista delle altre squadre -->
                @include('events.partials.teamlist', ['teams' => $teams->filter(fn($team) => $team['user']['id'] !== auth()->user()->id)])
            </div>
          
            


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            // Aggiungi listener agli elementi dei pulsanti
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Nascondi tutti i contenuti
                    tabContents.forEach(content => content.classList.add('hidden'));

                    // Rimuovi classi attive da tutti i pulsanti
                    tabButtons.forEach(btn => {
                        btn.classList.remove('bg-primary', 'active-tab');
                        btn.classList.add('bg-primary');
                    });

                    // Mostra il contenuto del tab attivo
                    const target = this.getAttribute('data-target');
                    document.getElementById(target).classList.remove('hidden');

                    // Cambia lo stato del pulsante cliccato
                    this.classList.add('bg-primary', 'active-tab');
                });
            });

            // Mostra il primo tab di default
            if (tabButtons.length > 0) {
                tabButtons[0].click();
            }
        });
    </script>

    <style>
        .tab-button {
            margin-right: 8px; /* Extra spacing between buttons */
        }

        .tab-button.active-tab {

        }

        .tab-content {
            padding-top: 1rem; /* Add some space between tabs and content */
        }
    </style>
