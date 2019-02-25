@component('mail::message')
# Canvi de paraula de pas de {{ $user->name }}

Algú (probablament vosté o algún administrador) ha canviat la vostre paraula de pas.

@component('mail::panel')
    Usuari: {{ $user->email }} Password: {{ $password }}
@endcomponent

En cas de dubte si us plau consulteu al/s responsable/es de l'aplicació.

@component('mail::button', ['url' => config('app.tenant_url') . '/login'])
Entrar
@endcomponent

Atentament,<br>
Manteniment d'informàtica {{ config('app.name') }}
@endcomponent
