@extends('tenants.layouts.app')

@section('content')

    <ldap-user-add></ldap-user-add>

    <ldap-users :users="{{ $users }}"></ldap-users>
@endsection


