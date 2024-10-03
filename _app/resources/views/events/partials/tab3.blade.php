<!-- Tab 3: Participants -->
<div id="tab3" class="tab-content hidden">
    <div class="flex justify-between items-center pb-2 mb-4 border-b-2 border-gray-300">
        <h3 class="text-lg font-semibold text-gray-700"></h3>
        <span class="text-lg font-semibold text-gray-700">Cost (Dolphins)</span>
    </div>
    <ul>
        @foreach ($members as $member)
            @php
                $pivotData = isset($event) && $event['members']->has($member['id'])
                             ? $event['members'][$member['id']]
                             : null;
            @endphp
            <li class="flex justify-between items-center py-4 border-b border-gray-300">
                <div class="flex items-center">
                    <input type="checkbox" name="members[{{ $member['id'] }}][active]" class="mr-4" {{ $pivotData!=null && $pivotData['active'] ? 'checked' : '' }}>
                    <img src="{{ asset('storage/' . $member['thumbnail']) }}" alt="{{ $member['name'] }}" class="w-12 h-12 rounded-full object-cover mr-4">
                    <span class="font-semibold text-gray-700">{{ $member['name'] }}</span>
                </div>
                <div class="flex items-center">
                    <input type="number" name="members[{{ $member['id'] }}][cost]" class="border border-gray-300 rounded-md shadow-sm w-24" value="{{ old('members.' . $member['id'] . '.cost', $pivotData['cost'] ?? 0) }}">
                </div>
            </li>
        @endforeach
    </ul>


</div>
