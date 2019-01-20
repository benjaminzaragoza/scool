@extends('tenants.layouts.app')

@section('content')
    <users :users="{{ $users }}" :roles="{{ $roles }}" :user-types=" {{ $userTypes }}"></users>
@endsection


