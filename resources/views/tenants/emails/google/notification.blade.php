@component('mail::message')
# Google Users Push Notification received.

<ul>
    <li>Type: {{ $type }}</li>
</ul>

Body:
@component('mail::panel')
    {{ $body }}
@endcomponent

Google headers:
<ul>
    @foreach ($googleHeaders as $googleHeaderKey => $googleHeader)
        <li>{{ $googleHeaderKey }}: {{ $googleHeader }}</li>
    @endforeach
</ul>

All headers:
<ul>
    @foreach ($headers as $headerKey => $header)
        <li>{{ $headerKey }}: {{ $header }}</li>
    @endforeach
</ul>

Full Request:

<quote>
    {{ $request }}
</quote>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
