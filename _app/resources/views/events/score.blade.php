<x-app-layout>

    <x-slot name="breadcrumbs">
        <li><a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a></li>
        <span class="mx-2">/</span>
        <li>{{ $event['name'] }}</li>
    </x-slot>

    <form action="{{ route('score.store', $event['id']) }}" method="POST" enctype="multipart/form-data" onsubmit="return handleFormSubmit(event)">
        @csrf
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <h2 class="text-3xl font-semibold text-white mb-6">{{ $event['name'] }}</h2>

                <!-- Contatore totale Bonus e Malus -->
                <div class="bg-dark-100 p-6 !mt-0 rounded-lg shadow-md mb-6 sticky !top-0 flex justify-between items-center">
                    <h3 class="text-2xl font-semibold text-white">Totale Bonus/Malus: <span id="totalValue">0</span></h3>
                    <button type="submit" class="bg-primary font-semibold text-white px-4 py-2 rounded-md hover:bg-primary transition">Invia</button>
                </div>

                <!-- Sezione Bonus e Malus -->
                <div class="grid grid-cols-2 gap-6">
                    <!-- Colonna Bonus -->
                    <div class="bg-dark p-6 rounded-lg shadow-md">
                        <h3 class="text-2xl font-semibold text-green-700 mb-4">Bonus</h3>
                        @foreach ($event['bonus'] as $rule)
                            <div class="bg-dark-100 p-4 rounded-lg shadow-md rule border-2 border-dark mb-4">
                                <div class="bullet {{ $rule['characteristic'] }}"><div class="bullet_text">{{ $rule['characteristic'] }}</div></div>
                                <h4 class="text-xl font-bold text-green-600">{{ $rule['name'] }}</h4>
                                <div class="text-green-500">{!! $rule['description'] !!}</div>
                                <p class="text-green-500 font-semibold text-lg">+{{ $rule['value'] }}</p>
                                <input class="check-rule" type="checkbox" name="rules[{{ $rule['id'] }}]" value="{{ $rule['value'] }}" onchange="updateTotal()">
                            </div>
                        @endforeach
                    </div>

                    <!-- Colonna Malus -->
                    <div class="bg-dark p-6 rounded-lg shadow-md">
                        <h3 class="text-2xl font-semibold text-red-700 mb-4">Malus</h3>
                        @foreach ($event['malus'] as $rule)
                            <div class="bg-dark-100 p-4 rounded-lg shadow-md border-2 border-dark rule mb-4">
                                <div class="bullet {{ $rule['characteristic'] }}"><div class="bullet_text">{{ $rule['characteristic'] }}</div></div>
                                <h4 class="text-xl font-bold text-red-600">{{ $rule['name'] }}</h4>
                                <div class="text-red-500">{!! $rule['description'] !!}</div>
                                <p class="text-red-500 font-semibold text-lg">-{{ $rule['value'] }}</p>
                                <input class="check-rule" type="checkbox" name="rules[{{ $rule['id'] }}]" value="-{{ $rule['value'] }}" onchange="updateTotal()">
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </form>

    @push('scripts')
        <script>
            // Seleziona tutti gli elementi con la classe 'check-rule'
            // Seleziona tutti gli elementi con la classe 'check-rule'
            document.querySelectorAll('.check-rule').forEach(function(element) {
            // Aggiungi un event listener per il click su ciascuno di essi
            element.addEventListener('click', function() {
                // Trova l'elemento padre con la classe 'rule'
                const parentRule = element.closest('.rule');
                if (parentRule) {
                // Alterna la classe 'border-primary'
                    parentRule.classList.toggle('checked');
                }
            });
            });
            function updateTotal() {
                let total = 0;
                document.querySelectorAll('input[name^="rules"]:checked').forEach(function(input) {
                    total += parseInt(input.value);
                });
                document.getElementById('totalValue').innerText = total;
            }
            function handleFormSubmit(event) {
                // Conferma la sottomissione del form
                return confirm('Sei sicuro di aver selezionato tutti i Bonus e Malus?');
            }
            document.addEventListener('DOMContentLoaded', function() {
                updateTotal();
            });
        </script>
    @endpush

</x-app-layout>
