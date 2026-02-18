@extends('layouts.app')

@section('content')
    <div class="container-custom">
        <div class="max-w-2xl mx-auto">
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Créer un Atelier</h1>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400 mt-1">Ajoutez un atelier à un événement</p>
                        </div>
                        <div class="hidden md:block">
                            <div class="text-right">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                    <i class="fas fa-chalkboard-teacher mr-2"></i>Nouvel atelier
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                <i class="fas fa-info-circle text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div>
                                <p class="font-medium text-blue-900 dark:text-blue-300">Information</p>
                                <p class="text-sm text-blue-700 dark:text-blue-400">Les ateliers sont associés à des événements spécifiques. Assurez-vous de sélectionner le bon événement.</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('ateliers.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Événement associé -->
                        <div class="form-group">
                            <label for="id_event" class="form-label">
                                <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>Événement associé
                                <span class="text-red-500">*</span>
                            </label>
                            <select name="id_event" id="id_event" 
                                    class="input @error('id_event') input-error @enderror"
                                    required>
                                <option value="">Sélectionner un événement</option>
                                @foreach($evenements as $evenement)
                                    <option value="{{ $evenement->id_event }}" 
                                            data-start="{{ \Illuminate\Support\Carbon::parse($evenement->date_heure_debut)->format('Y-m-d') }}"
                                            data-end="{{ \Illuminate\Support\Carbon::parse($evenement->date_heure_fin)->format('Y-m-d') }}"
                                            {{ (old('id_event') == $evenement->id_event) || (isset($selectedEvenement) && $selectedEvenement->id_event == $evenement->id_event) ? 'selected' : '' }}>
                                        {{ $evenement->titre }} - {{ \Illuminate\Support\Carbon::parse($evenement->date_heure_debut)->format('d/m/Y') }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="form-help">Sélectionnez l'événement auquel associer cet atelier</p>
                            @error('id_event')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Informations de l'atelier -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="titre" class="form-label">
                                    <i class="fas fa-heading mr-2 text-blue-600"></i>Titre de l'atelier
                                </label>
                                <input type="text" name="titre" id="titre" value="{{ old('titre') }}" 
                                       class="input @error('titre') input-error @enderror"
                                       placeholder="Titre de l'atelier">
                                @error('titre')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="capacite" class="form-label">
                                    <i class="fas fa-users mr-2 text-blue-600"></i>Capacité maximale
                                </label>
                                <input type="number" name="capacite" id="capacite" value="{{ old('capacite') }}" 
                                       class="input @error('capacite') input-error @enderror"
                                       placeholder="Nombre de participants">
                                @error('capacite')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Dates et heures -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="date" class="form-label">
                                    <i class="fas fa-calendar-day mr-2 text-blue-600"></i>Date de l'atelier
                                </label>
                                <input type="date" name="date" id="date" value="{{ old('date') }}" 
                                       class="input @error('date') input-error @enderror">
                                <p class="form-help">Doit être comprise entre les dates de l'événement</p>
                                @error('date')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="heure_debut" class="form-label">
                                    <i class="fas fa-clock mr-2 text-blue-600"></i>Heure de début
                                </label>
                                <input type="time" name="heure_debut" id="heure_debut" value="{{ old('heure_debut') }}" 
                                       class="input @error('heure_debut') input-error @enderror">
                                @error('heure_debut')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="heure_fin" class="form-label">
                                    <i class="fas fa-clock mr-2 text-blue-600"></i>Heure de fin
                                </label>
                                <input type="time" name="heure_fin" id="heure_fin" value="{{ old('heure_fin') }}" 
                                       class="input @error('heure_fin') input-error @enderror">
                                @error('heure_fin')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left mr-2 text-blue-600"></i>Description
                            </label>
                            <textarea name="description" id="description" rows="4" 
                                      class="input @error('description') input-error @enderror"
                                      placeholder="Description de l'atelier...">{{ old('description') }}</textarea>
                            <p class="form-help">Détaillez le contenu et les objectifs de l'atelier</p>
                            @error('description')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Speakers -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-microphone mr-2 text-blue-600"></i>Speakers
                            </label>
                            <p class="form-help mb-3">
                                Associez un ou plusieurs speakers a cet atelier.
                                <a href="{{ route('admin.speakers.create') }}" class="text-blue-600 hover:underline ml-1">Ajouter un speaker via page dediee</a>
                            </p>
                            @php
                                $selectedSpeakers = old('speakers', []);
                            @endphp
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @forelse($speakers as $speaker)
                                    <label class="flex items-center gap-3 p-3 rounded-lg border border-neutral-200 dark:border-neutral-700 hover:border-blue-400 transition">
                                        <input type="checkbox"
                                               name="speakers[]"
                                               value="{{ $speaker->id_speaker }}"
                                               class="rounded border-neutral-300 text-blue-600 focus:ring-blue-500"
                                               {{ in_array($speaker->id_speaker, $selectedSpeakers) ? 'checked' : '' }}>
                                        <span class="text-sm text-neutral-800 dark:text-neutral-200">
                                            {{ trim(($speaker->prenom ?? '') . ' ' . ($speaker->nom ?? '')) ?: ('Speaker #' . $speaker->id_speaker) }}
                                        </span>
                                    </label>
                                @empty
                                    <p class="text-sm text-neutral-500">Aucun speaker actif disponible.</p>
                                @endforelse
                            </div>
                            @error('speakers')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                            @error('speakers.*')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nouveau speaker -->
                        <div class="form-group">
                            <details class="rounded-lg border border-blue-200 dark:border-blue-800 bg-blue-50/50 dark:bg-blue-900/10 p-4">
                                <summary class="cursor-pointer font-semibold text-blue-800 dark:text-blue-300 list-none flex items-center justify-between">
                                    <span><i class="fas fa-user-plus mr-2"></i>Ajouter un nouveau speaker</span>
                                    <span class="text-xs text-blue-600 dark:text-blue-400">Optionnel</span>
                                </summary>
                                <p class="form-help mt-3 mb-3">Remplissez ce bloc pour creer un speaker et l'associer automatiquement a cet atelier.</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="new_speaker_nom" class="form-label">Nom</label>
                                        <input type="text"
                                               id="new_speaker_nom"
                                               name="new_speaker[nom]"
                                               value="{{ old('new_speaker.nom') }}"
                                               class="input @error('new_speaker.nom') input-error @enderror"
                                               placeholder="Nom du speaker">
                                        @error('new_speaker.nom')
                                            <p class="form-error">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="new_speaker_prenom" class="form-label">Prenom</label>
                                        <input type="text"
                                               id="new_speaker_prenom"
                                               name="new_speaker[prenom]"
                                               value="{{ old('new_speaker.prenom') }}"
                                               class="input @error('new_speaker.prenom') input-error @enderror"
                                               placeholder="Prenom du speaker">
                                        @error('new_speaker.prenom')
                                            <p class="form-error">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="new_speaker_email" class="form-label">Email</label>
                                        <input type="email"
                                               id="new_speaker_email"
                                               name="new_speaker[email]"
                                               value="{{ old('new_speaker.email') }}"
                                               class="input @error('new_speaker.email') input-error @enderror"
                                               placeholder="email@domaine.com">
                                        @error('new_speaker.email')
                                            <p class="form-error">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="new_speaker_poste" class="form-label">Poste</label>
                                        <input type="text"
                                               id="new_speaker_poste"
                                               name="new_speaker[poste]"
                                               value="{{ old('new_speaker.poste') }}"
                                               class="input @error('new_speaker.poste') input-error @enderror"
                                               placeholder="Ex: CTO">
                                        @error('new_speaker.poste')
                                            <p class="form-error">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="md:col-span-2">
                                        <label for="new_speaker_company" class="form-label">Entreprise</label>
                                        <input type="text"
                                               id="new_speaker_company"
                                               name="new_speaker[company]"
                                               value="{{ old('new_speaker.company') }}"
                                               class="input @error('new_speaker.company') input-error @enderror"
                                               placeholder="Entreprise du speaker">
                                        @error('new_speaker.company')
                                            <p class="form-error">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="md:col-span-2">
                                        <label for="new_speaker_bio" class="form-label">Bio</label>
                                        <textarea id="new_speaker_bio"
                                                  name="new_speaker[bio]"
                                                  rows="3"
                                                  class="input @error('new_speaker.bio') input-error @enderror"
                                                  placeholder="Courte presentation du speaker">{{ old('new_speaker.bio') }}</textarea>
                                        @error('new_speaker.bio')
                                            <p class="form-error">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="md:col-span-2">
                                        <label for="new_speaker_photo" class="form-label">Photo</label>
                                        <input type="file"
                                               id="new_speaker_photo"
                                               name="new_speaker[photo]"
                                               accept="image/*"
                                               class="input @error('new_speaker.photo') input-error @enderror">
                                        <p class="form-help">Formats acceptes: JPG, PNG, GIF (max 2MB)</p>
                                        @error('new_speaker.photo')
                                            <p class="form-error">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </details>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end space-x-4 pt-6 border-t border-neutral-100 dark:border-neutral-700">
                            <a href="{{ route('ateliers.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-2"></i>Retour aux ateliers
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus mr-2"></i>Ajouter l'atelier
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const eventSelect = document.getElementById('id_event');
        const dateInput = document.getElementById('date');

        function applyEventRange(option) {
            if (!option || !option.value) {
                dateInput.removeAttribute('min');
                dateInput.removeAttribute('max');
                dateInput.removeAttribute('disabled');
                return;
            }
            const start = option.dataset.start;
            const end = option.dataset.end;
            if (start && end) {
                dateInput.min = start;
                dateInput.max = end;
                dateInput.removeAttribute('disabled');
                // If currently selected date is outside range, clear it
                if (dateInput.value) {
                    if ((dateInput.value < start) || (dateInput.value > end)) {
                        dateInput.value = '';
                    }
                }
            } else {
                dateInput.setAttribute('disabled', 'disabled');
            }
        }

        // Apply on change
        eventSelect.addEventListener('change', function () {
            const opt = eventSelect.selectedOptions[0];
            applyEventRange(opt);
        });

        // On load, if an event is pre-selected, apply its range
        const initialOpt = eventSelect.selectedOptions[0];
        if (initialOpt && initialOpt.value !== '') {
            applyEventRange(initialOpt);
        }
    });
    </script>
    @endpush
@endsection
