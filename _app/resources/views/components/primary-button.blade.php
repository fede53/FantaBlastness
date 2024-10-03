<button {{ $attributes->merge(['type' => 'submit', 'class' => 'bg-blast-600 hover:bg-blast-700 text-white font-semibold py-2 px-4 rounded']) }}>
    {{ $slot }}
</button>
