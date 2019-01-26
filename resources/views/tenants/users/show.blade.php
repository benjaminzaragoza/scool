@extends('tenants.layouts.app')

@section('content')
    <users :users="{{ $users }}" :user="{{ $user }}" :roles="{{ $roles }}" :user-types=" {{ $userTypes }}"></users>
@endsection


