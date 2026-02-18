<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Code de verification</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111827;">
    <h2>Verification de votre inscription</h2>
    <p>Bonjour {{ $inscription->user->prenom ?? 'Participant' }},</p>
    <p>Utilisez le code suivant pour verifier votre inscription:</p>
    <p style="font-size: 28px; font-weight: 700; letter-spacing: 6px;">{{ $code }}</p>
    <p>Ce code expire dans {{ $expiresInMinutes }} minutes.</p>
    <p>Si vous n'etes pas a l'origine de cette demande, ignorez cet email.</p>
</body>
</html>
