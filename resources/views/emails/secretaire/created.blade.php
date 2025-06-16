<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Compte Secrétaire Créé</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .email-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: auto;
        }
        h1 {
            color: #333;
        }
        p {
            color: #555;
            line-height: 1.5;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #999;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h1>Bienvenue, {{ $secretaire->prenom }} {{ $secretaire->nom }} !</h1>
        <p>
            Votre compte secrétaire a été créé avec succès sur notre plateforme.
        </p>
        <p>
            <strong>Vos identifiants :</strong><br>
            Email : {{ $secretaire->email }}<br>
            Mot de passe temporaire : <strong>{{ $password }}</strong>
        </p>
        <p>
            Nous vous recommandons de changer votre mot de passe dès votre première connexion.
        </p>
        <p class="footer">
            Merci,<br>
            L'équipe administration
        </p>
    </div>
</body>
</html>
