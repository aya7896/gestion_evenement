<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>
        @page { size: A6; margin: 8mm; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #111; }
        .badge { width: 100%; height: 100%; display: flex; flex-direction: row; align-items: stretch; }
        .left { flex: 1; padding-right: 8px; border-right: 1px solid #ddd; }
        .right { width: 85pt; padding-left: 8px; display:flex; flex-direction:column; align-items:center; justify-content:center; }
        .event { font-size: 10px; color:#666; margin-bottom:6px; }
        .name { font-size: 20px; font-weight:700; margin-bottom:4px; }
        .company { font-size: 12px; color:#333; margin-bottom:6px; }
        .meta { font-size: 10px; color:#444; margin-bottom:4px; }
        .workshops { font-size: 10px; color:#333; margin-top:8px; }
        .qr { width: 120px; height: 120px; }
        .small { font-size: 9px; color:#666; }
    </style>
</head>
<body>
    <div class="badge">
        <div class="left">
            <div class="event">{{ $inscription->evenements->first()->titre ?? 'Événement' }}</div>
            <div class="name">{{ $inscription->user->name ?? ($inscription->user->prenom . ' ' . $inscription->user->nom) }}</div>
            @if($inscription->company)
                <div class="company">{{ $inscription->company }}</div>
            @endif

            <div class="meta">Email: {{ $inscription->user->email ?? '-' }}</div>
            @if(!empty($inscription->user->telephone))
                <div class="meta">Tel: {{ $inscription->user->telephone }}</div>
            @endif

            @if($inscription->ateliers && $inscription->ateliers->count() > 0)
                <div class="workshops">
                    <strong>Ateliers:</strong>
                    <div class="small">
                        {{ $inscription->ateliers->pluck('titre')->implode(', ') }}
                    </div>
                </div>
            @endif

            <div style="position: absolute; bottom: 12mm; font-size:9px; color:#999;">{{ $inscription->evenements->first()->entreprise->nom ?? '' }}</div>
        </div>
        <div class="right">
            <div>
                <img class="qr" src="data:image/png;base64,{{ $qrCode }}" alt="QR Code">
            </div>
            <div class="small" style="margin-top:6px; text-align:center;">ID: {{ $inscription->id_inscription }}</div>
        </div>
    </div>
</body>
</html>