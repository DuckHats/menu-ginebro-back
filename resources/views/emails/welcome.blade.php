@extends('emails.layout')

@section('title', 'Benvingut/da al Ginebró')

@section('content')
    <div class="badge">BENVINGUDA</div>
    <h2>Hola, {{ $user->name }}!</h2>
    <p>És un plaer donar-te la benvinguda a la plataforma del menjador del <strong>Ginebró</strong>. Hem creat aquest espai
        perquè gestionar els teus àpats sigui una experiència àgil i moderna.</p>

    <div style="margin: 32px 0; border: 1px solid #e2e8f0; border-radius: 20px; padding: 32px; background-color: #ffffff;">
        <h3 style="margin: 0 0 20px 0; font-size: 16px; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">
            Què pots fer ara mateix?</h3>

        <div style="margin-bottom: 20px;">
            <div style="font-weight: 700; color: #0f172a; margin-bottom: 4px;">🗓️ Planifica la teva setmana</div>
            <p style="margin: 0; font-size: 14px; color: #64748b;">Tria els teus menús preferits amb antelació i evita cues.
            </p>
        </div>

        <div style="margin-bottom: 20px; padding-top: 20px; border-top: 1px solid #f1f5f9;">
            <div style="font-weight: 700; color: #0f172a; margin-bottom: 4px;">💳 Gestiona el teu saldo</div>
            <p style="margin: 0; font-size: 14px; color: #64748b;">Recarrega de forma segura i consulta el teu historial de
                pagaments.</p>
        </div>

        <div style="padding-top: 20px; border-top: 1px solid #f1f5f9;">
            <div style="font-weight: 700; color: #0f172a; margin-bottom: 4px;">📊 Historial detallat</div>
            <p style="margin: 0; font-size: 14px; color: #64748b;">Accedeix a totes les teves comandes i moviments en un sol
                lloc.</p>
        </div>
    </div>

    <div style="text-align: center;">
        <a href="{{ $platformUrl }}" class="button">Comença ara</a>
    </div>

    <div class="divider"></div>

    <p style="font-size: 14px; color: #94a3b8; text-align: center; margin-bottom: 0;">Si necessites ajuda, el nostre equip
        d'atenció al client està aquí per a tu.</p>
@endsection
