@extends('tenants.layouts.app')

@section('content')

    <ldap-user-add></ldap-user-add>

    <ldap-users :users="{{ $users }}"></ldap-users>

    {{--TODO--}}
    <h1>TODO and info</h1>
    <ul>
        <li>PHP functions: http://php.net/manual/en/book.ldap.php</li>
        <li>sudo apt-get install php7.2-ldap</li>
    </ul>
@endsection


