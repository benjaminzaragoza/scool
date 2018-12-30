@extends('tenants.layouts.app')

@section('content')
    <curriculum
            :studies="{{ $studies }}"
            :departments="{{ $departments }}"
            :families="{{ $families }}"
            :tags="{{ $tags }}"
            :courses="{{ $courses }}"
            :subject-groups="{{ $subjectGroups }}"
            :subject-group-tags="{{ $subjectGroupTags }}"
    ></curriculum>
@endsection
