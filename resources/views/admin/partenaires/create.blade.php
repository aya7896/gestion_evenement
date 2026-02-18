@extends('layouts.app')

@section('content')
<div class="container-custom">
    <div class="max-w-3xl mx-auto card">
        <div class="card-header">
            <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Ajouter un sponsor</h1>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.partenaires.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Nom</label>
                        <input type="text" name="nom" value="{{ old('nom') }}" class="input @error('nom') input-error @enderror" required>
                        @error('nom') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Type</label>
                        <select name="type" class="input @error('type') input-error @enderror" required>
                            <option value="">Selectionner</option>
                            @foreach($types as $key => $label)
                                <option value="{{ $key }}" {{ old('type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('type') <p class="form-error">{{ $message }}</p> @enderror
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
                        <label class="form-label">Site web</label>
                        <input type="url" name="site_web" value="{{ old('site_web') }}" class="input @error('site_web') input-error @enderror">
                        @error('site_web') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Ordre</label>
                        <input type="number" name="ordre" min="0" value="{{ old('ordre', 0) }}" class="input @error('ordre') input-error @enderror">
                        @error('ordre') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="4" class="input @error('description') input-error @enderror">{{ old('description') }}</textarea>
                        @error('description') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Logo</label>
                        <input type="file" name="logo" class="input @error('logo') input-error @enderror">
                        @error('logo') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.partenaires.index') }}" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

