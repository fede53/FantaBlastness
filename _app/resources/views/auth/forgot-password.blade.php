<x-guest-layout>
    <div class="mb-4 text-sm text-white">
        {{ __('Hai dimenticato la password? Nessun problema. Facci sapere il tuo indirizzo email e ti invieremo un link per reimpostare la password, così potrai sceglierne una nuova.') }}
    </div>

    <!-- Stato della sessione -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Indirizzo Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Invia Link per Reimpostare la Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
