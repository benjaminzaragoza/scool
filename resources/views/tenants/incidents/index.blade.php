@extends('tenants.layouts.app')

@section('content')
    @if ($incident ?? null)
        <incidents :incidents="{{ $incidents }}" :incident="{{ $incident }}" :incident-users="{{ $incident_users }}" :manager-users="{{ $manager_users }}" :tags="{{ $tags }}"></incidents>
    @else
        <incidents :incidents="{{ $incidents }}" :incident-users="{{ $incident_users }}" :manager-users="{{ $manager_users }}" :tags="{{ $tags }}"></incidents>
    @endif
@endsection


