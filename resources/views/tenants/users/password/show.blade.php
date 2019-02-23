@extends('tenants.layouts.app')

@section('content')
    <users :users="{{ $users }}" :password="{{ $user }}" :roles="{{ $roles }}" :user-types=" {{ $userTypes }}"></users>
@endsection


