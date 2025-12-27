@extends('emails.layout')

@section('title', 'Verifica el teu correu')

@section('content')
    <div class="badge">SEGURETAT</div>
    <h2>Verifica el teu correu</h2>
    <p>Gràcies per registrar-te al menjador del <strong>Ginebró</strong>. Per seguretat, necessitem que verifiquis la teva
        adreça electrònica.</p>

    <div
        style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 20px; padding: 40px; text-align: center; margin: 32px 0;">
        <span
            style="display: block; font-size: 13px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 700; margin-bottom: 16px;">El
            teu codi de verificació</span>
        <div
            style="display: inline-block; padding: 20px 40px; background-color: #009ca6; color: #ffffff; font-size: 36px; font-weight: 900; letter-spacing: 0.25em; border-radius: 16px; box-shadow: 0 8px 16px rgba(0, 156, 166, 0.15);">
            {{ $code }}
        </div>
        <p style="margin: 24px 0 0; color: #94a3b8; font-size: 12px; font-weight: 500;">Aquest codi és d'ús únic i caducarà
            en 15 minuts.</p>
    </div>

    <p style="text-align: center;">Introdueix aquest codi a l'aplicació per completar el procés de registre.</p>

    <div class="divider"></div>

    <p style="font-size: 14px; color: #94a3b8; text-align: center; margin-bottom: 0;">Si no has intentat registrar-te, pots
        ignorar aquest missatge amb total seguretat.</p>
@endsection
