@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'bg-dark focus:border-primary rounded-md shadow-sm']) !!}>
