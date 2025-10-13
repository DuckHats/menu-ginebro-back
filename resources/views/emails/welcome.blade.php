<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Benvingut al Menjador del Ginebró!</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
            color: #111827;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background-color: #009ca6;
            color: #ffffff;
            padding: 24px;
            text-align: center;
        }

        .header h2 {
            margin: 0;
            font-size: 22px;
        }

        .content {
            padding: 24px;
        }

        .content h1 {
            font-size: 20px;
            color: #065e63;
            margin-top: 0;
        }

        .content p {
            font-size: 16px;
            line-height: 1.6;
            margin: 16px 0;
        }

        .content ul {
            padding-left: 20px;
            margin: 12px 0 24px;
        }

        .content ul li {
            margin-bottom: 8px;
        }

        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #009ca6;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            border: 1px solid #007b85;
            transition: background-color 0.2s ease-in-out;
        }

        .button:hover {
            background-color: #007b85;
        }

        .footer {
            text-align: center;
            font-size: 13px;
            color: #6b7280;
            padding: 16px;
            background-color: #f9fafb;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Benvingut al Menjador del Ginebró!</h2>
        </div>
        <div class="content">
            <h1>Hola, {{ $user->name }}!</h1>
            <p>És un plaer donar-te la benvinguda a la nostra plataforma del menjador! Des d’aquí podràs:</p>
            <ul>
                <li>Fer noves comandes al menjador</li>
                <li>Veure l’historial de les últimes comandes</li>
            </ul>
            <p>Si tens qualsevol dubte o necessitat, el nostre equip està a la teva disposició.</p>
            <p>Que tinguis una experiència fantàstica amb nosaltres!</p>
            <div style="text-align: center; margin-top: 32px;">
                <a href="{{ $platformUrl }}" class="button" target="_blank">Accedeix a la Plataforma</a>
            </div>
        </div>
        <div class="footer">
            &copy; 2025 Menjador Ginebró. Tots els drets reservats.
        </div>
    </div>
</body>

</html>
