<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Plaquette - {{ $evenement->titre }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; color: #222; }
        .header { text-align: center; margin-bottom: 24px; }
        .title { font-size: 28px; font-weight: bold; margin: 12px 0; }
        .meta { color: #666; font-size: 12px; margin-bottom: 18px; }
        .image { width: 100%; max-height: 300px; object-fit: cover; margin-bottom: 16px; }
        .section { margin-bottom: 16px; }
        .description { line-height: 1.4; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">{{ $evenement->titre }}</div>
        <div class="meta">{{ $evenement->entreprise?->nom ?? '' }} • {{ ucfirst($evenement->type) }} • {{ ucfirst($evenement->visibility) }}</div>
    </div>

    @php
        $imagePath = null;
        if ($evenement->image) {
            if (file_exists(public_path('storage/' . $evenement->image))) {
                $imagePath = public_path('storage/' . $evenement->image);
            } elseif (file_exists($evenement->image)) {
                $imagePath = $evenement->image;
            }
        }
    @endphp

    @if($imagePath)
        <div class="section">
            <img src="{{ $imagePath }}" class="image" alt="{{ $evenement->titre }}" />
        </div>
    @endif

    <div class="section">
        <h3>Dates</h3>
        <p class="meta">Début: {{ \Illuminate\Support\Carbon::parse($evenement->date_heure_debut)->format('d/m/Y H:i') }} — Fin: {{ \Illuminate\Support\Carbon::parse($evenement->date_heure_fin)->format('d/m/Y H:i') }}</p>
    </div>

    <div class="section">
        <h3>Description</h3>
        <div class="description">{!! nl2br(e($evenement->description)) !!}</div>
    </div>

    <hr/>
    <div class="meta" style="margin-top:20px; font-size:11px; text-align:center;">Généré le {{ \Illuminate\Support\Carbon::now()->format('d/m/Y H:i') }}</div>
</body>
</html>