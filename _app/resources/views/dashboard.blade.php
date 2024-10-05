@php
    use Carbon\Carbon;
@endphp

<x-app-layout>
    <x-slot name="breadcrumbs">
        <li>Dashboard</li>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <h2 class="text-2xl font-semibold text-white mb-6">Upcoming Events</h2>

            <!-- Lista degli eventi -->
            <div class="flex flex-wrap space-y-6"> <!-- Flex container per centrare le card -->
                @forelse ($events as $event)
                    <!-- Card per ogni evento al 60% della larghezza -->
                    <div class="bg-dark-100 shadow-lg rounded-lg overflow-hidden w-3/5">

                        @if($event['image'])
                            <img src="{{ asset('storage/' . $event['image']) }}" alt="Event Image" class="w-full h-96 object-cover">
                        @endif

                        <div class="p-6">
                            <h3 class="text-xl font-bold text-white mb-2">{{ $event['name'] }}</h3>
                            <div class="my-4">
                                <p class="text-white">
                                    {!! Str::limit($event['description'], 200) !!} <!-- Descrizione con limite a 150 caratteri -->
                                </p>
                            </div>

                            @if (Carbon::parse($event['date_phase_1'])->greaterThanOrEqualTo(Carbon::now()) &&
                                    Carbon::parse($event['date_phase_2'])->isFuture() && !$event['eventScoreCheck'])
                                <div class="countdown text-white font-semibold my-4" data-countdown="{{ $event['date_phase_1'] }}" data-msg="Ora puoi compilare i tuoi bonus e malus!">
                                    Potrai compilare i tuoi bonus e malus tra: <span></span>
                                </div>
                            @elseif (Carbon::parse($event['date_phase_1'])->lessThan(Carbon::now()) &&
                                    Carbon::parse($event['date_phase_2'])->isFuture())
                                <div class="countdown text-white font-semibold my-4" data-countdown="{{ $event['date_phase_2'] }}" data-msg="Visualizza i risultati!">
                                    I risultati verranno pubblicati tra: <span></span>
                                </div>
                            @endif

                            @if ($event['can_partecipate'] && !$event['haveATeam'])
                                <div class="countdown text-white font-semibold my-4" data-countdown="{{ $event['date_for_partecipate'] }}" data-msg="Evento iniziato">
                                    Partecipa entro: <span></span>
                                </div>
                            @endif



                            @if (\Carbon\Carbon::parse($event['date_phase_2'])->lessThan(\Carbon\Carbon::now()))
                                <a href="{{ route('events.show', $event['id']) }}" class="inline-block bg-primary text-white font-semibold py-2 px-4 rounded">
                                    Scopri il vincitore
                                </a>
                            @else
                                <a href="{{ route('events.show', $event['id']) }}" class="inline-block bg-primary text-white font-semibold py-2 px-4 rounded">
                                    Show Event
                                </a>

                                @if (\Carbon\Carbon::parse($event['date_phase_1'])->lessThan(\Carbon\Carbon::now()) && \Carbon\Carbon::parse($event['date_phase_2'])->isFuture() && !$event['eventScoreCheck'])
                                    <a href="{{ route('score.create', $event['id']) }}" class="inline-block bg-primary text-white font-semibold py-2 px-4 rounded">
                                        Compila i tuoi bonus e malus
                                    </a>
                                @endif
                            @endif


                        </div>
                    </div>
                @empty
                    <!-- Messaggio se non ci sono eventi -->
                    <p class="text-white text-center">No events available.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
