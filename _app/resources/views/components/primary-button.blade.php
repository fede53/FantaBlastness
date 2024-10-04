<button {{ $attributes->merge(['type' => 'submit', 'class' => 'bg-primary text-white font-semibold py-2 px-4 rounded']) }}>
    {{ $slot }}
</button>
