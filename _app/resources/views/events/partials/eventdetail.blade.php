<h2 class="text-3xl font-semibold text-white">{{ $event['name'] }}</h2>

<div class="bg-dark-100 mx-auto shadow-lg rounded-lg overflow-hidden w-full photo-full">
    @if($event['image'])
        <img src="{{ asset('storage/' . $event['image']) }}" alt="Event Image" class="w-full h-full object-cover">
    @endif
</div>

<div class="text-lg text text-white">{!! $event['description'] !!}</div>

<!-- Pulsante Crea la tua squadra e partecipa -->
@if ($event['can_partecipate'] && !$event['haveATeam'])
    <div id="countdown-{{ $event['id'] }}" class="flex justify-center text-white font-semibold mb-4">
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
    <div class="flex justify-center">
        <a href="{{ route('events.team.create', $event['id']) }}" class="px-3 bg-primary hover:bg-primary text-white font-bold py-4 px-8 rounded-lg text-xl shadow-lg transition duration-300 ease-in-out">
            Crea la tua squadra e partecipa
        </a>
    </div>
@endif

<!-- Accordion for Regulation -->
<div class="bg-dark-100 shadow-lg rounded-lg overflow-hidden mt-6">
    <button onclick="toggleAccordion()" class="w-full text-left p-4 text-lg font-semibold text-white bg-dark-100 border-b-2 border-primary dark:bg-dark hover:bg-dark-100">
        Clicca qui per leggere il <strong>regolamento</strong>
        <span id="accordion-icon" class="float-right transition-transform duration-300">
                        ▼
                    </span>
    </button>
    <div id="accordion-content" class="p-4  hidden">
        <div class="text-white text text-md">{!! $event['regulation'] !!}</div>
    </div>
</div>

<!-- Sezione Bonus e Malus -->
<div class="grid grid-cols-2 gap-6">
    <!-- Colonna Bonus -->
    <div class="bg-dark-100 p-6 rounded-lg shadow-md">
        <h3 class="text-2xl font-semibold text-green-700 mb-4">Bonus</h3>
        @foreach ($event['bonus'] as $rule)
            <div class="rule bg-dark p-4 rounded-lg shadow-md mb-4 text-green-600">
            <div class="bullet {{ $rule['characteristic'] }}"><div class="bullet_text">{{ $rule['characteristic'] }}</div></div>
                <h4 class="text-xl font-bold text-green-600">{{ $rule['name'] }}</h4>
                <div class="text-white">{!! $rule['description'] !!}</div>
                <p class="text-green-500 font-semibold text-lg">+{{ $rule['value'] }}</p>
            </div>
        @endforeach
    </div>

    <!-- Colonna Malus -->
    <div class="bg-dark-100 p-6 rounded-lg shadow-md">
        <h3 class="text-2xl font-semibold text-red-700 mb-4">Malus</h3>
        @foreach ($event['malus'] as $rule)
            <div class="rule bg-dark p-4 rounded-lg shadow-md mb-4 text-red-600">
            <div class="bullet {{ $rule['characteristic'] }}"><div class="bullet_text">{{ $rule['characteristic'] }}</div></div>
                <h4 class="text-xl font-bold text-red-600 ">{{ $rule['name'] }}</h4>
                <div class="text-white">{!! $rule['description'] !!}</div>
                <p class="text-red-500 font-semibold text-lg">-{{ $rule['value'] }}</p>
            </div>
        @endforeach
    </div>
</div>

@push('scripts')
    <script>
        function toggleAccordion() {
            const content = document.getElementById('accordion-content');
            const icon = document.getElementById('accordion-icon');

            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                icon.innerHTML = '▲'; // Change icon to up arrow
            } else {
                content.classList.add('hidden');
                icon.innerHTML = '▼'; // Change icon to down arrow
            }
        }
    </script>
@endpush
