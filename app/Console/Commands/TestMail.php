<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMail extends Command
{
    protected $signature = 'mail:test {email}';
    protected $description = 'Enviar correo de prueba';

    public function handle(): void
    {
        $to = $this->argument('email');

        try {
            Mail::raw('Prueba de correo desde GreenBoard. Si recibes esto, el sistema de correo funciona correctamente.', function ($message) use ($to) {
                $message->to($to)->subject('Test GreenBoard - Correo de prueba');
            });

            $this->info("Correo enviado a: {$to}");
            $this->line('Mailer: ' . config('mail.default'));
            $this->line('Host: ' . config('mail.mailers.smtp.host'));
            $this->line('From: ' . config('mail.from.address'));
        } catch (\Exception $e) {
            $this->error('ERROR: ' . $e->getMessage());
        }
    }
}
