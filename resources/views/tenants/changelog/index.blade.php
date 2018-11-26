@extends('tenants.layouts.app')

@section('content')
    <changelog :logs="{{ $logs }}" :users="{{ $users }}"></changelog>
@endsection
