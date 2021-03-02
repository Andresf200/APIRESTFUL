@component('mail::message')
    # Hola {{ $user->name }}

    Gracias por crear una cuenta por favor verificala usando el siguiente enlace:

    @component('mail::button', ['url' => route('verify', $user->verification_token)])
        Button Text
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
