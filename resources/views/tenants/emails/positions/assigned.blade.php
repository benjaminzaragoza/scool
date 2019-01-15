@component('mail::message')
# Assignació de càrrec

Us han assignat el càrrec **{{ $position->name }}** a l'aplicatiu del centre.

@component('mail::button', ['url' => url('/home')])
Anem-hi!
@endcomponent

Salutacions,<br>
{{ config('app.name') }}
@endcomponent
