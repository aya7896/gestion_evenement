@extends('landing.layouts.app')

@section('title', $evenement->titre)

@section('content')

<!-- Hero Section avec image de l'événement -->
<section class="relative h-96 bg-gradient-to-r from-blue-600 to-blue-800">
    @if($evenement->image)
        <div class="absolute inset-0 bg-black opacity-50"></div>
        @php
            $img = $evenement->image ?? null;
            $imgNorm = $img ? preg_replace('#^(/)?(storage/|public/|storage/app/public/)#', '', $img) : null;
            if ($imgNorm && \Illuminate\Support\Facades\Storage::disk('public')->exists($imgNorm)) {
                $imgUrl = asset('storage/' . $imgNorm);
            } elseif ($img && file_exists($img)) {
                $imgUrl = asset($img);
            } else {
                $imgUrl = null;
            }
        @endphp
        @if($imgUrl)
        <img src="{{ $imgUrl }}" alt="{{ $evenement->titre }}" 
             class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
    @endif
    
    <div class="relative container mx-auto px-4 h-full flex items-center">
        <div class="text-white max-w-3xl">
            <h1 class="text-5xl font-bold mb-4">{{ $evenement->titre }}</h1>
            <p class="text-xl mb-6">{{ Str::limit($evenement->description, 150) }}</p>
            <div class="flex flex-wrap gap-4">
                <span class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-sm">
                    <i class="far fa-calendar mr-2"></i>
                    {{ \Carbon\Carbon::parse($evenement->date_debut)->format('d M Y') }}
                </span>
                <span class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-sm">
                    <i class="fas fa-map-marker-alt mr-2"></i>
                    {{ $evenement->lieu }}
                </span>
            </div>
        </div>
    </div>
</section>

<!-- Contenu principal avec sidebar -->
<div class="container mx-auto px-4 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Contenu principal -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Message de succès ou d'erreur -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 text-red-800 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Description détaillée -->
            <section id="details" class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    À propos de l'événement
                </h2>
                <div class="prose max-w-none text-gray-700 leading-relaxed">
                    {!! nl2br(e($evenement->description)) !!}
                </div>
            </section>

            <!-- Liste des ateliers -->
            <section id="ateliers" class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-chalkboard-teacher text-blue-600 mr-2"></i>
                    Ateliers disponibles
                    <span class="text-sm font-normal text-gray-500 ml-2">
                        ({{ $evenement->ateliers->count() }} ateliers)
                    </span>
                </h2>

                @forelse($evenement->ateliers as $atelier)
                    <div class="mb-6 last:mb-0 border-b last:border-0 pb-6 last:pb-0">
                        <div class="flex items-start gap-4">
                            @if($atelier->image)
                                @php
                                    $img = $atelier->image ?? null;
                                    $imgNorm = $img ? preg_replace('#^(/)?(storage/|public/|storage/app/public/)#', '', $img) : null;
                                    if ($imgNorm && \Illuminate\Support\Facades\Storage::disk('public')->exists($imgNorm)) {
                                        $imgUrl = asset('storage/' . $imgNorm);
                                    } elseif ($img && file_exists($img)) {
                                        $imgUrl = asset($img);
                                    } else {
                                        $imgUrl = null;
                                    }
                                @endphp
                                @if($imgUrl)
                                <img src="{{ $imgUrl }}" 
                                     alt="{{ $atelier->titre }}"
                                     class="w-24 h-24 rounded-lg object-cover">
                            @else
                                <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-chalkboard text-white text-3xl"></i>
                                </div>
                            @endif

                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-800 mb-2">
                                    {{ $atelier->titre }}
                                </h3>
                                
                                <div class="flex flex-wrap gap-3 mb-3 text-sm text-gray-600">
                                    <span>
                                        <i class="far fa-calendar mr-1"></i>
                                        {{ $atelier->date->format('d/m/Y') }}
                                    </span>
                                    <span>
                                        <i class="far fa-clock mr-1"></i>
                                        {{ $atelier->heure_debut->format('H:i') }} - {{ $atelier->heure_fin->format('H:i') }}
                                    </span>
                                    <span>
                                        <i class="fas fa-users mr-1"></i>
                                        {{ $atelier->capacite }} places
                                    </span>
                                </div>

                                @if($atelier->sujet)
                                    <p class="text-gray-600 mb-3">{{ $atelier->sujet }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-3"></i>
                        <p>Aucun atelier disponible pour le moment</p>
                    </div>
                @endforelse
            </section>

        </div>

        <!-- Sidebar -->
        <div>
            @include('landing.layouts.sidebar')
        </div>

    </div>
</div>

@endsection