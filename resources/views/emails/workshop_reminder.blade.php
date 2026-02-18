<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Rappel d'atelier</title>
  <style>
    body { font-family: Arial, Helvetica, sans-serif; color:#111; }
    .container{ max-width:600px;margin:0 auto;padding:24px;background:#fff;border-radius:8px }
    .hero{ background:#f7f7f9;padding:18px;border-radius:6px;margin-bottom:12px }
    .cta{ display:inline-block;padding:10px 16px;background:#ff3071;color:#fff;border-radius:6px;text-decoration:none }
  </style>
</head>
<body>
  <div class="container">
    <h2>Rappel : {{ $atelier->titre ?? 'Votre atelier' }}</h2>
    <div class="hero">
      <p><strong>Date :</strong> {{ optional($atelier->date)->format('d M Y') ?? '—' }}</p>
      <p><strong>Heure de début :</strong> {{ optional($atelier->heure_debut)->format('H:i') ?? '—' }}</p>
      <p><strong>Lieu :</strong> {{ $atelier->lieu ?? ($atelier->evenement->lieu ?? 'En ligne') }}</p>
    </div>

    <p>Bonjour {{ $inscription->user->name ?? ($inscription->company ?? 'participant') }},</p>
    <p>Nous vous rappelons que l'atelier <strong>{{ $atelier->titre }}</strong> commencera dans environ une heure.</p>

    <p>Si vous ne pouvez plus participer, merci de vous désinscrire afin de libérer votre place.</p>

    <p>
      <a class="cta" href="{{ $atelier->evenement ? route('public.evenement.landing', $atelier->evenement) : url('/') }}">Voir l'événement</a>
    </p>

    <p style="font-size:12px;color:#666;margin-top:16px">Ce message a été envoyé automatiquement. Merci de ne pas y répondre.</p>
  </div>
</body>
</html>
