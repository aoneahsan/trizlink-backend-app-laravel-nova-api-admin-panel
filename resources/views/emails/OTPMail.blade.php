<x-mail::message>
# Welcome to ZLink.

    ## Thank you for using our service. To complete your verification, please enter the following OTP (One-Time Password) code:

    {!! '<h2 style="font-size: 100px">'. $otp . '</h2>' !!}

    This OTP is valid for a short period and can only be used once. If you didn't request this OTP, please disregard this email.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
