@extends('emails.layout')

@section('title', 'Contrasenya Actualitzada')

@section('content')
    <div class="badge">SEGURETAT</div>
    <h2>Contrasenya canviada amb èxit</h2>
    <p>T'informem que la contrasenya del teu compte al <strong>Ginebró</strong> s'ha actualitzat correctament. Ja pots
        accedir a la plataforma amb les teves noves credencials.</p>

    <div
        style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 20px; padding: 40px; text-align: center; margin: 32px 0;">
        <div style="margin-bottom: 24px; color: #009ca6;">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                style="display: block; margin: 0 auto;">
                <path
                    d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
        <div style="font-weight: 700; color: #0f172a; font-size: 18px; margin-bottom: 8px;">Compte Actualitzat</div>
        <p style="margin: 0; font-size: 14px; color: #64748b;">La teva nova contrasenya ja està activa.</p>

        <div style="margin-top: 32px;">
            <a href="{{ config('services.frontend.url') }}/login" class="button">Inicia Sessió</a>
        </div>
    </div>

    <div class="divider"></div>

    <p style="font-size: 14px; color: #94a3b8; text-align: center; margin-bottom: 0;">Si no has realitzat aquest canvi,
        posa't en contacte amb nosaltres immediatament per bloquejar el teu compte.</p>
@endsection
