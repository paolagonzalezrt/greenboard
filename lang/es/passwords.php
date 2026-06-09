<?php

return [
    // Página de solicitar reset
    'hero_title'             => 'Recupera tu acceso',
    'hero_desc'              => 'Te enviamos un enlace para que puedas crear una nueva contraseña.',
    'form_title'             => '¿Olvidaste tu contraseña?',
    'form_desc'              => 'Ingresa tu correo y te enviaremos un enlace de recuperación.',
    'email'                  => 'Correo electrónico',
    'email_placeholder'      => 'tu@correo.com',
    'send_button'            => 'Enviar enlace',
    'back_to_login'          => 'Volver al inicio de sesión',
    'explore'                => 'Explorar',
    'sent'                   => 'Te enviamos el enlace de recuperación. Revisa tu bandeja de entrada.',

    // Página de nueva contraseña
    'reset_title'            => 'Crea una nueva contraseña',
    'reset_desc'             => 'Ingresa tu nueva contraseña para recuperar el acceso a tu cuenta.',
    'new_password'           => 'Nueva contraseña',
    'new_password_placeholder' => '••••••••',
    'confirm_password'       => 'Confirmar contraseña',
    'confirm_password_placeholder' => '••••••••',
    'reset_button'           => 'Restablecer contraseña',
    'reset_success'          => 'Tu contraseña fue restablecida correctamente.',

    // Email
    'email_subject'          => 'Recupera tu contraseña - GreenBoard',
    'email_greeting'         => 'Hola :name,',
    'email_intro'            => 'Recibimos una solicitud para restablecer la contraseña de tu cuenta.',
    'email_action'           => 'Haz clic en el botón de abajo para crear una nueva contraseña. Este enlace expirará en :count minutos.',
    'email_button'           => 'Restablecer contraseña',
    'email_ignore'           => 'Si no solicitaste este cambio, puedes ignorar este correo.',
    'email_expire'           => 'Este enlace expirará en :count minutos.',

    // Errores
    'error_email_required'   => 'El correo electrónico es requerido.',
    'error_email_invalid'    => 'El correo electrónico no es válido.',
    'error_token'            => 'El enlace de recuperación no es válido o ha expirado.',
    'throttled'              => 'Por favor espera antes de volver a intentarlo.',
    'error_user'             => 'No encontramos ninguna cuenta con ese correo.',
];
