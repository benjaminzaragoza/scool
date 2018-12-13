@extends('tenants.layouts.app')

@section('content')
    <curriculum :studies="{{ $studies }}"></curriculum>
@endsection
