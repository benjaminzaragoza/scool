@extends('tenants.layouts.app')

@section('content')

    <ldap-user-add></ldap-user-add>

    <ldap-users :users="{{ $users }}"></ldap-users>

    {{--TODO--}}
    <h1>TODO and info</h1>
    <ul>
        <li>PHP functions: http://php.net/manual/en/book.ldap.php</li>
        
    </ul>
@endsection


