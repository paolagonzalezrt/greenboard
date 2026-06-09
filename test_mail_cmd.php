<?php
// Ejecutar via: php artisan eval "require 'test_mail_cmd.php';"
// O via tinker

use Illuminate\Support\Facades\Mail;

try {
    Mail::raw('Prueba de correo GreenBoard - si recibes esto funciona!', function($message) {
        $message->to('t23paola-gonzalez@utbispuebla.edu.mx')
                ->subject('Test GreenBoard correo');
    });
    echo "EXITO: Correo enviado\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
