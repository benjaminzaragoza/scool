@extends('tenants.layouts.app')

@section('content')
    <subject-groups
            :subject-groups="{{ $subjectGroups }}"
            :subject-group-tags="{{ $subjectGroupTags }}"
            :studies="{{ $studies }}"
            :departments="{{ $departments }}"
            :families="{{ $families }}"
    ></subject-groups>
@endsection
