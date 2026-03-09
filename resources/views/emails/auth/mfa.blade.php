@component('mail::message')
# Your Authentication Code

We detected a login attempt that requires multi-factor authentication. Please use the following 6-digit code to securely complete your sign in.

@component('mail::panel')
<div style="text-align: center; font-size: 24px; font-weight: bold; letter-spacing: 5px;">
{{ $code }}
</div>
@endcomponent

This code will expire in 10 minutes. If you did not request this code, you can safely ignore this email.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
