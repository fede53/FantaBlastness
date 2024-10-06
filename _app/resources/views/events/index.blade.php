<x-app-layout>

    <x-slot name="breadcrumbs">
        <li><a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a></li>
        <span class="mx-2">/</span>
        <li>Events</li>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <x-profile-header
                title="Events"
                description="Manage events and their roles."
                :links="[
                        ['href' => route('events.create'), 'text' => 'Create Event']
                    ]"
            />

            <div class="p-4 sm:p-8 bg-dark-100 shadow sm:rounded-lg">

                <div class="overflow-x-auto">
                    <!-- Tabella degli utenti -->
                    <table id="sortable-table" class="min-w-full w-full table-auto border-collapse">
                        <thead class="bg-dark-100">
                        <tr>
                            <!-- Colonna della miniatura -->
                            <th class="w-1/12 px-4 py-3 text-left text-white font-bold bg-dark"></th>
                            <th data-column="name" data-order="asc" class="w-7/12 px-4 py-3 text-left text-white font-bold cursor-pointer bg-dark">
                                {{ __('Name') }}
                            </th>
                            <th data-column="dolphins" data-order="asc" class="w-3/12 px-4 py-3 text-left text-white font-bold cursor-pointer bg-dark">
                                {{ __('Dolphins') }}
                            </th>
                            <th class="w-1/12 px-4 py-3 text-left text-white font-bold bg-dark">{{ __('Actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($events as $event)
                            <tr class="bg-dark-100 hover:bg-dark-100 transition">
                                <!-- Colonna della miniatura -->
                                <td class="px-4 py-3 text-white">
                                    @if($event['thumbnail'])
                                        <img src="{{ asset('storage/' . $event['thumbnail']) }}" alt="Thumbnail" class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <!-- Icona di default se non c'è miniatura -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 11c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z" />
                                            <path d="M12 14c-3.31 0-6 2.69-6 6v2h12v-2c0-3.31-2.69-6-6-6z" />
                                        </svg>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-white">{{ $event['name'] }}</td>
                                <td class="px-4 py-3 text-white">{{ $event['dolphins'] }}</td>
                                <td class="px-4 py-3 space-x-2">

                                    <div class="flex items-center justify-center">
                                        <!-- Pulsante Modifica -->
                                        <a href="{{ route('events.edit', $event['id']) }}" class="text-white hover:text-white button-action" title="{{ __('Edit') }}">
                                            <!-- Icona Modifica (matita) -->
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-5m-3-10.414a2 2 0 112.828 2.828L11.414 10.828a2 2 0 01-.828.414L8 12l.758-2.586a2 2 0 01.414-.828l5.828-5.828z" />
                                            </svg>
                                        </a>

                                        <!-- Pulsante Cancella -->
                                        <form class="flex items-center" action="{{ route('events.destroy', $event['id']) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this event?') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 button-action" title="{{ __('Delete') }}">
                                                <!-- Icona Cancella (cestino) -->
                                                <svg style="fill: red; height: 17px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Pro 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2024 Fonticons, Inc.--><path d="M164.2 39.5L148.9 64l150.3 0L283.8 39.5c-2.9-4.7-8.1-7.5-13.6-7.5l-92.5 0c-5.5 0-10.6 2.8-13.6 7.5zM311 22.6L336.9 64 384 64l32 0 16 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-16 0 0 336c0 44.2-35.8 80-80 80l-224 0c-44.2 0-80-35.8-80-80L32 96 16 96C7.2 96 0 88.8 0 80s7.2-16 16-16l16 0 32 0 47.1 0L137 22.6C145.8 8.5 161.2 0 177.7 0l92.5 0c16.6 0 31.9 8.5 40.7 22.6zM64 96l0 336c0 26.5 21.5 48 48 48l224 0c26.5 0 48-21.5 48-48l0-336L64 96zm80 80l0 224c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-224c0-8.8 7.2-16 16-16s16 7.2 16 16zm96 0l0 224c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-224c0-8.8 7.2-16 16-16s16 7.2 16 16zm96 0l0 224c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-224c0-8.8 7.2-16 16-16s16 7.2 16 16z"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-3 text-center text-white">{{ __('No events found.') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
