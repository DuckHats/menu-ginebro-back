<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Notificació Ginebró')</title>
    <style>
        /* Modern Email Reset */
        body {
            margin: 0;
            padding: 0;
            min-width: 100%;
            width: 100% !important;
            height: 100% !important;
            background-color: #f8fafc;
            -webkit-font-smoothing: antialiased;
            text-size-adjust: 100%;
        }

        table,
        td {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        /* Essential Typography */
        body,
        p,
        td {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #334155;
            font-size: 16px;
            line-height: 1.6;
        }

        /* Layout */
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f8fafc;
            padding: 48px 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }

        /* Brand Accent Bar */
        .top-bar {
            height: 4px;
            background-color: #009ca6;
        }

        /* Header */
        .header {
            padding: 40px 40px 32px 40px;
            text-align: center;
        }

        .brand-title {
            margin: 0;
            color: #009ca6;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.02em;
            text-transform: uppercase;
        }

        .brand-subtitle {
            margin: 4px 0 0 0;
            color: #94a3b8;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.05em;
        }

        /* Content Area */
        .content {
            padding: 0 48px 48px 48px;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 32px;
            background-color: #f8fafc;
            border-top: 1px solid #f1f5f9;
        }

        .footer p {
            margin: 0;
            font-size: 13px;
            color: #94a3b8;
            line-height: 1.8;
        }

        /* Global UI Elements */
        .button {
            display: inline-block;
            padding: 14px 32px;
            background-color: #009ca6;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin: 24px 0;
        }

        .badge {
            display: inline-block;
            padding: 2px 10px;
            background-color: #f0fdfa;
            color: #009ca6;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 12px;
        }

        h2 {
            color: #0f172a;
            font-size: 24px;
            font-weight: 800;
            margin: 0 0 16px 0;
            letter-spacing: -0.02em;
        }

        p {
            margin: 0 0 24px 0;
        }

        .divider {
            height: 1px;
            background-color: #f1f5f9;
            margin: 32px 0;
            border: none;
        }

        /* Mobile Styles */
        @media only screen and (max-width: 600px) {
            .wrapper {
                padding: 0;
            }

            .container {
                border-radius: 0;
                border: none;
            }

            .content {
                padding: 0 24px 32px 24px;
            }

            .header {
                padding: 32px 24px 24px 24px;
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container">
            <div class="top-bar"></div>
            <div class="header">
                <h1 class="brand-title">{{ config('app.name', 'Ginebró') }}</h1>
                <p class="brand-subtitle">MENJADOR ESCOLAR</p>
            </div>
            <div class="content">
                @yield('content')
            </div>
            <div class="footer">
                <p><strong>{{ config('app.name', 'Ginebró') }}</strong><br>
                    Escola Ginebró &bull; Carrer de l'Escola, s/n &bull; 08440 Cardedeu</p>
                <div style="height: 12px;"></div>
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tots els drets reservats.</p>
                <p style="font-size: 11px;">Aquest és un correu automàtic, per favor no responguis directament.</p>
            </div>
        </div>
    </div>
</body>

</html>
