@php
$mappedIncident = $incident->load(['user','comments','comments.user'])->map();
$incidentState = $mappedIncident['closed_at'] ? 'Tancada' : 'Oberta';
$incidentDate = $mappedIncident['closed_at'] ? $mappedIncident['formatted_closed_at_diff'] : '';
@endphp

@component('mail::message')
# Dades de la incidència

- **Títol**: {{ $incident->subject }}
- **Creada per**: {{ $mappedIncident['user_name'] }} ( {{ $mappedIncident['user_email'] }} )
- **Data creació**: <span title="{{ $mappedIncident['formatted_created_at'] }}">{{ $mappedIncident['formatted_created_at_diff'] }}</span>
- **Estat**: {{ $incidentState }} {{ $incidentDate }}
- **Última modificació**: <span title="{{ $mappedIncident['formatted_updated_at'] }}">{{ $mappedIncident['formatted_updated_at_diff'] }}</span>

@component('mail::button', ['url' => config('app.url') . '/'. $mappedIncident['api_uri'] . '/' . $incident->id])
    Vegeu la incidència
@endcomponent

# Descripció

{{ $incident->description }}

@if (count($incident->comments) > 0)
# Comentaris
@foreach ($incident->comments as $comment)
**{{ $comment->user->name }}** - {{ $comment->formatted_created_at_diff }}
@component('mail::panel')
{{ $comment->body }}
@endcomponent
@endforeach
@endif

Atentament,<br>
Manteniment d'informàtica {{ config('app.name') }}
@endcomponent
