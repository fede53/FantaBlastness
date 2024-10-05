<!-- Tab Content -->
<div id="tab1" class="tab-content hidden">
    <div class="mb-4">
        <label for="name" class="block text-white font-semibold">{{ __('Name') }}</label>
        <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md shadow-sm" value="{{ old('name', $event['name'] ?? '') }}" required>
        @error('name')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div class="mb-4">
        <label for="dolphins" class="block text-white font-semibold">{{ __('Dolphins for participant') }}</label>
        <input type="number" name="dolphins" id="dolphins" class="mt-1 block w-full rounded-md shadow-sm" value="{{ old('dolphins', $event['dolphins'] ?? '') }}" required>
        @error('dolphins')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div class="mb-4">
        <label for="description" class="block text-white font-semibold">{{ __('Description') }}</label>
        <textarea id="description" name="description" class="tinymce-editor mt-1 block w-full rounded-md shadow-sm">{{ old('description', $event['description'] ?? '') }}</textarea>
        @error('description')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div class="mb-4">
        <label for="regulation" class="block text-white font-semibold">{{ __('Regulation') }}</label>
        <textarea id="regulation" name="regulation" class="tinymce-editor mt-1 block w-full rounded-md shadow-sm">{{ old('regulation', $event['regulation'] ?? '') }}</textarea>
        @error('regulation')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div class="mb-4">
        <label for="instructions" class="block text-white font-semibold">{{ __('Regulation') }}</label>
        <textarea id="instructions" name="instructions" class="tinymce-editor mt-1 block w-full rounded-md shadow-sm">{{ old('instructions', $event['instructions'] ?? '') }}</textarea>
        @error('instructions')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>
