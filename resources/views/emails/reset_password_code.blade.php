@extends('emails.layout')

@section('title', 'Restabliment de Contrasenya')

@section('content')
    <div class="badge">SEGURETAT</div>
    <h2>Restableix la teva contrasenya</h2>
    <p>Hem rebut una sol·licitud per canviar la contrasenya del teu compte al <strong>Ginebró</strong>. Per continuar,
        utilitza el següent codi:</p>

    <div
        style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 20px; padding: 40px; text-align: center; margin: 32px 0;">
        <span
            style="display: block; font-size: 13px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 700; margin-bottom: 16px;">Codi
            de recuperació</span>
        <div
            style="display: inline-block; padding: 20px 40px; background-color: #009ca6; color: #ffffff; font-size: 36px; font-weight: 900; letter-spacing: 0.25em; border-radius: 16px; box-shadow: 0 8px 16px rgba(0, 156, 166, 0.15);">
            {{ $code }}
        </div>
        <p style="margin: 24px 0 0; color: #94a3b8; font-size: 12px; font-weight: 500;">Aquest codi és d'ús únic i caducarà
            en 15 minuts.</p>
    </div>

    <p style="text-align: center;">Si el codi caduca, hauràs de tornar a realitzar la sol·licitud des de la pantalla d'inici
        de sessió.</p>

    <div class="divider"></div>

    <p style="font-size: 14px; color: #94a3b8; text-align: center; margin-bottom: 0;">Si no has estat tu, et recomanem que
        revisis la seguretat del teu correu electrònic.</p>
@endsection
