<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nome')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Email aziendale')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <span id="email-error" class="text-red-500 text-sm mt-2 hidden">L'email deve terminare con @blastness.com oppure @foxtechnologies.it</span>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const emailInput = document.getElementById('email');
                const emailError = document.getElementById('email-error');

                emailInput.addEventListener('blur', function () {
                    const emailValue = emailInput.value;
                    const validDomains = ['@blastness.com', '@foxtechnologies.it'];

                    const isValid = validDomains.some(domain => emailValue.endsWith(domain));

                    if (!isValid) {
                        emailError.classList.remove('hidden');
                        emailInput.classList.add('border-red-500');
                        emailInput.value = '';
                    } else {
                        emailError.classList.add('hidden');
                        emailInput.classList.remove('border-red-500');
                    }
                });
            });
        </script>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Conferma Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="text-sm text-white hover:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Sei già registrato?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Registrati') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
