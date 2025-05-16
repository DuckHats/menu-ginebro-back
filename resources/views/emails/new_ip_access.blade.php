<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nou inici de sessió detectat</title>
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
        ul {
            list-style-type: none;
            padding: 0;
        }
        ul li {
            margin: 5px 0;
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
            <h2>Nou inici de sessió detectat</h2>
        </div>
        <div class="content">
            <p>Hola,</p>
            <p>Per garantir la seguretat del teu compte al casal d'estiu, t'informem que s'ha iniciat sessió des d'una nova ubicació.</p>
            <p>Si has estat tu, no cal que facis res. Si no ho reconeixes, posa't en contacte amb nosaltres immediatament.</p>
            <h3>Detalls de l'inici de sessió:</h3>
            <ul>
                <li><strong>Data i Hora:</strong> {{ $ipDetails['date'] }}</li>
                <li><strong>Adreça IP:</strong> {{ $ipDetails['ip'] }}</li>
                <li><strong>Ubicació:</strong> {{ $ipDetails['location']['city'] ?? 'Desconegut' }}, {{ $ipDetails['location']['country'] ?? 'Desconegut' }}</li>
            </ul>
            <p>Si necessites ajuda, fes clic al botó següent per contactar-nos:</p>
            <a href="#" class="button">Contacta'ns</a>
        </div>
        <div class="footer">
            <p>Gràcies per confiar en el nostre casal!</p>
            <p>&copy; 2025 Casal d'Estiu. Tots els drets reservats.</p>
        </div>
    </div>
</body>
</html>