<!DOCTYPE html>
<html lang="ca">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restabliment de Contrasenya</title>
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
      color: white;
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

    .content p {
      font-size: 16px;
      line-height: 1.5;
      margin: 16px 0;
    }

    .code {
      display: inline-block;
      margin: 20px 0;
      padding: 12px 24px;
      background-color: #065e63;
      color: #ffffff;
      font-size: 18px;
      letter-spacing: 2px;
      border-radius: 8px;
      font-weight: bold;
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
      <h2>Restabliment de la teva contrasenya</h2>
    </div>
    <div class="content">
      <p>Hola,</p>
      <p>Hem rebut una sol·licitud per restablir la contrasenya del teu compte al nostre menjador.</p>
      <p>Utilitza el següent codi per completar el procés:</p>
      <div class="code">{{ $code }}</div>
      <p>Aquest codi caducarà en 15 minuts.</p>
      <p>Si no has fet aquesta sol·licitud, pots ignorar aquest missatge.</p>
    </div>
    <div class="footer">
      &copy; 2025 Menjador Ginebró. Tots els drets reservats.
    </div>
  </div>
</body>
</html>
