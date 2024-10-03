<x-app-layout>

    <x-slot name="breadcrumbs">
        <li><a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a></li>
        <span class="mx-2">/</span>
        <li><a href="{{ route('users.index') }}" class="hover:underline">Users</a></li>
        <span class="mx-2">/</span>
        <li>{{ isset($user) ? 'Edit user' : 'Create user' }}</li>
    </x-slot>

    <div class="py-12 pb-32"> <!-- Aggiungi padding bottom per evitare la sovrapposizione -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <x-profile-header
                title="Users"
                description="Manage users and their roles."
                :links="[
                        ['href' => route('users.index'), 'text' => 'User List']
                    ]"
            />

            <!-- Layout a due colonne -->
            <form action="{{ isset($user) ? route('users.update', $user['id']) : route('users.store') }}" method="POST" enctype="multipart/form-data">

                @csrf
                @if(isset($user))
                    @method('PUT')
                @endif

                <div class="flex justify-between gap-6">
                    <!-- Colonna principale (sinistra) -->
                    <div class="w-9/12 p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <!-- Nome -->
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 font-semibold">{{ __('Name') }}</label>
                            <input type="text" name="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('name', $user['name'] ?? '') }}" required>
                            @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="surname" class="block text-gray-700 font-semibold">{{ __('Surname') }}</label>
                            <input type="text" name="surname" id="surname" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('surname', $user['surname'] ?? '') }}" required>
                            @error('surname')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 font-semibold">{{ __('Email') }}</label>
                            <input type="email" name="email" id="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('email', $user['email'] ?? '') }}" required>
                            @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ruolo -->
                        <div class="mb-4">
                            <label for="role_id" class="block text-gray-700 font-semibold">{{ __('Role') }}</label>
                            <select name="role_id" id="role_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                @foreach ($roles as $role)
                                    <option value="{{ $role['id'] }}" {{ old('role_id', $user['role_id'] ?? '') == $role['id'] ? 'selected' : '' }}>
                                        {{ $role['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="block text-gray-700 font-semibold">{{ isset($user) ? __('New Password') : __('Password') }}</label>
                            <div class="relative">
                                <input type="password" name="password" id="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" {{ isset($user) ? '' : 'required' }}>
                                <div class="flex space-x-2 mt-2">
                                    <!-- Pulsante Genera Password -->
                                    <button type="button" id="generate-password" class="bg-blast-600 hover:bg-blast-700 text-white font-semibold py-1 px-3 rounded">
                                        {{ __('Generate Password') }}
                                    </button>
                                    <!-- Pulsante Mostra Password -->
                                    <button type="button" id="toggle-password" class="bg-blast-600 hover:bg-blast-700 text-white font-semibold py-1 px-3 rounded">
                                        {{ __('Show Password') }}
                                    </button>
                                </div>
                            </div>
                            @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Conferma Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-gray-700 font-semibold">{{ __('Confirm Password') }}</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" {{ isset($user) ? '' : 'required' }}>
                        </div>
                    </div>

                    <!-- Colonna gestione immagine (destra) -->
                    <div class="w-3/12 p-4 pt-0 sm:pr-8 sm:pb-8 sm:pl-8 flex flex-col items-center">
                        <label for="image" class="block text-gray-700 font-semibold text-center">{{ __('Profile Picture') }}</label>
                        <div class="mt-4 relative flex justify-center">
                            <img id="image-preview" src="{{ isset($user) && $user['image'] ? asset('storage/' . $user['image']) : '' }}"
                                 alt=""
                                 class="w-24 h-24 rounded-full object-cover border border-gray-300"
                                 style="width: 150px; height: 150px;"
                            >
                            <button type="button" id="delete-image" class="{{ !isset($user['image']) ? 'hidden' : '' }} remove-bonus-pre absolute top-2 right-2 bg-red-600 hover:bg-red-700 text-white flex items-center justify-center w-8 h-8 rounded-full">
                                X
                            </button>
                        </div>
                        <label for="image" class="bg-blast-600 hover:bg-blast-700 text-white font-semibold py-2 px-4 rounded cursor-pointer inline-block mt-4 text-center">
                            {{ __('Choose File') }}
                        </label>
                        <input type="hidden" name="image_deleted" id="image_deleted" value="0" class="hidden">
                        <input type="file" name="image" id="image" class="hidden" accept="image/*" onchange="previewImage(event)">
                        @error('image')
                        <p class="text-red-500 text-sm mt-1 text-center">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Barra fissa con i pulsanti -->
                <div class="fixed bottom-0 left-0 right-0 bg-gray-100 border-t border-gray-300 py-3 px-6 flex justify-end">
                    <a href="{{ route('users.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded mr-2">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="bg-blast-600 hover:bg-blast-700 text-white font-semibold py-2 px-4 rounded">
                        {{ isset($user) ? __('Update User') : __('Create User') }}
                    </button>
                </div>

            </form>

        </div>
    </div>

    @push('scripts')
        <script>
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
        </script>
    @endpush

</x-app-layout>
