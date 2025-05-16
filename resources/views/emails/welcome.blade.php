<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benvingut al Casal d'Estiu!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #F1F8E9;
            color: #2E7D32;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            background-color: #81C784;
            padding: 15px;
            border-radius: 10px 10px 0 0;
            color: white;
        }
        .content {
            padding: 20px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #555;
            margin-top: 20px;
        }
        .button {
            display: inline-block;
            padding: 10px 15px;
            margin-top: 10px;
            background-color: #388E3C;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Benvingut al Casal d'Estiu!</h2>
        </div>
        <div class="content">
            <h1>Hola, {{ $user->username }}!</h1>
            <p>És un plaer donar-te la benvinguda al nostre casal d'estiu! A través de la nostra plataforma, podràs:</p>
            <ul>
                <li>Seguir les activitats dels teus fills</li>
                <li>Veure fotos i moments destacats</li>
                <li>Conèixer els monitors</li>
                <li>Rebre informació actualitzada</li>
            </ul>
            <p>Si tens qualsevol dubte o necessitat, el nostre equip està a la teva disposició.</p>
            <p>Que tinguis una experiència fantàstica amb nosaltres!</p>
            <a href="#" class="button">Accedeix a la Plataforma</a>
        </div>
        <div class="footer">
            <p>&copy; 2025 Casal d'Estiu. Tots els drets reservats.</p>
        </div>
    </div>
</body>
</html>
