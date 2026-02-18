<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>
        @page { size: A6; margin: 6mm; }
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #111827; margin: 0; }
        .badge {
            border: 1px solid #D1D5DB;
            border-radius: 14px;
            overflow: hidden;
        }
        .hero {
            height: 78px;
            position: relative;
            background: #111827;
            color: #fff;
        }
        .hero img {
            width: 100%;
            height: 78px;
            object-fit: cover;
            display: block;
        }
        .hero-overlay {
            position: absolute;
            inset: 0;
            background: rgba(17, 24, 39, 0.45);
        }
        .hero-content {
            position: absolute;
            left: 12px;
            right: 12px;
            bottom: 10px;
        }
        .event-title {
            font-size: 14px;
            font-weight: 700;
            margin: 0;
            line-height: 1.2;
        }
        .event-sub {
            font-size: 10px;
            margin: 2px 0 0;
            opacity: .95;
        }
        .body {
            padding: 10px 12px 12px;
        }
        .grid {
            width: 100%;
            border-collapse: collapse;
        }
        .left {
            width: 67%;
            vertical-align: top;
            padding-right: 10px;
        }
        .right {
            width: 33%;
            vertical-align: top;
            text-align: center;
            border-left: 1px dashed #D1D5DB;
            padding-left: 10px;
        }
        .name {
            font-size: 18px;
            font-weight: 700;
            margin: 0 0 5px;
            line-height: 1.15;
            color: #111827;
        }
        .pill {
            display: inline-block;
            font-size: 9px;
            background: #EEF2FF;
            color: #3730A3;
            border: 1px solid #C7D2FE;
            border-radius: 999px;
            padding: 2px 8px;
            margin-bottom: 8px;
        }
        .meta {
            font-size: 10px;
            color: #374151;
            margin: 0 0 4px;
            word-break: break-word;
        }
        .label {
            font-weight: 700;
            color: #111827;
        }
        .workshops {
            margin-top: 8px;
            font-size: 9px;
            color: #374151;
            line-height: 1.3;
        }
        .qr-card {
            border: 1px solid #E5E7EB;
            border-radius: 10px;
            padding: 6px;
            display: inline-block;
            background: #fff;
        }
        .qr {
            width: 92px;
            height: 92px;
            display: block;
        }
        .id {
            margin-top: 6px;
            font-size: 9px;
            color: #6B7280;
        }
        .footer {
            margin-top: 8px;
            padding-top: 6px;
            border-top: 1px solid #E5E7EB;
            font-size: 9px;
            color: #6B7280;
            text-align: right;
        }
    </style>
</head>
<body>
@php
    $event = $inscription->evenements->first();
    $participantName = trim(($inscription->user->prenom ?? '') . ' ' . ($inscription->user->nom ?? ''));
    if ($participantName === '') {
        $participantName = $inscription->user->name ?? 'Participant';
    }
@endphp

<div class="badge">
    <div class="hero">
        @if(!empty($eventImageDataUri))
            <img src="{{ $eventImageDataUri }}" alt="Event image">
        @endif
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <p class="event-title">{{ $event->titre ?? 'Evenement' }}</p>
            <p class="event-sub">{{ $event->entreprise->nom ?? 'Organisation' }}</p>
        </div>
    </div>

    <div class="body">
        <table class="grid" role="presentation">
            <tr>
                <td class="left">
                    <p class="name">{{ $participantName }}</p>
                    <span class="pill">Participant</span>

                    <p class="meta"><span class="label">Email:</span> {{ $inscription->user->email ?? '-' }}</p>
                    <p class="meta"><span class="label">Tel:</span> {{ $inscription->user->telephone ?? '-' }}</p>
                    <p class="meta"><span class="label">Societe:</span> {{ $inscription->company ?? '-' }}</p>

                    @if($inscription->ateliers && $inscription->ateliers->count() > 0)
                        <div class="workshops">
                            <span class="label">Ateliers:</span>
                            {{ $inscription->ateliers->pluck('titre')->take(4)->implode(' | ') }}
                        </div>
                    @endif
                </td>
                <td class="right">
                    <div class="qr-card">
                        <img class="qr" src="{{ $qrCode }}" alt="QR Code">
                    </div>
                    <div class="id">ID #{{ $inscription->id_inscription }}</div>
                </td>
            </tr>
        </table>

        <div class="footer">
            {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>
</div>
</body>
</html>
