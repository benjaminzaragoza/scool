@extends('tenants.layouts.app')

@section('content')

    <pending-teachers
            :teachers="{{ $teachers }}"
            :jobs="{{ $jobs }}"
            :pending-teachers="{{ $pendingTeachers }}"
            :specialties="{{ $specialties }}"
            :forces="{{ $forces }}"
            :administrative-statuses="{{ $administrativeStatuses }}">
    </pending-teachers>

    <teachers :teacher="{{ $teacher }}" :teachers="{{ $teachers }}" :administrative-statuses="{{ $administrativeStatuses }}"></teachers>

    <teacher-add
            :users="{{ $users }}"
            :jobs="{{ $jobs }}"
            :specialties="{{ $specialties }}"
            :administrative-statuses="{{ $administrativeStatuses }}"
            :departments="{{ $departments }}"></teacher-add>

@endsection


