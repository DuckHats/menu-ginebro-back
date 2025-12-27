@extends('emails.layout')

@section('title', 'Compte Suspès')

@section('content')
    <div class="badge" style="background-color: #fee2e2; color: #dc2626;">AVÍS DE COMPTE</div>
    <h2>El teu compte ha estat suspès</h2>
    <p>Hola, t'informem que el teu compte al menjador del <strong>Ginebró</strong> ha estat suspès temporalment.</p>

    <div class="divider"></div>

    <div style="background-color: #fef2f2; padding: 24px; border-radius: 16px; margin-bottom: 24px;">
        <h3 style="margin-top: 0; font-size: 16px; color: #991b1b;">Motiu de la suspensió</h3>
        <p style="margin: 0; color: #b91c1c;">{{ $reason }}</p>
    </div>

    <p>Mentre el compte estigui suspès, no podràs realitzar noves comandes ni gestionar el teu saldo.</p>

    <div class="divider"></div>

    <p style="font-size: 14px; color: #64748b;">Si creus que es tracta d'un error o vols més informació, posa't en contacte
        amb l'administració del centre.</p>
@endsection
