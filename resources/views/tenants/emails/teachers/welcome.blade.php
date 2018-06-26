@component('mail::message')
# Introduction

The body of your message.

@component('mail::button', ['url' => 'http://www.google.com/prova'])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
