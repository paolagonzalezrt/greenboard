<?php

return [
    // Seite zum Anfordern des Resets
    'hero_title'             => 'Zugang wiederherstellen',
    'hero_desc'              => 'Wir senden dir einen Link, mit dem du ein neues Passwort erstellen kannst.',
    'form_title'             => 'Passwort vergessen?',
    'form_desc'              => 'Gib deine E-Mail-Adresse ein und wir senden dir einen Wiederherstellungslink.',
    'email'                  => 'E-Mail-Adresse',
    'email_placeholder'      => 'du@email.de',
    'send_button'            => 'Link senden',
    'back_to_login'          => 'Zurück zur Anmeldung',
    'explore'                => 'Entdecken',
    'sent'                   => 'Wir haben den Wiederherstellungslink gesendet. Prüfe deinen Posteingang.',

    // Seite für neues Passwort
    'reset_title'            => 'Neues Passwort erstellen',
    'reset_desc'             => 'Gib dein neues Passwort ein, um wieder Zugang zu deinem Konto zu erhalten.',
    'new_password'           => 'Neues Passwort',
    'new_password_placeholder' => '••••••••',
    'confirm_password'       => 'Passwort bestätigen',
    'confirm_password_placeholder' => '••••••••',
    'reset_button'           => 'Passwort zurücksetzen',
    'reset_success'          => 'Dein Passwort wurde erfolgreich zurückgesetzt.',

    // E-Mail
    'email_subject'          => 'Passwort zurücksetzen - GreenBoard',
    'email_greeting'         => 'Hallo :name,',
    'email_intro'            => 'Wir haben eine Anfrage erhalten, dein Kontopasswort zurückzusetzen.',
    'email_action'           => 'Klicke auf den Button unten, um ein neues Passwort zu erstellen.',
    'email_button'           => 'Passwort zurücksetzen',
    'email_ignore'           => 'Wenn du dies nicht angefordert hast, kannst du diese E-Mail ignorieren.',
    'email_expire'           => 'Dieser Link läuft in :count Minuten ab.',
    'email_fallback_text'    => 'Wenn der Button nicht funktioniert, kopiere und füge die folgende URL in deinen Webbrowser ein:',
    'email_tagline'          => 'Community für nachhaltige Tipps',
    'email_auto_notice'      => 'Dies ist eine automatische Nachricht. Bitte antworte nicht direkt auf diese E-Mail.',

    // Fehler
    'error_email_required'   => 'E-Mail-Adresse ist erforderlich.',
    'error_email_invalid'    => 'Die E-Mail-Adresse ist ungültig.',
    'error_token'            => 'Der Wiederherstellungslink ist ungültig oder abgelaufen.',
    'throttled'              => 'Bitte warte, bevor du es erneut versuchst.',
    'error_user'             => 'Wir konnten kein Konto mit dieser E-Mail finden.',
];
