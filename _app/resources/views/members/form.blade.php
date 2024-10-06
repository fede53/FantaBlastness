<x-app-layout>

    <x-slot name="breadcrumbs">
        <li><a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a></li>
        <span class="mx-2">/</span>
        <li><a href="{{ route('members.index') }}" class="hover:underline">Members</a></li>
        <span class="mx-2">/</span>
        <li>{{ isset($member) ? 'Edit member' : 'Create member' }}</li>
    </x-slot>

    <div class="py-12 pb-32"> <!-- Aggiungi padding bottom per evitare la sovrapposizione -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <x-profile-header
                title="Members"
                description="Manage members and their roles."
                :links="[
                        ['href' => route('members.index'), 'text' => 'Member List']
                    ]"
            />

            <!-- Layout a due colonne -->
            <form action="{{ isset($member) ? route('members.update', $member['id']) : route('members.store') }}" method="POST" enctype="multipart/form-data">

                @csrf
                @if(isset($member))
                    @method('PUT')
                @endif

                <div class="flex justify-between gap-6">
                    <!-- Colonna principale (sinistra) -->
                    <div class="w-9/12 p-4 sm:p-8 bg-dark-100 shadow sm:rounded-lg">
                        <!-- Nome -->
                        <div class="mb-4">
                            <label for="name" class="block text-white font-semibold">{{ __('Name') }}</label>
                            <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md shadow-sm" value="{{ old('name', $member['name'] ?? '') }}" required>
                            @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="block text-white font-semibold">{{ __('email') }}</label>
                            <input type="text" name="email" id="email" class="mt-1 block w-full rounded-md shadow-sm" value="{{ old('email', $member['email'] ?? '') }}" required>
                            @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Fantaname -->
                        <div class="mb-4">
                            <label for="fantaname" class="block text-white font-semibold">{{ __('Fantaname') }}</label>
                            <input type="text" name="fantaname" id="fantaname" class="mt-1 block w-full rounded-md shadow-sm" value="{{ old('fantaname', $member['fantaname'] ?? '') }}">
                            @error('fantaname')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="pazzia" class="block text-white font-semibold">{{ __('Pazzia') }}: <span id="pazzia_value">{{ old('characteristics.pazzia', $member['characteristics']['pazzia'] ?? 5) }}</span></label>
                            <input type="range" min="0" max="10" name="characteristics[pazzia]" class="mt-1 block w-full rounded-md shadow-sm" value="{{ old('characteristics.pazzia', $member['characteristics']['pazzia'] ?? 5) }}" oninput="updateChart()" required>
                            @error('characteristics.pazzia')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="alcolismo" class="block text-white font-semibold">{{ __('Alcolismo') }}: <span id="alcolismo_value">{{ old('characteristics.alcolismo', $member['characteristics']['alcolismo'] ?? 5) }}</span></label>
                            <input type="range" min="0" max="10" name="characteristics[alcolismo]" class="mt-1 block w-full rounded-md shadow-sm" value="{{ old('characteristics.alcolismo', $member['characteristics']['alcolismo'] ?? 5) }}" oninput="updateChart()" required>
                            @error('characteristics.alcolismo')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="resistenza" class="block text-white font-semibold">{{ __('Resistenza') }}: <span id="resistenza_value">{{ old('characteristics.resistenza', $member['characteristics']['resistenza'] ?? 5) }}</span></label>
                            <input type="range" min="0" max="10" name="characteristics[resistenza]" class="mt-1 block w-full rounded-md shadow-sm" value="{{ old('characteristics.resistenza', $member['characteristics']['resistenza'] ?? 5) }}" oninput="updateChart()" required>
                            @error('characteristics.resistenza')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="socialita" class="block text-white font-semibold">{{ __('Socialita') }}: <span id="socialita_value">{{ old('characteristics.socialita', $member['characteristics']['socialita'] ?? 5) }}</span></label>
                            <input type="range" min="0" max="10" name="characteristics[socialita]" class="mt-1 block w-full rounded-md shadow-sm" value="{{ old('characteristics.socialita', $member['characteristics']['socialita'] ?? 5) }}" oninput="updateChart()" required>
                            @error('characteristics.socialita')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="seduzione" class="block text-white font-semibold">{{ __('Seduzione') }}: <span id="seduzione_value">{{ old('characteristics.seduzione', $member['characteristics']['seduzione'] ?? 5) }}</span></label>
                            <input type="range" min="0" max="10" name="characteristics[seduzione]" class="mt-1 block w-full rounded-md shadow-sm" value="{{ old('characteristics.seduzione', $member['characteristics']['seduzione'] ?? 5) }}" oninput="updateChart()" required>
                            @error('characteristics.seduzione')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="professionalita" class="block text-white font-semibold">{{ __('Professionalita') }}: <span id="professionalita_value">{{ old('characteristics.professionalita', $member['characteristics']['professionalita'] ?? 5) }}</span></label>
                            <input type="range" min="0" max="10" name="characteristics[professionalita]" class="mt-1 block w-full rounded-md shadow-sm" value="{{ old('characteristics.professionalita', $member['characteristics']['professionalita'] ?? 5) }}" oninput="updateChart()" required>
                            @error('characteristics.professionalita')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <!-- Colonna gestione immagine (destra) -->
                    <div class="w-3/12 p-4 pt-0 sm:pr-8 sm:pb-8 sm:pl-8 flex flex-col items-center">
                        <label for="image" class="block text-white font-semibold text-center">{{ __('Profile Picture') }}</label>
                        <div class="mt-4 relative flex justify-center">
                            <img id="image-preview" src="{{ isset($member) && $member['image'] ? asset('storage/' . $member['image']) : '' }}"
                                 alt=""
                                 class="w-24 h-24 rounded-full object-cover border"
                                 style="width: 150px; height: 150px;"
                            >
                            <button type="button" id="delete-image" class="{{ !isset($member['image']) ? 'hidden' : '' }} remove-bonus-pre absolute top-2 right-2 bg-red-600 hover:bg-red-700 text-white flex items-center justify-center w-8 h-8 rounded-full">
                                X
                            </button>
                        </div>
                        <label for="image" class="bg-primary hover:bg-primary text-white font-semibold py-2 px-4 rounded cursor-pointer inline-block mt-4 text-center">
                            {{ __('Choose File') }}
                        </label>
                        <input type="hidden" name="image_deleted" id="image_deleted" value="0" class="hidden">
                        <input type="file" name="image" id="image" class="hidden" accept="image/*" onchange="previewImage(event)">

                        @error('image')
                        <p class="text-red-500 text-sm mt-1 text-center">{{ $message }}</p>
                        @enderror


                        <div>
                            <canvas id="myChart"></canvas>
                        </div>

                    </div>
                </div>

                <!-- Barra fissa con i pulsanti -->
                <div class="fixed bottom-0 left-0 right-0 bg-dark dark:bg-dark  py-3 px-6 flex justify-end">
                    <a href="{{ route('members.index') }}" class="bg-primary hover:bg-primary text-white font-semibold py-2 px-4 rounded mr-2">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="bg-primary hover:bg-primary text-white font-semibold py-2 px-4 rounded">
                        {{ isset($member) ? __('Update Member') : __('Create Member') }}
                    </button>
                </div>

            </form>

        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


        <script>
            // Definisci il contesto del canvas
            const ctx = document.getElementById('myChart');

            // Crea il grafico radar con i valori iniziali presi dai range
            /*const myChart = new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: ['pazzia', 'alcolismo', 'resistenza', 'socialita', 'seduzione', 'professionalita'],
                    datasets: [{
                        label: 'Characteristics',
                        data: [
                            document.querySelector("input[name='characteristics[pazzia]']").value,
                            document.querySelector("input[name='characteristics[alcolismo]']").value,
                            document.querySelector("input[name='characteristics[resistenza]']").value,
                            document.querySelector("input[name='characteristics[socialita]']").value,
                            document.querySelector("input[name='characteristics[seduzione]']").value,
                            document.querySelector("input[name='characteristics[professionalita]']").value
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        r: {
                            min: 0,  // Valore minimo della scala
                            max: 10, // Valore massimo della scala
                            ticks: {
                                stepSize: 1  // Step a 1 unità per i valori intermedi
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false // Nasconde la leggenda
                        }
                    }
                },

            });*/
            const myChart = new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: ['pazzia', 'alcolismo', 'resistenza', 'socialita', 'seduzione', 'professionalita'],
                    datasets: [{
                        label: 'Characteristics',
                        data: [
                            document.querySelector("input[name='characteristics[pazzia]']").value,
                            document.querySelector("input[name='characteristics[alcolismo]']").value,
                            document.querySelector("input[name='characteristics[resistenza]']").value,
                            document.querySelector("input[name='characteristics[socialita]']").value,
                            document.querySelector("input[name='characteristics[seduzione]']").value,
                            document.querySelector("input[name='characteristics[professionalita]']").value
                        ],
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
                            min: 0,  // Valore minimo della scala
                            max: 10, // Valore massimo della scala
                            ticks: {
                                stepSize: 1,
                                display: false
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.2)'  // Colore delle linee di griglia per non rendere il grafico troppo pesante
                            },
                            angleLines: {
                                color: 'rgba(255, 255, 255, 0.2)' // Linee angolari in un colore tenue per non distrarre dal contenuto
                            },
                            pointLabels: {
                                color: 'rgba(255, 255, 255, 0.85)' // Colore delle etichette dei punti per una buona visibilità
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: 'rgba(255, 255, 255, 0.85)' // Colore delle etichette della legenda (se presente) per renderle leggibili
                            },
                            display: false // Nasconde la legenda
                        }
                    },
                    responsive: true,
                }
            });




            // Funzione per aggiornare il grafico quando i valori cambiano
            function updateChart() {
                // Log dei valori dei range per il debug
                console.log('Pazzia:', document.querySelector("input[name='characteristics[pazzia]']").value);
                console.log('Alcolismo:', document.querySelector("input[name='characteristics[alcolismo]']").value);
                console.log('Resistenza:', document.querySelector("input[name='characteristics[resistenza]']").value);
                console.log('Socialita:', document.querySelector("input[name='characteristics[socialita]']").value);
                console.log('Seduzione:', document.querySelector("input[name='characteristics[seduzione]']").value);
                console.log('Professionalita:', document.querySelector("input[name='characteristics[professionalita]']").value);

                // Aggiorna gli span con i nuovi valori
                document.getElementById('pazzia_value').textContent = document.querySelector("input[name='characteristics[pazzia]']").value;
                document.getElementById('alcolismo_value').textContent = document.querySelector("input[name='characteristics[alcolismo]']").value;
                document.getElementById('resistenza_value').textContent = document.querySelector("input[name='characteristics[resistenza]']").value;
                document.getElementById('socialita_value').textContent = document.querySelector("input[name='characteristics[socialita]']").value;
                document.getElementById('seduzione_value').textContent = document.querySelector("input[name='characteristics[seduzione]']").value;
                document.getElementById('professionalita_value').textContent = document.querySelector("input[name='characteristics[professionalita]']").value;

                // Aggiorna i dati del grafico con i nuovi valori
                myChart.data.datasets[0].data = [
                    document.querySelector("input[name='characteristics[pazzia]']").value,
                    document.querySelector("input[name='characteristics[alcolismo]']").value,
                    document.querySelector("input[name='characteristics[resistenza]']").value,
                    document.querySelector("input[name='characteristics[socialita]']").value,
                    document.querySelector("input[name='characteristics[seduzione]']").value,
                    document.querySelector("input[name='characteristics[professionalita]']").value
                ];
                myChart.update();
            }

            // Funzione per visualizzare l'anteprima dell'immagine
            function previewImage(event) {
                var reader = new FileReader();
                reader.onload = function(){
                    var output = document.getElementById('image-preview');
                    output.src = reader.result;
                    output.classList.remove('hidden');
                    output.style.width = '150px';
                    output.style.height = '150px';
                };
                document.getElementById('delete-image').classList.remove('hidden');
                reader.readAsDataURL(event.target.files[0]);
            }

            // Funzione per cancellare l'immagine
            document.getElementById('delete-image')?.addEventListener('click', function() {
                document.getElementById('image-preview').src = '';
                document.getElementById('delete-image').classList.add('hidden');
                document.getElementById('image').value = '';
                document.getElementById('image_deleted').value = 1;
            });

            // Imposta i valori correnti negli span e nel grafico al caricamento della pagina
            document.addEventListener('DOMContentLoaded', function() {
                updateChart(); // Aggiorna il grafico e gli span con i valori iniziali
            });
        </script>
    @endpush

</x-app-layout>
