@extends('tenants.layouts.app')

@section('content')
    {{--{{ $incidents }}--}}
    <incidents :incidents="{{ $incidents }}"></incidents>
@endsection


