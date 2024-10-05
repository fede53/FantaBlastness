<section class="w-full">
    <header>
        <h2 class="text-lg font-medium text-white">
            {{ __('Informazioni del Profilo') }}
        </h2>

        <p class="mt-1 text-sm text-white">
            {{ __("Aggiorna le informazioni del profilo e l'indirizzo email del tuo account.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" method="POST" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="w-full flex justify-between">

        <div class="max-w-xl w-3/4">

            <div>
                <x-input-label for="name" :value="__('Nome')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user['name'])" required autofocus autocomplete="name" />
                @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6">
                <x-input-label for="surname" :value="__('Cognome')" />
                <x-text-input id="surname" name="surname" type="text" class="mt-1 block w-full" :value="old('surname', $user['surname'])" required autofocus autocomplete="surname" />
                @error('surname')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user['email'])" required autocomplete="username" />
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user['hasVerifiedEmail']())
                    <div>
                        <p class="text-sm mt-2 text-white">
                            {{ __('Il tuo indirizzo email non è verificato.') }}

                            <button form="send-verification" class="underline text-sm text-white hover:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Clicca qui per reinviare l\'email di verifica.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600">
                                {{ __('Un nuovo link di verifica è stato inviato al tuo indirizzo email.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="w-3/12 p-4 pt-0 sm:pr-8 sm:pb-8 sm:pl-8 flex flex-col items-center">
            <label for="image" class="block text-white font-semibold text-center">{{ __('Profile Picture') }}</label>
            <div class="mt-4 relative flex justify-center">
                <img id="image-preview" src="{{ isset($user) && $user['image'] ? asset('storage/' . $user['image']) : '' }}"
                     alt=""
                     class="w-24 h-24 rounded-full object-cover border"
                     style="width: 150px; height: 150px;"
                >
                <button type="button" id="delete-image" class="{{ !isset($user['image']) ? 'hidden' : '' }} remove-bonus-pre absolute top-2 right-2 bg-red-600 hover:bg-red-700 text-white flex items-center justify-center w-8 h-8 rounded-full">
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
        </div>

        </div>


        <div class="flex items-center gap-4 !mt-0">
            <x-primary-button>{{ __('Salva') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-white"
                >{{ __('Salvato.') }}</p>
            @endif
        </div>
    </form>
</section>

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
