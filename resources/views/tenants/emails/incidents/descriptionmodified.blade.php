@php
$mappedIncident = $incident->load(['user','comments','comments.user'])->map();
$incidentState = $mappedIncident['closed_at'] ? 'Tancada' : 'Oberta';
$incidentClosedInfo = '';
if ($mappedIncident['closed_at']) {
$incidentClosedInfo = $mappedIncident['formatted_closed_at_diff'];
}
$incidentClosedByInfo = '';
if ($mappedIncident['closed_at']) {
$incidentClosedByInfo = $mappedIncident['closer_name'];
}
$tags= 'Cap etiqueta assignada';
if (count($mappedIncident['tags']) > 0) {
$tags= implode(collect($mappedIncident['tags'])->pluck('value')->toArray(),',');
}
$assignees= 'Cap responsable assignat';
if (count($mappedIncident['assignees']) > 0) {
$assignees= implode(collect($mappedIncident['assignees'])->pluck('name')->toArray(),',');
}
@endphp


@component('mail::message')
# Dades de la incidència

- **Títol**: {{ $incident->subject }}
- **Creada per**: {{ $mappedIncident['user_name'] }} ( {{ $mappedIncident['user_email'] }} )
- **Data creació**: <span title="{{ $mappedIncident['formatted_created_at'] }}">{{ $mappedIncident['formatted_created_at_diff'] }}</span>
- **Estat**: {{ $incidentState }} @if ($mappedIncident['closed_at'])<span title="{{ $mappedIncident['formatted_closed_at'] }}">{{ $incidentClosedInfo }} per <span title="{{ $mappedIncident['closer_email'] }}">{{ $incidentClosedByInfo }}</span>@endif

- **Última modificació**: <span title="{{ $mappedIncident['formatted_closed_at'] }}">{{ $mappedIncident['formatted_updated_at_diff'] }}</span>
- **Etiquetes**: {{ $tags }}
- **Assignada a**: {{ $assignees }}

@component('mail::button', ['url' => config('app.tenant_url') . '/'. $mappedIncident['api_uri'] . '/' . $incident->id])
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
