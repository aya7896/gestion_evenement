{{-- resources/views/templates/tech/inscription.blade.php --}}
@extends('landing.Template 3.layouts.app')

@section('title', 'Registration - ' . $evenement->titre)

@section('content')
<section class="min-h-screen pt-32 pb-20 bg-black">
    <div class="container mx-auto px-4 max-w-2xl">
        
        <div class="mb-8">
            <div class="flex items-center gap-2 text-cyan-400 font-mono text-sm mb-4">
                <span class="animate-pulse">➜</span>
                <span>~</span>
                <span class="text-purple-400">./register.sh</span>
                <span class="text-gray-500">--event={{ $evenement->id_event }}</span>
            </div>
            <h1 class="text-4xl font-bold text-white font-mono mb-2">
                NEW_USER<span class="text-cyan-400">_REGISTRATION</span>
            </h1>
            <div class="h-[2px] w-32 bg-gradient-to-r from-cyan-400 to-purple-400"></div>
        </div>

        <div class="bg-gray-900 border border-cyan-500/30 rounded-lg p-8 shadow-[0_0_30px_rgba(34,211,238,0.1)]">
            <!-- Event Info Header -->
            <div class="mb-8 pb-6 border-b border-cyan-500/20">
                <div class="flex items-center gap-2 text-cyan-400 font-mono text-xs mb-2">
                    <i class="fas fa-info-circle"></i>
                    TARGET_EVENT
                </div>
                <h2 class="text-2xl font-bold text-white font-mono">{{ $evenement->titre }}</h2>
                <div class="flex gap-4 mt-2 text-sm text-gray-400 font-mono">
                    <span><i class="far fa-calendar text-cyan-400 mr-2"></i>{{ $evenement->date_heure_debut?->format('Y-m-d H:i') }}</span>
                    <span><i class="fas fa-map-marker-alt text-purple-400 mr-2"></i>{{ $evenement->lieu ?? 'TBD' }}</span>
                </div>
            </div>

            @if($errors->any())
                <div class="bg-red-900/20 border border-red-500/30 p-4 mb-6 rounded">
                    <div class="text-red-400 font-mono text-sm mb-2">[ERROR] Validation failed:</div>
                    <ul class="list-disc list-inside text-red-400/80 text-xs font-mono">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('inscription.store', $evenement) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-cyan-400 font-mono text-xs mb-2 uppercase">
                            <i class="fas fa-user mr-2"></i>First_Name *
                        </label>
                        <input type="text" name="prenom" value="{{ old('prenom') }}" required
                               class="w-full bg-black border border-cyan-500/30 text-white px-4 py-3 rounded font-mono focus:border-cyan-400 focus:shadow-[0_0_10px_rgba(34,211,238,0.3)] outline-none transition">
                    </div>
                    <div>
                        <label class="block text-cyan-400 font-mono text-xs mb-2 uppercase">
                            <i class="fas fa-user mr-2"></i>Last_Name *
                        </label>
                        <input type="text" name="nom" value="{{ old('nom') }}" required
                               class="w-full bg-black border border-cyan-500/30 text-white px-4 py-3 rounded font-mono focus:border-cyan-400 focus:shadow-[0_0_10px_rgba(34,211,238,0.3)] outline-none transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-purple-400 font-mono text-xs mb-2 uppercase">
                            <i class="fas fa-envelope mr-2"></i>Email *
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full bg-black border border-purple-500/30 text-white px-4 py-3 rounded font-mono focus:border-purple-400 focus:shadow-[0_0_10px_rgba(168,85,247,0.3)] outline-none transition">
                    </div>
                    <div>
                        <label class="block text-purple-400 font-mono text-xs mb-2 uppercase">
                            <i class="fas fa-phone mr-2"></i>Phone *
                        </label>
                        <input type="tel" name="telephone" value="{{ old('telephone') }}" required
                               class="w-full bg-black border border-purple-500/30 text-white px-4 py-3 rounded font-mono focus:border-purple-400 focus:shadow-[0_0_10px_rgba(168,85,247,0.3)] outline-none transition">
                    </div>
                </div>

                <div>
                    <label class="block text-green-400 font-mono text-xs mb-2 uppercase">
                        <i class="fas fa-lock mr-2"></i>Password *
                    </label>
                    <input type="password" name="password" required
                           class="w-full bg-black border border-green-500/30 text-white px-4 py-3 rounded font-mono focus:border-green-400 focus:shadow-[0_0_10px_rgba(74,222,128,0.3)] outline-none transition">
                </div>

                <div>
                    <label class="block text-gray-400 font-mono text-xs mb-2 uppercase">
                        <i class="fas fa-building mr-2"></i>Organization
                    </label>
                    <input type="text" name="company" value="{{ old('company') }}"
                           class="w-full bg-black border border-gray-700 text-white px-4 py-3 rounded font-mono focus:border-gray-500 outline-none transition">
                </div>

                @if($evenement->ateliers->count() > 0)
                <div class="border border-cyan-500/20 rounded-lg p-6 bg-black/50">
                    <div class="flex items-center gap-2 text-cyan-400 font-mono text-xs mb-4 uppercase">
                        <i class="fas fa-code-branch mr-2"></i>Select_Workshops []
                    </div>
                    <div class="space-y-3 max-h-64 overflow-y-auto custom-scrollbar">
                        @foreach($evenement->ateliers as $atelier)
                        @php
                            $registeredCount = $atelierCapacities[$atelier->id_atelier] ?? 0;
                            $isFull = $atelier->capacite && $registeredCount >= $atelier->capacite;
                        @endphp
                        <label class="flex items-center gap-4 p-3 rounded bg-gray-900/50 hover:bg-gray-800/50 transition cursor-pointer {{ $isFull ? 'opacity-50' : '' }}">
                            <input type="checkbox" name="ateliers[]" value="{{ $atelier->id_atelier }}" 
                                   {{ $isFull ? 'disabled' : '' }}
                                   class="w-5 h-5 rounded border-cyan-500/30 bg-black text-cyan-400 focus:ring-cyan-500 focus:ring-offset-0">
                            <div class="flex-1">
                                <div class="text-white font-mono text-sm">{{ $atelier->titre }}</div>
                                <div class="text-gray-500 text-xs font-mono">{{ $atelier->heure_debut?->format('H:i') }} - {{ $atelier->heure_fin?->format('H:i') }}</div>
                            </div>
                            @if($atelier->capacite)
                                <div class="text-xs font-mono {{ $isFull ? 'text-red-400' : 'text-green-400' }}">
                                    [{{ $registeredCount }}/{{ $atelier->capacite }}]
                                </div>
                            @endif
                        </label>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="border border-purple-500/20 rounded-lg p-6 bg-black/50">
                    <label class="flex items-start gap-4 cursor-pointer">
                        <input type="checkbox" name="one_to_one" value="1" {{ old('one_to_one') ? 'checked' : '' }}
                               class="w-5 h-5 rounded border-purple-500/30 bg-black text-purple-400 focus:ring-purple-500 focus:ring-offset-0 mt-1">
                        <div>
                            <div class="text-purple-400 font-mono text-sm uppercase mb-1">
                                <i class="fas fa-user-secret mr-2"></i>Enable_One-to-One_Mode
                            </div>
                            <div class="text-gray-500 text-xs font-mono">Access exclusive mentoring session</div>
                        </div>
                    </label>
                </div>

                <div id="advanced-fields" class="space-y-6 {{ old('one_to_one') ? '' : 'hidden' }} border-t border-cyan-500/20 pt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-cyan-400 font-mono text-xs mb-2 uppercase">Avatar_Upload</label>
                            <input type="file" name="photo" accept="image/*"
                                   class="w-full bg-black border border-cyan-500/30 text-white px-4 py-3 rounded font-mono file:mr-4 file:py-2 file:px-4 file:rounded file:border file:border-cyan-500 file:bg-transparent file:text-cyan-400 file:text-xs file:font-mono hover:file:bg-cyan-400/10 transition">
                        </div>
                        <div>
                            <label class="block text-cyan-400 font-mono text-xs mb-2 uppercase">Job_Title</label>
                            <input type="text" name="poste" value="{{ old('poste') }}"
                                   class="w-full bg-black border border-cyan-500/30 text-white px-4 py-3 rounded font-mono focus:border-cyan-400 outline-none transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-cyan-400 font-mono text-xs mb-2 uppercase">LinkedIn_URL</label>
                        <input type="url" name="lien_linkedin" value="{{ old('lien_linkedin') }}"
                               class="w-full bg-black border border-cyan-500/30 text-white px-4 py-3 rounded font-mono focus:border-cyan-400 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-cyan-400 font-mono text-xs mb-2 uppercase">Bio_String</label>
                        <textarea name="presentation" rows="3"
                                  class="w-full bg-black border border-cyan-500/30 text-white px-4 py-3 rounded font-mono focus:border-cyan-400 outline-none transition">{{ old('presentation') }}</textarea>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-800">
                    <label class="flex items-start gap-4 cursor-pointer">
                        <input type="checkbox" id="terms" required
                               class="w-5 h-5 rounded border-green-500/30 bg-black text-green-400 focus:ring-green-500 focus:ring-offset-0 mt-1">
                        <span class="text-gray-400 text-xs font-mono">
                            I agree to the <span class="text-cyan-400">terms_of_service</span> and <span class="text-purple-400">privacy_policy</span> *
                        </span>
                    </label>
                </div>

                <button type="submit" class="w-full group bg-cyan-400 text-black py-4 rounded font-bold font-mono uppercase tracking-wider hover:bg-cyan-300 transition shadow-[0_0_20px_rgba(34,211,238,0.3)] hover:shadow-[0_0_30px_rgba(34,211,238,0.5)] flex items-center justify-center gap-2">
                    <i class="fas fa-rocket group-hover:animate-bounce"></i>
                    EXECUTE_REGISTRATION()
                </button>
            </form>
        </div>
    </div>
</section>

<script>
    document.querySelector('input[name="one_to_one"]')?.addEventListener('change', function() {
        const fields = document.getElementById('advanced-fields');
        fields.classList.toggle('hidden', !this.checked);
    });
</script>
@endsection
