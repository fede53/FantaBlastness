<!-- Tab 2: Phases -->
<div id="tab2" class="tab-content hidden">
    <div class="mb-4">
        <label for="date_for_partecipate" class="block text-gray-700 font-semibold">{{ __('Date for partecipate') }}</label>
        <input type="datetime-local" name="date_for_partecipate" id="date_for_partecipate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('date_for_partecipate', $event['date_for_partecipate'] ?? '') }}" required>
        @error('date_for_partecipate')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div class="mb-4">
        <label for="date_phase_1" class="block text-gray-700 font-semibold">{{ __('Bonus/malus pre party') }}</label>
        <input type="datetime-local" name="date_phase_1" id="date_phase_1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('date_phase_1', $event['date_phase_1'] ?? '') }}" required>
        @error('date_phase_1')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div class="mb-4">
        <label for="date_phase_2" class="block text-gray-700 font-semibold">{{ __('Data pubblicazione risultati') }}</label>
        <input type="datetime-local" name="date_phase_2" id="date_phase_2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('date_phase_2', $event['date_phase_2'] ?? '') }}" required>
        @error('date_phase_2')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>
