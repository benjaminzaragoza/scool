@extends('tenants.layouts.app')

@section('content')
    <subject-groups
            :subject-groups="{{ $subjectGroups }}"
    ></subject-groups>
@endsection
