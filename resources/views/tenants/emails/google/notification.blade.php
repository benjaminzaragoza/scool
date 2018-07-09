@component('mail::message')
# Google Users Push Notification received.
<ul>
    @foreach ($googleHeaders as $googleHeaderKey => $googleHeader)
        <li>{{ $googleHeaderKey }}: {{ $googleHeader }}</li>
    @endforeach
</ul>

Full Request:

<quote>
    {{ $request }}
</quote>

<ul>
    @foreach ($headers as $header)
        <li>Header: {{ json_encode($header) }}</li>
    @endforeach
</ul>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
