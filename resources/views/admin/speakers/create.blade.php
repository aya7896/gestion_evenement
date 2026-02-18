@extends('layouts.app')

@section('content')
<div class="container-custom">
    <div class="max-w-3xl mx-auto card">
        <div class="card-header">
            <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Ajouter un speaker</h1>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.speakers.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Nom</label>
                        <input type="text" name="nom" value="{{ old('nom') }}" class="input @error('nom') input-error @enderror" required>
                        @error('nom') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Prenom</label>
                        <input type="text" name="prenom" value="{{ old('prenom') }}" class="input @error('prenom') input-error @enderror" required>
                        @error('prenom') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="input @error('email') input-error @enderror">
                        @error('email') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Telephone</label>
                        <input type="text" name="telephone" value="{{ old('telephone') }}" class="input @error('telephone') input-error @enderror">
                        @error('telephone') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Poste</label>
                        <input type="text" name="poste" value="{{ old('poste') }}" class="input @error('poste') input-error @enderror">
                        @error('poste') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Entreprise</label>
                        <input type="text" name="company" value="{{ old('company') }}" class="input @error('company') input-error @enderror">
                        @error('company') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Bio</label>
                        <textarea name="bio" rows="4" class="input @error('bio') input-error @enderror">{{ old('bio') }}</textarea>
                        @error('bio') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Photo</label>
                        <input type="file" name="photo" class="input @error('photo') input-error @enderror">
                        @error('photo') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.speakers.index') }}" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

