<x-app-layout>
    <x-slot name="breadcrumbs">
        <li><a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a></li>
        <span class="mx-2">/</span>
        <li>Create Your Team</li>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 ">

            <!-- Titolo dell'evento -->
            <div class="flex justify-between flex-wrap gap-4">
                <h2 class="text-2xl font-semibold text-white">{{ $event['name'] }}</h2>
                <div class="text text-s">{!! $event['instructions'] !!}</div>
            </div>

            <div class="flex gap-8"> <!-- Struttura a due colonne -->

                <!-- Tooltip per il grafico delle caratteristiche di un singolo membro -->
                <div id="tooltip-container" class="tooltip-container hidden">
                    <canvas id="memberRadarChart" width="300" height="300"></canvas>
                </div>

                <!-- Colonna sinistra con membri selezionabili -->
                <form action="{{ route('teams.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="event_id" value="{{ $event['id'] }}">



                    <div class="flex justify-between gap-6 relative">

                        <div class="w-9/12 p-4 sm:p-8 bg-dark-100 shadow sm:rounded-lg min-890">

                        <!-- Input per il nome della squadra -->
                        <div class="mb-8 ">
                            <label for="name" class="block text-white font-semibold">{{ __('Nome squadra') }}</label>
                            <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md shadow-sm" placeholder="Inserisci nome della squadra" required>
                            @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                            <div class="flex flex-wrap gap-4" id="members-list">

                                <div class="members_filters w-full flex flex-end">
                                    <div class="members_filters__text font-semibold">Filtra per delfini:</div>

                                    <input type="range" id="costRange" min="0" max="{{ $maxCost }}" value="{{ $maxCost }}" step="1" class="w-1/2">
                                    <span id="costRangeValue" class="text-white ml-2">{{ $maxCost }}</span>
                                </div>

                                @foreach ($members as $member)
                                    <div class="shadow-lg rounded-lg overflow-hidden p-4 w-3/12 relative member-box bg-dark" id="member-{{ $member['id'] }}" data-characteristics='@json($member["characteristics"])'>
                                        <div class="absolute top-2 right-2 cursor-pointer captain-star-container" data-member-id="{{ $member['id'] }}">
                                            <svg class="captain-star w-6 h-6 text-white" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                <path d="M12 17.75l-6.172 3.247 1.18-6.88-5-4.873 6.91-1.004L12 .75l3.082 6.54 6.91 1.004-5 4.873 1.18 6.88z"/>
                                            </svg>
                                        </div>

                                        <input type="radio" name="captain" value="{{ $member['id'] }}" class="captain-radio" id="captain-{{ $member['id'] }}" style="opacity: 0; position: absolute;">
                                        <img src="{{ asset('storage/' . $member['thumbnail']) }}" alt="Member Image" class="w-full h-40 object-contain rounded member-image">
                                        <div class="name-full">
                                            <h4 class="text-lg w-full font-semibold text-white mt-2 member-name">{{ $member['name'] }}</h4>
                                            <h5 class="text-xs w-full text-white member-name">{{ ($member['fantaname'] != '') ? $member['fantaname'] : ''}}</h5>
                                        </div>
                                        <div class="flex items-center gap-2 mt-2 member-price bg-dark-100">
                                            <h5 class="text-lg text-white member-cost" data-cost="{{ $member['cost'] }}">{{ $member['cost'] }}</h5>
                                            <!-- Icona di un delfino dorato -->
                                            <div class="dolphin">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Pro 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2024 Fonticons, Inc.--><path d="M80 160c0 13.3 2.3 26 6.5 37.8c3.9 10.8-.4 22.8-10.2 28.7L56.8 238.2 44.7 217.9l12.1 20.2c-5.5 3.3-8.8 9.2-8.8 15.6c0 10.1 8.2 18.2 18.2 18.2L192 272l24 0 32.9 0c6.3 0 12.3 2.5 16.8 6.8L304 316.3l0-20.3c0-13.3 10.7-24 24-24l16 0c48.6 0 88 39.4 88 88l0 8c0 .9 0 1.8 0 2.6c19.9-21.2 32-49.6 32-81c0-60.8-22.4-116.2-59.4-158.7c-7.2-8.2-7.9-20.3-1.7-29.3c10.4-15.3 23.4-35.5 31.4-51.9c-27.3 4-58.4 14.3-84.2 24.6c-6.4 2.5-13.5 2.2-19.6-.8C297.9 57.2 261.2 48 222.3 48L192 48C130.1 48 80 98.1 80 160zM277.7 408l66.3 0c22.1 0 40-17.9 40-40l0-8c0-19.4-13.7-35.5-32-39.2l0 1.9c0 25-20.3 45.3-45.3 45.3c-11.8 0-23.2-4.6-31.7-12.9L239.1 320 216 320l-24 0L66.2 320C29.6 320 0 290.4 0 253.8C0 230.5 12.2 209 32.1 197l3.7-2.2C33.3 183.6 32 171.9 32 160C32 71.6 103.6 0 192 0l30.3 0C265 0 305.6 9.3 342.2 25.9C374.9 13.5 418.7 0 456 0c7.8 0 17.9 2.4 25.2 11.3c6.7 8.1 7.5 17.4 7.3 23.2c-.4 11.3-5.2 23.4-9.7 33.1c-7 15-17.1 31.8-26.4 46.2C489.8 162.5 512 223.5 512 289.7C512 381.5 437.5 456 345.7 456l-1.7 0-66.3 0c-19.9 34.5-56.8 56-97 56l-4.7 0c-5.5 0-10.7-2.9-13.6-7.6s-3.2-10.6-.7-15.6L190.1 432l-28.4-56.8c-2.5-5-2.2-10.9 .7-15.6s8.1-7.6 13.6-7.6l4.7 0c40.2 0 77.1 21.5 97 56zM136 160a24 24 0 1 1 48 0 24 24 0 1 1 -48 0z"/></svg>
                                            </div>
                                        </div>
                                        <input type="checkbox" name="team[{{ $member['id'] }}]" value="1" class="member-checkbox">
                                        <input type="hidden" name="cost[{{ $member['id'] }}]" value="{{ $member['cost'] }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="w-3/12 p-4 pt-0 sm:pr-8 sm:pb-8 sm:pl-8 flex  flex-col items-center sticky">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 id="total_dolphins" class="text-2xl font-semibold text-white">Budget: {{ $event['dolphins'] }}</h3>
                                <div class="dolphin">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Pro 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2024 Fonticons, Inc.--><path d="M80 160c0 13.3 2.3 26 6.5 37.8c3.9 10.8-.4 22.8-10.2 28.7L56.8 238.2 44.7 217.9l12.1 20.2c-5.5 3.3-8.8 9.2-8.8 15.6c0 10.1 8.2 18.2 18.2 18.2L192 272l24 0 32.9 0c6.3 0 12.3 2.5 16.8 6.8L304 316.3l0-20.3c0-13.3 10.7-24 24-24l16 0c48.6 0 88 39.4 88 88l0 8c0 .9 0 1.8 0 2.6c19.9-21.2 32-49.6 32-81c0-60.8-22.4-116.2-59.4-158.7c-7.2-8.2-7.9-20.3-1.7-29.3c10.4-15.3 23.4-35.5 31.4-51.9c-27.3 4-58.4 14.3-84.2 24.6c-6.4 2.5-13.5 2.2-19.6-.8C297.9 57.2 261.2 48 222.3 48L192 48C130.1 48 80 98.1 80 160zM277.7 408l66.3 0c22.1 0 40-17.9 40-40l0-8c0-19.4-13.7-35.5-32-39.2l0 1.9c0 25-20.3 45.3-45.3 45.3c-11.8 0-23.2-4.6-31.7-12.9L239.1 320 216 320l-24 0L66.2 320C29.6 320 0 290.4 0 253.8C0 230.5 12.2 209 32.1 197l3.7-2.2C33.3 183.6 32 171.9 32 160C32 71.6 103.6 0 192 0l30.3 0C265 0 305.6 9.3 342.2 25.9C374.9 13.5 418.7 0 456 0c7.8 0 17.9 2.4 25.2 11.3c6.7 8.1 7.5 17.4 7.3 23.2c-.4 11.3-5.2 23.4-9.7 33.1c-7 15-17.1 31.8-26.4 46.2C489.8 162.5 512 223.5 512 289.7C512 381.5 437.5 456 345.7 456l-1.7 0-66.3 0c-19.9 34.5-56.8 56-97 56l-4.7 0c-5.5 0-10.7-2.9-13.6-7.6s-3.2-10.6-.7-15.6L190.1 432l-28.4-56.8c-2.5-5-2.2-10.9 .7-15.6s8.1-7.6 13.6-7.6l4.7 0c40.2 0 77.1 21.5 97 56zM136 160a24 24 0 1 1 48 0 24 24 0 1 1 -48 0z"/></svg>
                                </div>
                                <h3 id="selected_count" class="text-xl font-semibold text-white ml-4">Selezionati: 0</h3>
                            </div>
                            <canvas id="teamRadarChart" width="100%" height="100%"></canvas>
                        </div>

                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6">
                        <button type="submit" id="submit-team-btn" class="bg-primary hover:bg-primary text-white font-semibold py-2 px-4 rounded" style="display: none;">
                            Save Team
                        </button>
                    </div>
                </form>

            </div>

        </div>
    </div>

    <style>
        .tooltip-container {
            position: absolute;
            background: rgba(255, 255, 255);
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            pointer-events: none;
            width: fit-content;
            height: fit-content;

        }

        canvas#memberRadarChart {
            width: 250px !important;
            height: fit-content !important;
            padding: 0;
            margin: 0;
        }

        h3#selected_count {
            width: 100%;
            margin: 0;
        }
        .hidden {
            display: none;
        }
    </style>

    @push('scripts')

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const checkboxes = document.querySelectorAll('.member-checkbox');
                const radios = document.querySelectorAll('.captain-radio');
                const submitButton = document.getElementById('submit-team-btn');
                const totalDolphinsElement = document.getElementById('total_dolphins');
                const selectedCountElement = document.getElementById('selected_count');
                let totalDolphins = parseFloat(totalDolphinsElement.innerText.replace(/[^\d.-]/g, ''));
                const maxSelection = 8;
                let currentCaptain = null;

                // Team Radar Chart for total characteristics
                const ctx = document.getElementById('teamRadarChart').getContext('2d');
                /* let radarChart = new Chart(ctx, {
                    type: 'radar',
                    data: {
                        labels:  ['pazzia', 'alcolismo', 'resistenza', 'socialita', 'seduzione', 'professionalita'],
                        datasets: [{
                            label: 'Team Characteristics',
                            data: [0, 0, 0, 0, 0],
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
                }); */
                let radarChart = new Chart(ctx, {
                    type: 'radar',
                    data: {
                        labels: ['pazzia', 'alcolismo', 'resistenza', 'socialita', 'seduzione', 'professionalita'],
                        datasets: [{
                            label: 'Team Characteristics',
                            data: [0, 0, 0, 0, 0],
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
                                    stepSize: 2,
                                    display: false
                                },
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.2)'  // Colore delle linee di griglia per un contrasto più leggero
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
                                    color: 'rgba(255, 255, 255, 0.85)' // Colore della legenda (se abilitata)
                                },
                                display: false
                            }
                        },
                        responsive: true,
                    }
                });


                // Seleziona il range e il valore mostrato
                const costRange = document.getElementById('costRange');
                const costRangeValue = document.getElementById('costRangeValue');

                // Event listener per aggiornare il valore mostrato e filtrare i membri
                costRange.addEventListener('input', function () {
                    // Aggiorna il valore mostrato
                    costRangeValue.innerText = costRange.value;

                    // Filtra i membri in base al valore del range
                    filterMembersByCost(costRange.value);
                });

                function filterMembersByCost(maxCost) {
                    document.querySelectorAll('.member-box').forEach(memberBox => {
                        const memberCost = parseFloat(memberBox.querySelector('.member-cost').getAttribute('data-cost'));

                        if (memberCost <= maxCost) {
                            memberBox.style.display = 'flex'; // Mostra il membro se il costo è <= al valore selezionato
                        } else {
                            memberBox.style.display = 'none'; // Nascondi il membro se il costo è > al valore selezionato
                        }
                    });
                }

                // Inizializza la visualizzazione con il valore massimo predefinito
                filterMembersByCost(costRange.value);



                // Tooltip Radar Chart for individual characteristics
                const memberRadarCanvas = document.getElementById('memberRadarChart').getContext('2d');
                const tooltipContainer = document.getElementById('tooltip-container');
                let memberRadarChart = new Chart(memberRadarCanvas, {
                    type: 'radar',
                    data: {
                        labels: ['pazzia', 'alcolismo', 'resistenza', 'socialita', 'seduzione', 'professionalita'],
                        datasets: [{
                            label: 'Member Characteristics',
                            data: [0, 0, 0, 0, 0],
                            borderWidth: 1,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        scales: {
                            r: {
                                min: 0,
                                max: 10,
                                ticks: {
                                    stepSize: 2
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

                function updateMemberRadarChart(characteristics) {
                    memberRadarChart.data.datasets[0].data = [
                        characteristics.pazzia,
                        characteristics.alcolismo,
                        characteristics.resistenza,
                        characteristics.socialita,
                        characteristics.seduzione,
                        characteristics.professionalita,
                    ];
                    memberRadarChart.update();
                }

                document.querySelectorAll('.member-box').forEach(memberBox => {
                    memberBox.addEventListener('mouseover', function (event) {
                        const characteristics = JSON.parse(memberBox.getAttribute('data-characteristics'));
                        updateMemberRadarChart(characteristics);

                        // Posiziona il tooltip vicino al cursore
                        tooltipContainer.style.left = event.pageX + 15 + 'px';
                        tooltipContainer.style.top = event.pageY + 15 + 'px';
                        tooltipContainer.classList.remove('hidden');
                    });

                    memberBox.addEventListener('mousemove', function (event) {
                        // Aggiorna la posizione del tooltip mentre si muove il mouse
                        tooltipContainer.style.left = event.pageX + 15 + 'px';
                        tooltipContainer.style.top = event.pageY + 15 + 'px';
                    });

                    memberBox.addEventListener('mouseleave', function () {
                        tooltipContainer.classList.add('hidden');
                    });
                });

                function updateRadarChart() {
                    let totalCharacteristics = {
                        pazzia: 0,
                        alcolismo: 0,
                        resistenza: 0,
                        socialita: 0,
                        seduzione: 0,
                        professionalita: 0
                    };
                    let selectedCount = 0;

                    checkboxes.forEach(checkbox => {
                        if (checkbox.checked) {
                            const memberBox = checkbox.closest('.member-box');
                            const characteristics = JSON.parse(memberBox.getAttribute('data-characteristics'));
                            totalCharacteristics.pazzia += parseInt(characteristics.pazzia);
                            totalCharacteristics.alcolismo += parseInt(characteristics.alcolismo);
                            totalCharacteristics.resistenza += parseInt(characteristics.resistenza);
                            totalCharacteristics.socialita += parseInt(characteristics.socialita);
                            totalCharacteristics.seduzione += parseInt(characteristics.seduzione);
                            totalCharacteristics.professionalita += parseInt(characteristics.professionalita);
                            selectedCount++;
                        }
                    });

                    if (selectedCount > 0) {
                        radarChart.data.datasets[0].data = [
                            Math.round(totalCharacteristics.pazzia / selectedCount),
                            Math.round(totalCharacteristics.alcolismo / selectedCount),
                            Math.round(totalCharacteristics.resistenza / selectedCount),
                            Math.round(totalCharacteristics.socialita / selectedCount),
                            Math.round(totalCharacteristics.seduzione / selectedCount),
                            Math.round(totalCharacteristics.professionalita / selectedCount)
                        ];
                    } else {
                        radarChart.data.datasets[0].data = [0, 0, 0, 0, 0, 0];
                    }
                    radarChart.update();
                }

                function updateTotalCost() {
                    let selectedMembers = 0;
                    let totalCost = totalDolphins;
                    let captainSelected = false;

                    checkboxes.forEach((checkbox, index) => {
                        const memberBox = checkbox.closest('.member-box');
                        const memberCost = parseFloat(memberBox.querySelector('.member-cost').getAttribute('data-cost'));
                        const starIcon = memberBox.querySelector('.captain-star');
                        const radio = radios[index];

                        if (checkbox.checked) {
                            selectedMembers++;
                            totalCost -= memberCost;

                            radios[index].style.display = 'block';
                            memberBox.classList.add('border-primary');

                            if (radio.checked) {
                                captainSelected = true;
                                starIcon.classList.replace('text-white', 'text-yellow-400');
                            }
                        } else {
                            radios[index].style.display = 'none';
                            memberBox.classList.remove('border-primary');
                            starIcon.classList.replace('text-yellow-400', 'text-white');

                            if (radio.checked) {
                                radio.checked = false;
                                currentCaptain = null;
                            }
                        }
                    });

                    // Aggiorna il totale del budget
                    if (!isNaN(totalCost)) {
                        totalDolphinsElement.innerText = `Budget: ${Math.round(totalCost)}`;
                    }

                    // Aggiorna il numero di membri selezionati
                    selectedCountElement.innerText = `Selected: ${selectedMembers}`;

                    // Controlla le condizioni per visualizzare il pulsante di submit
                    if (selectedMembers === maxSelection && captainSelected && totalCost >= 0) {
                        submitButton.style.display = 'flex';
                    } else {
                        submitButton.style.display = 'none';
                    }

                    // Nascondi i membri non selezionati se si raggiunge il numero massimo
                    if (selectedMembers >= maxSelection) {
                        checkboxes.forEach(checkbox => {
                            if (!checkbox.checked) {
                                checkbox.closest('.member-box').style.display = 'none';
                            }
                        });
                    } else {
                        checkboxes.forEach(checkbox => {
                            checkbox.closest('.member-box').style.display = 'flex';
                        });
                    }

                    updateRadarChart();
                }

                // Gestisci il click sulla stella per selezionare il capitano
                document.querySelectorAll('.captain-star-container').forEach(starContainer => {
                    starContainer.addEventListener('click', function () {
                        const memberId = this.getAttribute('data-member-id');
                        const radio = document.getElementById('captain-' + memberId);

                        if (currentCaptain && currentCaptain !== radio) {
                            currentCaptain.checked = false;
                            const prevStarIcon = currentCaptain.closest('.member-box').querySelector('.captain-star');
                            prevStarIcon.classList.replace('text-yellow-400', 'text-white');
                        }

                        radio.checked = true;
                        currentCaptain = radio;

                        const starIcon = this.querySelector('.captain-star');
                        starIcon.classList.replace('text-white', 'text-yellow-400');

                        updateTotalCost();
                    });
                });

                // Aggiungi gli event listeners per i checkbox
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', updateTotalCost);
                });

                // Aggiungi gli event listeners per i radio
                radios.forEach(radio => {
                    radio.addEventListener('change', function () {
                        updateTotalCost();
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
