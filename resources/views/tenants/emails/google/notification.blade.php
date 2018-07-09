@component('mail::message')
# Google Users Push Notification received.

Google headers:
<ul>
    @foreach ($googleHeaders as $googleHeaderKey => $googleHeader)
        <li>{{ $googleHeaderKey }}: {{ $googleHeader }}</li>
    @endforeach
</ul>

Full Request:

<quote>
    {{ $request }}
</quote>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
