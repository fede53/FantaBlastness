<!-- Tab 3: Participants -->
<div id="tab3" class="tab-content hidden">
    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-white">
            <thead class="text-xs uppercase bg-primary text-white">
            <tr>
                <th scope="col" class="py-3 px-6 w-4/12">Partecipante</th>
                <th scope="col" class="py-3 px-6 w-1/12">Attivo</th>
                <th scope="col" class="py-3 px-6 w-1/12">Cost (Dolphins)</th>
                <th scope="col" class="py-3 px-6 w-1/12">Extra</th>
                <th scope="col" class="py-3 px-6 w-5/12">Message</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($members as $member)
                @php
                    $pivotData = isset($event) && $event['members']->has($member['id'])
                                 ? $event['members'][$member['id']]
                                 : null;
                @endphp
                <tr class="bg-dark border-b">
                    <!-- Partecipante -->
                    <td class="py-4 px-6 flex items-center">
                        <img src="{{ asset('storage/' . $member['thumbnail']) }}" alt="{{ $member['name'] }}" class="w-12 h-12 rounded-full object-cover mr-4">
                        <span class="font-semibold">{{ $member['name'] }}</span>
                    </td>

                    <!-- Checkbox Attivo -->
                    <td class="py-4 px-6">
                        <input type="checkbox" name="members[{{ $member['id'] }}][active]" {{ $pivotData != null && $pivotData['active'] ? 'checked' : '' }}>
                    </td>

                    <!-- Cost (Dolphins) -->
                    <td class="py-4 px-6">
                        <input type="number" name="members[{{ $member['id'] }}][cost]" class="border rounded-md shadow-sm w-24" value="{{ old('members.' . $member['id'] . '.cost', $pivotData['cost'] ?? 0) }}">
                    </td>

                    <!-- Extra -->
                    <td class="py-4 px-6">
                        <input type="number" name="members[{{ $member['id'] }}][extra]" class="border rounded-md shadow-sm w-24" value="{{ old('members.' . $member['id'] . '.extra', $pivotData['extra'] ?? 0) }}">
                    </td>

                    <!-- Extra Message -->
                    <td class="py-4 px-6">
                        <input type="text" name="members[{{ $member['id'] }}][extra_message]" class="border !border-white bg-dark-100 rounded-md shadow-sm w-full" value="{{ old('members.' . $member['id'] . '.extra_message', $pivotData['extra_message'] ?? '') }}">
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
