<x-app-layout>
    <x-slot name="breadcrumbs">
        <li>Dashboard</li>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Upcoming Events</h2>

            <!-- Lista degli eventi -->
            <div class="flex flex-wrap space-y-6"> <!-- Flex container per centrare le card -->
                @forelse ($events as $event)
                    <!-- Card per ogni evento al 60% della larghezza -->
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden w-3/5">

                        @if($event['image'])
                            <img src="{{ asset('storage/' . $event['image']) }}" alt="Event Image" class="w-full h-96 object-cover">
                        @endif

                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $event['name'] }}</h3>
                            <p class="text-gray-600 mb-4">
                                {!! Str::limit($event['description'], 150) !!} <!-- Descrizione con limite a 150 caratteri -->
                            </p>
                            <p class="text-gray-500 text-sm mb-4">
                                <strong>Date:</strong> {{ \Carbon\Carbon::parse($event['date_for_partecipate'])->format('M d, Y H:i') }}
                            </p>

                            <!-- Countdown -->
                            @if ($event['can_partecipate'] && !$event['haveATeam'])
                                <div id="countdown-{{ $event['id'] }}" class="text-gray-700 font-semibold mb-4">
                                    Partecipa entro: <span></span>
                                </div>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        var countdownElement = document.getElementById('countdown-{{ $event['id'] }}').querySelector('span');
                                        var eventDate = new Date('{{ $event['date_for_partecipate'] }}').getTime();

                                        var countdownInterval = setInterval(function() {
                                            var now = new Date().getTime();
                                            var distance = eventDate - now;

                                            if (distance < 0) {
                                                clearInterval(countdownInterval);
                                                countdownElement.innerHTML = "Event has started";
                                            } else {
                                                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                                                countdownElement.innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
                                            }
                                        }, 1000);
                                    });
                                </script>
                            @endif

                            <a href="{{ route('events.show', $event['id']) }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                                Show Event
                            </a>
                        </div>
                    </div>
                @empty
                    <!-- Messaggio se non ci sono eventi -->
                    <p class="text-gray-500 text-center">No events available.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
