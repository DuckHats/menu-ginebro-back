<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificació de correu electrònic</title>
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Confirmació del teu correu electrònic</h2>
        </div>
        <div class="content">
            <p>Hola,</p>
            <p>Benvingut al nostre casal d'estiu! Per començar a utilitzar la nostra plataforma i accedir a la informació dels teus fills, verifica el teu correu electrònic amb aquest codi:</p>
            <h3>{{ $verificationCode }}</h3>
            <p>Si no has sol·licitat aquest registre, pots ignorar aquest missatge.</p>
        </div>
        <div class="footer">
            <p>&copy; 2025 Casal d'Estiu. Tots els drets reservats.</p>
        </div>
    </div>
</body>
</html>
