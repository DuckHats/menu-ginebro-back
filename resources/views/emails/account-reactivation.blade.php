@extends('emails.layout')

@section('title', 'Compte Reactivat')

@section('content')
    <div class="badge">COMPTE ACTIU</div>
    <h2>Bentornat/da, {{ $user->name }}!</h2>
    <p>T'informem que el teu compte al menjador del <strong>Ginebró</strong> ha estat reactivat correctament.</p>

    <div class="divider"></div>

    <p>Ja pots tornar a utilitzar tots els serveis de la plataforma: planificar els teus menús, recarregar saldo i consultar
        el teu historial.</p>

    <div style="text-align: center; margin-top: 32px;">
        <a href="{{ config('services.frontend.url') }}" class="button">Accedeix a la Plataforma</a>
    </div>

    <div class="divider"></div>

    <p style="font-size: 14px; color: #64748b;">Gràcies per la teva paciència. Estem encantats de tornar-te a veure!</p>
@endsection
