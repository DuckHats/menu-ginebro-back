<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Mail;

class EmailHelper
{
    /**
     * Enviar un correo electrónico simplificado.
     *
     * @param  string  $recipient  Correo del destinatario.
     * @param  string  $mailableClass  Clase del Mailable (ejemplo: \App\Mail\WelcomeMail::class).
     * @param  array  $data  Datos dinámicos que se pasan al Mailable.
     * @param  string|null  $cc  Correo para copia (opcional).
     * @param  string|null  $bcc  Correo para copia oculta (opcional).
     * @return bool
     */
    public static function sendEmail($recipient, $mailableClass, $data = [], $cc = null, $bcc = null)
    {
        try {
            $mailable = new $mailableClass(...$data);

            $email = Mail::to($recipient);

            if ($cc) {
                $email->cc($cc);
            }

            if ($bcc) {
                $email->bcc($bcc);
            }

            // Enviar el correo
            $email->send($mailable);

            return true; // Éxito
        } catch (\Exception $e) {
            ErrorLogger::log(
                'Error enviando correo:',
                $e->getMessage(),
                $e->getTraceAsString());

            return false; // Fallo
        }
    }
}
