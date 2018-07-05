@component('mail::message')
# Introduction

Google Notification received! {{ $prova }}

<ul>
    @foreach ($headers as $header)
        <li>Header: {{ json_encode($header) }}</li>
    @endforeach
</ul>


@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
