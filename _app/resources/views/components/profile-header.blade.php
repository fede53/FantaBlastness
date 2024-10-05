@props([
    'title' => __('Profile Information'),
    'description' => __("Update your account's profile information and email address."),
    'links' => [] // Array di link predefinito
])

<header {{ $attributes->merge(['class' => 'mb-6']) }}>
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-lg font-medium text-white">
                {{ $title }}
            </h2>
            <p class="mt-1 text-sm text-white">
                {{ $description }}
            </p>
        </div>

        @if (!empty($links))
            <div class="inline-flex rounded-md shadow-sm" role="group">
                @foreach ($links as $index => $link)
                    <a href="{{ $link['href'] }}"
                       class="bg-primary hover:bg-primary text-white font-semibold py-1 px-3 text-sm  {{ count($links) == 1 ? 'rounded' : '' }} {{ count($links) > 1 && $index === 0 ? 'rounded-l-md' : (count($links) > 1 && $loop->last ? 'rounded-r-md' : '') }}">
                        {{ $link['text'] }}
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</header>
