<?php

return [
    // Request reset page
    'hero_title'             => 'Recover your access',
    'hero_desc'              => 'We will send you a link to create a new password.',
    'form_title'             => 'Forgot your password?',
    'form_desc'              => 'Enter your email and we will send you a recovery link.',
    'email'                  => 'Email Address',
    'email_placeholder'      => 'you@email.com',
    'send_button'            => 'Send link',
    'back_to_login'          => 'Back to login',
    'explore'                => 'Explore',
    'sent'                   => 'We sent the recovery link. Check your inbox.',

    // New password page
    'reset_title'            => 'Create a new password',
    'reset_desc'             => 'Enter your new password to regain access to your account.',
    'new_password'           => 'New password',
    'new_password_placeholder' => '••••••••',
    'confirm_password'       => 'Confirm password',
    'confirm_password_placeholder' => '••••••••',
    'reset_button'           => 'Reset password',
    'reset_success'          => 'Your password was successfully reset.',

    // Email
    'email_subject'          => 'Reset your password - GreenBoard',
    'email_greeting'         => 'Hello :name,',
    'email_intro'            => 'We received a request to reset your account password.',
    'email_action'           => 'Click the button below to create a new password.',
    'email_button'           => 'Reset password',
    'email_ignore'           => 'If you did not request this, you can ignore this email.',
    'email_expire'           => 'This link will expire in :count minutes.',
    'email_fallback_text'    => 'If the button does not work, copy and paste the following URL in your web browser:',
    'email_tagline'          => 'Sustainable tips community',
    'email_auto_notice'      => 'This is an automatic message. Please do not reply directly to this email.',

    // Errors
    'error_email_required'   => 'Email address is required.',
    'error_email_invalid'    => 'The email address is not valid.',
    'error_token'            => 'The recovery link is invalid or has expired.',
    'throttled'              => 'Please wait before retrying.',
    'error_user'             => 'We could not find an account with that email.',
];
