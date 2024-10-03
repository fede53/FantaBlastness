<x-app-layout>

    <x-slot name="breadcrumbs">
        <li><a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a></li>
        <span class="mx-2">/</span>
        <li>Users</li>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <x-profile-header
                title="Users"
                description="Manage users and their roles."
                :links="[
                        ['href' => route('users.create'), 'text' => 'Create User']
                    ]"
            />

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">

                <div class="overflow-x-auto">
                    <!-- Tabella degli utenti -->
                    <table id="sortable-table" class="min-w-full w-full table-auto border-collapse">
                        <thead class="bg-gray-200">
                        <tr>
                            <!-- Colonna della miniatura -->
                            <th class="w-1/12 px-4 py-3 text-left text-gray-700 font-bold"></th>
                            <th data-column="name" data-order="asc" class="w-4/12 px-4 py-3 text-left text-gray-700 font-bold cursor-pointer">
                                {{ __('Name') }}
                            </th>
                            <th data-column="email" data-order="asc" class="w-3/12 px-4 py-3 text-left text-gray-700 font-bold cursor-pointer">
                                {{ __('Email') }}
                            </th>
                            <th data-column="role" data-order="asc" class="w-3/12 px-4 py-3 text-left text-gray-700 font-bold cursor-pointer">
                                {{ __('Role') }}
                            </th>
                            <th class="w-3/12 px-4 py-3 text-left text-gray-700 font-bold">{{ __('Actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($users as $user)
                            <tr class="bg-white border-b hover:bg-gray-50 transition">
                                <!-- Colonna della miniatura -->
                                <td class="px-4 py-3 text-gray-700">
                                    @if($user['thumbnail'])
                                        <img src="{{ asset('storage/' . $user['thumbnail']) }}" alt="Thumbnail" class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <!-- Icona di default se non c'è miniatura -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 11c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z" />
                                            <path d="M12 14c-3.31 0-6 2.69-6 6v2h12v-2c0-3.31-2.69-6-6-6z" />
                                        </svg>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-700">{{ $user['surname'] }} {{ $user['name'] }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $user['email'] }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $user['role'] }}</td>
                                <td class="px-4 py-3 space-x-2">

                                    <div class="flex items-center justify-center">
                                        <!-- Pulsante Modifica -->
                                        <a href="{{ route('users.edit', $user['id']) }}" class="text-blast-600 hover:text-blue-900" title="{{ __('Edit') }}">
                                            <!-- Icona Modifica (matita) -->
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-5m-3-10.414a2 2 0 112.828 2.828L11.414 10.828a2 2 0 01-.828.414L8 12l.758-2.586a2 2 0 01.414-.828l5.828-5.828z" />
                                            </svg>
                                        </a>

                                        <!-- Pulsante Cancella -->
                                        @if($user['id']>1 && $user['id']!=auth()->id())
                                            <form class="flex items-center" action="{{ route('users.destroy', $user['id']) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this user?') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" title="{{ __('Delete') }}">
                                                    <!-- Icona Cancella (cestino) -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 18M6 6H18M7 6V18M17 6V18" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-3 text-center text-gray-500">{{ __('No users found.') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
