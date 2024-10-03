<x-app-layout>
    <x-slot name="breadcrumbs">
        <li><a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a></li>
        <span class="mx-2">/</span>
        <li>Create Your Team</li>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Titolo dell'evento -->
            <div class="flex justify-between flex-wrap gap-4">
                <h2 class="text-2xl font-semibold text-gray-800">{{ $event['name'] }}</h2>
            </div>

            <div class="flex gap-8"> <!-- Struttura a due colonne -->

                <!-- Tooltip per il grafico delle caratteristiche di un singolo membro -->
                <div id="tooltip-container" class="tooltip-container hidden">
                    <canvas id="memberRadarChart" width="240" height="200"></canvas>
                </div>

                <!-- Colonna sinistra con membri selezionabili -->
                <form action="{{ route('teams.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="event_id" value="{{ $event['id'] }}">

                    <!-- Input per il nome della squadra -->
                    <div class="mb-8">
                        <label for="name" class="block text-gray-700 font-semibold">{{ __('Team Name') }}</label>
                        <input type="text" name="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Enter team name" required>
                        @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-between gap-6 relative">

                        <div class="w-9/12 p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                            <div class="flex flex-wrap gap-4" id="members-list">
                                @foreach ($members as $member)
                                    <div class="bg-white shadow-lg rounded-lg overflow-hidden p-4 w-3/12 relative member-box" id="member-{{ $member['id'] }}" data-characteristics='@json($member["characteristics"])'>
                                        <div class="absolute top-2 right-2 cursor-pointer captain-star-container" data-member-id="{{ $member['id'] }}">
                                            <svg class="captain-star w-6 h-6 text-white" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                <path d="M12 17.75l-6.172 3.247 1.18-6.88-5-4.873 6.91-1.004L12 .75l3.082 6.54 6.91 1.004-5 4.873 1.18 6.88z"/>
                                            </svg>
                                        </div>

                                        <input type="radio" name="captain" value="{{ $member['id'] }}" class="captain-radio" id="captain-{{ $member['id'] }}" style="opacity: 0; position: absolute;">
                                        <img src="{{ asset('storage/' . $member['thumbnail']) }}" alt="Member Image" class="w-full h-40 object-contain rounded member-image">
                                        <h4 class="text-lg font-semibold text-gray-800 mt-2 member-name">{{ $member['name'] }}</h4>
                                        <div class="flex items-center gap-2 mt-2 member-price">
                                            <h5 class="text-lg text-gray-800 member-cost" data-cost="{{ $member['cost'] }}">{{ $member['cost'] }}</h5>
                                            <!-- Icona di un delfino dorato -->
                                            <img src="/assets/images/dolphin-icon.png" alt="Gold Dolphin" class="w-10 h-10">
                                        </div>
                                        <input type="checkbox" name="team[{{ $member['id'] }}]" value="1" class="member-checkbox">
                                        <input type="hidden" name="cost[{{ $member['id'] }}]" value="{{ $member['cost'] }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="w-3/12 p-4 pt-0 sm:pr-8 sm:pb-8 sm:pl-8 flex flex-col items-center sticky">
                            <div class="flex items-center gap-2">
                                <h3 id="total_dolphins" class="text-2xl font-semibold text-gray-800">Budget: {{ $event['dolphins'] }}</h3>
                                <img src="/assets/images/dolphin-icon.png" alt="Gold Dolphin" class="w-12 h-12">
                                <h3 id="selected_count" class="text-xl font-semibold text-gray-800 ml-4">Selected: 0</h3>
                            </div>
                            <canvas id="teamRadarChart" width="100%" height="400"></canvas>
                        </div>

                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6">
                        <button type="submit" id="submit-team-btn" class="bg-blast-600 hover:bg-blast-700 text-white font-semibold py-2 px-4 rounded" style="display: none;">
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
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            pointer-events: none;
            width: 240px;
            height: 200px;
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
                let radarChart = new Chart(ctx, {
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
                });

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
                            memberBox.classList.add('bg-blast-100');

                            if (radio.checked) {
                                captainSelected = true;
                                starIcon.classList.replace('text-white', 'text-yellow-400');
                            }
                        } else {
                            radios[index].style.display = 'none';
                            memberBox.classList.remove('bg-blast-100');
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
