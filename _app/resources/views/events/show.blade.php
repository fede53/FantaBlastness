<x-app-layout>

    <x-slot name="breadcrumbs">
        <li><a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a></li>
        <span class="mx-2">/</span>
        <li>{{ $event['name'] }}</li>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (!$event['can_partecipate'] || $event['haveATeam'])
                @include('events.partials.partecipate')
            @else
                @include('events.partials.eventdetail')
            @endif
        </div>
    </div>

</x-app-layout>
