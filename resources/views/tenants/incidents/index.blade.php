@extends('tenants.layouts.app')

@section('content')
    @if ($incident ?? null)
        <incidents :incidents="{{ $incidents }}" :incident="{{ $incident }}" :incident-users="{{ $incident_users }}"></incidents>
    @else
        <incidents :incidents="{{ $incidents }}" :incident-users="{{ $incident_users }}"></incidents>
    @endif
@endsection


