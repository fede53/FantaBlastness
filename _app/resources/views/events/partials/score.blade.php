<div class="overflow-x-auto relative shadow-md sm:rounded-lg bg-dark-100 border border-dark-100">
    <table class="w-full text-sm text-left text-white">
        <thead class="text-xs uppercase bg-dark-100 border-b-2 border-primary text-white">
        <tr>
            <th scope="col" class="py-3 px-6 w-4/12">Partecipante</th>
            <th scope="col" class="py-3 px-6 w-1/12">Punteggio</th>
            <th scope="col" class="py-3 px-6 w-1/12">Extra</th>
            <th scope="col" class="py-3 px-6 w-2/12">Message</th>
            <th scope="col" class="py-3 px-6 w-4/12">Trofei</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($members as $member)

            <tr class="bg-dark-100 border-b border-dark-100">
                <!-- Partecipante -->
                <td class="py-4 px-6 flex items-center">
                    <img src="{{ asset('storage/' . $member['thumbnail']) }}" alt="{{ $member['name'] }}" class="w-12 h-12 rounded-full object-cover mr-4">
                    <span class="font-semibold">{{ $member['name'] }}</span>
                </td>

                <!-- Cost (Dolphins) -->
                <td class="py-4 px-6">
                    {{ $member['score']  }}
                </td>

                <!-- Extra -->
                <td class="py-4 px-6">
                    {{ $member['extra']  }}
                </td>

                <!-- Extra Message -->
                <td class="py-4 px-6">
                    {{ $member['extra_message']  }}
                </td>

                <!-- Extra Message -->
                <td class="py-4 px-6 bg-dark">
                    <div class="flex items-center">
                    @foreach ($member['trophies'] as $key => $trophy)
                        @if($trophy > 2)
                            <div style="display:flex; align-items: center; width: 50px; margin-right: 10px; height: 50px; border-radius:50%; color: white; background: @if($trophy <= 2) #cd7f32 @elseif($trophy <= 5) #c0c0c0  @elseif($trophy <= 7) #ffd700 @endif">
                                {{ $key }}
                            </div>
                        @endif
                    @endforeach
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
