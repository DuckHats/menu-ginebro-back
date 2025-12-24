@extends('emails.layout')

@section('title', 'Confirmació de Comanda')

@section('content')
    <div class="badge">COMANDA CONFIRMADA</div>
    <h2>Gràcies per la teva comanda, {{ $user->name }}!</h2>
    <p>Hem registrat correctament la teva tria per al dia <strong>{{ $order->order_date }}</strong>. Aquí tens els detalls:
    </p>

    <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 32px; margin: 32px 0;">
        <h3 style="margin: 0 0 24px 0; font-size: 16px; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">
            Resum del Menú</h3>

        <table style="width: 100%;">
            <tr>
                <td style="padding-bottom: 16px;">
                    <span style="display: block; font-size: 13px; color: #94a3b8; text-transform: uppercase;">Tipus</span>
                    <span style="font-weight: 700; color: #0f172a;">{{ $order->orderType->name ?? 'Menú Complet' }}</span>
                </td>
            </tr>
            @if ($order->orderDetail)
                @if ($order->orderDetail->option1)
                    <tr>
                        <td style="padding-bottom: 16px; border-top: 1px solid #f1f5f9; padding-top: 16px;">
                            <span style="display: block; font-size: 13px; color: #94a3b8; text-transform: uppercase;">Primer
                                Plat</span>
                            <span style="font-weight: 600; color: #334155;">{{ $order->orderDetail->option1 }}</span>
                        </td>
                    </tr>
                @endif
                @if ($order->orderDetail->option2)
                    <tr>
                        <td style="padding-bottom: 16px; border-top: 1px solid #f1f5f9; padding-top: 16px;">
                            <span style="display: block; font-size: 13px; color: #94a3b8; text-transform: uppercase;">Segon
                                Plat</span>
                            <span style="font-weight: 600; color: #334155;">{{ $order->orderDetail->option2 }}</span>
                        </td>
                    </tr>
                @endif
                @if ($order->orderDetail->option3)
                    <tr>
                        <td style="padding-bottom: 16px; border-top: 1px solid #f1f5f9; padding-top: 16px;">
                            <span
                                style="display: block; font-size: 13px; color: #94a3b8; text-transform: uppercase;">Postres</span>
                            <span style="font-weight: 600; color: #334155;">{{ $order->orderDetail->option3 }}</span>
                        </td>
                    </tr>
                @endif
            @endif
            <tr>
                <td style="padding-top: 24px; border-top: 2px solid #e2e8f0;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="font-size: 18px; font-weight: 800; color: #0f172a;">Total Pagat</td>
                            <td style="text-align: right; font-size: 22px; font-weight: 800; color: #009ca6;">
                                {{ number_format($order->total_price, 2, ',', '.') }}€</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <div style="text-align: center;">
        <a href="{{ $platformUrl }}/profile" class="button">Gestionar les meves comandes</a>
    </div>

    <div class="divider"></div>

    <p style="font-size: 14px; color: #94a3b8; text-align: center; margin-bottom: 0;">Recorda que pots consultar el teu
        historial complet a la plataforma.</p>
@endsection
