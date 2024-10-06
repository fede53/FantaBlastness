<x-app-layout>

    <x-slot name="breadcrumbs">
        <li><a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a></li>
        <span class="mx-2">/</span>
        <li><a href="{{ route('events.index') }}" class="hover:underline">Events</a></li>
        <span class="mx-2">/</span>
        <li>{{ isset($event) ? 'Edit event' : 'Create event' }}</li>
    </x-slot>

    <div class="py-12 pb-32"> <!-- Aggiungi padding bottom per evitare la sovrapposizione -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <x-profile-header
                title="Events"
                description="Manage events and their roles."
                :links="[
                        ['href' => route('events.index'), 'text' => 'Event List']
                    ]"
            />

            <!-- Layout a due colonne -->
            <form action="{{ isset($event) ? route('events.update', $event['id']) : route('events.store') }}" method="POST" enctype="multipart/form-data">

                @csrf
                @if(isset($event))
                    @method('PUT')
                @endif

                <div class="flex justify-between gap-6">
                    <div class="w-9/12 p-4 sm:p-8 bg-dark-100 shadow sm:rounded-lg">

                        <!-- Tab Buttons -->
                        <div class="mb-4">
                            <ul class="flex tab-list">
                                <li class="mr-1">
                                    <a href="#tab1" class="bg-dark-100 inline-block py-2 px-4 text-white hover:text-white font-semibold text-white-2 border-primary border-b-2" onclick="openTab(event, 'tab1')">General</a>
                                </li>
                                <li class="mr-1">
                                    <a href="#tab2" class="bg-dark-100 inline-block py-2 px-4 text-white hover:text-white font-semibold" onclick="openTab(event, 'tab2')">Phases</a>
                                </li>
                                <li>
                                    <a href="#tab3" class="bg-dark-100 inline-block py-2 px-4 text-white hover:text-white font-semibold" onclick="openTab(event, 'tab3')">Members</a>
                                </li>
                                <li>
                                    <a href="#tab4" class="bg-dark-100 inline-block py-2 px-4 text-white hover:text-white font-semibold" onclick="openTab(event, 'tab4')">Bonus</a>
                                </li>
                                <li>
                                    <a href="#tab5" class="bg-dark-100 inline-block py-2 px-4 text-white hover:text-white font-semibold" onclick="openTab(event, 'tab5')">Malus</a>
                                </li>
                            </ul>
                        </div>

                        @include('events.partials.tab1')

                        @include('events.partials.tab2')

                        @include('events.partials.tab3')

                        @include('events.partials.tab4')

                        @include('events.partials.tab5')

                    </div>

                    <!-- Colonna gestione immagine (destra) -->
                    <div class="w-3/12 p-4 pt-0 sm:pr-8 sm:pb-8 sm:pl-8">

                        <div class="sticky top-10 flex flex-col items-center">
                            <label for="image" class="block text-white font-semibold text-center">{{ __('Profile Picture') }}</label>
                            <div class="mt-4 relative flex justify-center">
                                <img id="image-preview" src="{{ isset($event) && $event['image'] ? asset('storage/' . $event['image']) : '' }}"
                                     alt=""
                                     class="w-24 h-24 rounded-full object-cover border"
                                     style="width: 150px; height: 150px;"
                                >
                                <button type="button" id="delete-image" class="{{ !isset($event['image']) ? 'hidden' : '' }} remove-bonus-pre absolute top-2 right-2 bg-red-600 hover:bg-red-700 text-white flex items-center justify-center w-8 h-8 rounded-full">
                                    X
                                </button>
                            </div>
                            <label for="image" class="bg-primary hover:bg-primary text-white font-semibold py-2 px-4 rounded cursor-pointer inline-block mt-4 text-center">
                                {{ __('Choose File') }}
                            </label>
                            <input type="hidden" name="image_deleted" id="image_deleted" value="0" class="hidden">
                            <input type="file" name="image" id="image" class="hidden" accept="image/*" onchange="previewImage(event)">
                            @error('image')
                                <p class="text-red-500 text-sm mt-1 text-center">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Barra fissa con i pulsanti -->
                <div class="fixed z-50 bottom-0 left-0 right-0  py-3 px-6 flex justify-end">
                    <a href="{{ route('events.index') }}" class="bg-dark border-primary border-2 text-primary font-semibold py-2 px-4 rounded mr-2">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="bg-primary hover:bg-primary text-white font-semibold py-2 px-4 rounded">
                        {{ isset($event) ? __('Update Event') : __('Create Event') }}
                    </button>
                </div>

            </form>

        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.tiny.cloud/1/w9zd7syofrqk6xb3z20cjcd44pj5ryu4jdeg3ecrv568gjdl/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
        <script>

            tinymce.init({
                selector: '.tinymce-editor',
                skin: "oxide-dark",
                content_css: "dark",
                plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
            });

            function openTab(evt, tabName) {
                var i, tabcontent, tablinks;
                tabcontent = document.getElementsByClassName("tab-content");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }
                tablinks = document.getElementsByTagName("a");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].classList.remove("text-white", "border-b-2", "border-primary");
                }
                document.getElementById(tabName).style.display = "block";
                evt.currentTarget.classList.add("text-white", "border-b-2", "border-primary");
            }

            document.addEventListener('DOMContentLoaded', function() {
                document.querySelector('.tab-content').style.display = 'block';
            });

            function previewImage(event) {
                var reader = new FileReader();
                reader.onload = function(){
                    var output = document.getElementById('image-preview');
                    output.src = reader.result;
                    output.classList.remove('hidden');
                    output.style.width = '150px';
                    output.style.height = '150px';
                };
                document.getElementById('delete-image').classList.remove('hidden');
                reader.readAsDataURL(event.target.files[0]);
            }

            // Funzione per cancellare l'immagine
            document.getElementById('delete-image')?.addEventListener('click', function() {
                document.getElementById('image-preview').src = '';
                document.getElementById('delete-image').classList.add('hidden');
                document.getElementById('image').value = '';
                document.getElementById('image_deleted').value = 1;
            });

            document.addEventListener('DOMContentLoaded', function () {
                let bonusIndex = 0;
                let malusIndex = 0;

                // Funzione per creare una regola dinamicamente (per pre e post)
                function createRuleHTML(index, type, rule = null) {
                    const ruleName = type === 0 ? 'Bonus' : 'Malus';
                    const ruleList = `${type === 0 ? 'bonus' : 'malus'}-list`;
                    const ruleTypeClass = `${type === 0 ? 'remove-bonus' : 'remove-malus'}`;
                    const removeButtonClass = 'bg-red-600 hover:bg-red-700 text-white flex items-center justify-center w-8 h-8 rounded-full';
                    const rulePrefix = `${type === 0 ? 'bonus' : 'malus'}[${index}]`;

                    const li = document.createElement('li');
                    li.className = "mb-4 p-4 rounded relative bg-dark"; // Aggiungi `relative` per posizionamento assoluto

                    li.innerHTML = `
            <div class="grid grid-cols-2 gap-4">
                <!-- Colonna sinistra -->
                <div>
                    <input type="hidden" name="${rulePrefix}[type]" value="${type}">
                    ${rule && rule.id ? `<input type="hidden" name="${rulePrefix}[id]" value="${rule.id}">` : ''}
                    <input type="hidden" name="${rulePrefix}[_delete]" value="0" class="delete-flag">

                    <div class="mb-2">
                        <label class="block text-white">${ruleName} Name</label>
                        <input type="text" name="${rulePrefix}[name]" class="border rounded-md w-full bg-dark-100" placeholder="Enter ${ruleName.toLowerCase()} name" value="${rule ? rule.name : ''}">
                    </div>

                    <div class="mb-2">
                        <label class="block text-white">Value</label>
                        <input type="number" name="${rulePrefix}[value]" class="border rounded-md w-full bg-dark-100" placeholder="Enter ${ruleName.toLowerCase()} value" value="${rule ? rule.value : ''}">
                    </div>

                    <div class="mb-2">
                        <label class="block text-white">Characteristic}</label>
                        <select name="${rulePrefix}[characteristic]" class="border rounded-md w-full bg-dark-100" placeholder="Enter ${ruleName.toLowerCase()} characteristic">
                            <option value="generale" ${(rule && rule.characteristic == 'generale') ? "selected" : "" }>generale</option>    
                            <option value="alcolismo" ${(rule && rule.characteristic == 'alcolismo') ? "selected" : "" }>alcolismo</option>
                            <option value="resistenza" ${(rule && rule.characteristic == 'resistenza') ? "selected" : "" }>resistenza</option>
                            <option value="professionalita" ${(rule && rule.characteristic == 'professionalita') ? "selected" : "" }>professionalita</option>
                            <option value="socialita" ${(rule && rule.characteristic == 'socialita') ? "selected" : "" }>socialita</option>
                            <option value="seduzione" ${(rule && rule.characteristic == 'seduzione') ? "selected" : "" }>seduzione</option>
                            <option value="pazzia" ${(rule && rule.characteristic == 'pazzia') ? "selected" : "" }>pazzia</option>
                        </select>
                    </div>
                </div>

                <!-- Colonna destra -->
                <div>
                    <div class="mb-2">
                        <label class="block text-white">Description</label>
                        <textarea name="${rulePrefix}[description]" class="tinymce-editor-small border rounded-md w-full bg-dark-100">${rule ? rule.description : ''}</textarea>
                    </div>
                </div>
            </div>

            <!-- Pulsante con icona di cestino -->
            <button type="button" class="${ruleTypeClass} absolute bottom-2 right-2 ${removeButtonClass}">
                <!-- Icona cestino -->
                <svg style="fill: red; height: 17px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Pro 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2024 Fonticons, Inc.--><path d="M164.2 39.5L148.9 64l150.3 0L283.8 39.5c-2.9-4.7-8.1-7.5-13.6-7.5l-92.5 0c-5.5 0-10.6 2.8-13.6 7.5zM311 22.6L336.9 64 384 64l32 0 16 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-16 0 0 336c0 44.2-35.8 80-80 80l-224 0c-44.2 0-80-35.8-80-80L32 96 16 96C7.2 96 0 88.8 0 80s7.2-16 16-16l16 0 32 0 47.1 0L137 22.6C145.8 8.5 161.2 0 177.7 0l92.5 0c16.6 0 31.9 8.5 40.7 22.6zM64 96l0 336c0 26.5 21.5 48 48 48l224 0c26.5 0 48-21.5 48-48l0-336L64 96zm80 80l0 224c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-224c0-8.8 7.2-16 16-16s16 7.2 16 16zm96 0l0 224c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-224c0-8.8 7.2-16 16-16s16 7.2 16 16zm96 0l0 224c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-224c0-8.8 7.2-16 16-16s16 7.2 16 16z"/></svg>
            </button>
        `;

                    document.getElementById(ruleList).appendChild(li);
                    tinymce.init({
                        selector: '.tinymce-editor-small',
                        plugins: 'autolink lists',
                        toolbar: 'bold italic underline | bullist numlist',
                        menubar: false,
                        statusbar: false,
                        height: 200,
                        skin: "oxide-dark",
                        content_css: "dark",
                    });

                    li.querySelector(`.${ruleTypeClass}`).addEventListener('click', function () {
                        li.querySelector('.delete-flag').value = 1;
                        li.style.display = 'none';
                    });
                }

                document.getElementById('add-bonus-button').addEventListener('click', function () {
                    bonusIndex++;
                    createRuleHTML(bonusIndex, 0);
                });
                document.getElementById('add-malus-button').addEventListener('click', function () {
                    malusIndex++;
                    createRuleHTML(malusIndex, 1);
                });

                // Inizializza i bonus e malus esistenti
                const rules = @json($event['rules'] ?? []);
                rules.forEach((rule, index) => {
                    if (rule.type === 0) {
                        bonusIndex++;
                        createRuleHTML(bonusIndex, 0, rule);
                    } else if (rule.type === 1) {
                        malusIndex++;
                        createRuleHTML(malusIndex, 1, rule);
                    }
                });
            });

        </script>
    @endpush

</x-app-layout>
