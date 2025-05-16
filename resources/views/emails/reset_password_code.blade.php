<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restabliment de Contrasenya</title>
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
            <h2>Sol·licitud de restabliment de contrasenya</h2>
        </div>
        <div class="content">
            <p>Hola,</p>
            <p>Hem rebut la teva sol·licitud per restablir la contrasenya del teu compte al nostre casal d'estiu. Utilitza el següent codi per completar el procés:</p>
            <h3>{{ $code }}</h3>
            <p>Aquest codi expira en 15 minuts. Si no has fet aquesta sol·licitud, ignora aquest correu.</p>
        </div>
        <div class="footer">
            <p>&copy; 2025 Casal d'Estiu. Tots els drets reservats.</p>
        </div>
    </div>
</body>
</html>
