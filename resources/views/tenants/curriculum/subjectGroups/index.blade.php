@extends('tenants.layouts.app')

@section('content')
    <subject-groups
            :subject-groups="{{ $subjectGroups }}"
            :studies="{{ $studies }}"
            :departments="{{ $departments }}"
            :families="{{ $families }}"
    ></subject-groups>
@endsection
