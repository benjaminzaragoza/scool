@extends('tenants.layouts.app')

@section('content')
    <subjects
            :subjects="{{ $subjects }}"
            :studies="{{ $studies}}"
            :subject-groups="{{ $subject_groups }}"
            :courses="{{ $courses }}"
            :departments="{{ $departments }}"
            :families="{{ $families }}"
    ></subjects>
@endsection
