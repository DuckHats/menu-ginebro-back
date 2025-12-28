@extends('emails.layout')

@section('title', 'Saldo Actualitzat')

@section('content')
    <div class="badge">SALDO ACTUALITZAT</div>
    <h2>Hola, {{ $user->name }}!</h2>
    <p>La teva recàrrega s'ha processat correctament. Aquí tens el resum del teu estat de compte actualitzat:</p>

    <div
        style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 20px; padding: 40px; text-align: center; margin: 32px 0;">
        <span
            style="display: block; font-size: 13px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 700; margin-bottom: 8px;">Nou
            Saldo Disponible</span>
        <h3 style="margin: 0; font-size: 48px; color: #009ca6; font-weight: 900; letter-spacing: -0.04em;">
            {{ number_format($user->balance, 2, ',', '.') }}€</h3>

        <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #e2e8f0; display: inline-block; width: 100%;">
            <table style="width: 100%;">
                <tr>
                    <td style="text-align: left; color: #64748b; font-size: 14px;">Import recarregat</td>
                    <td style="text-align: right; color: #0f172a; font-weight: 700; font-size: 16px;">
                        +{{ number_format($amount, 2, ',', '.') }}€</td>
                </tr>
            </table>
        </div>
    </div>

    <div style="text-align: center;">
        <a href="{{ $platformUrl }}/profile/transactions" class="button">Veure moviments</a>
    </div>

    <div class="divider"></div>

    <p style="font-size: 14px; color: #94a3b8; text-align: center; margin-bottom: 0;">Ja pots utilitzar el teu saldo per
        planificar els teus propers àpats.</p>
@endsection
