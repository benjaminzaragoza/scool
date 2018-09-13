@extends('tenants.layouts.app')

@section('content')

    <user-add :roles="{{ $roles }}"></user-add>

    <users-list :users="{{ $users }}"></users-list>

    <ul>
        <li>TODO</li>
        <li>Mobile: de moment camp no obligatori però després podria servir com alternativa al email.
            Usuaris no tenen email poder utilitzar el mòbil i SMS per a fer autenticació?</li>
        <li>Last Login de l'usuari, permetre saber usuaris no s'han logat mai</li>
    </ul>

    <users-dashboard></users-dashboard>



@endsection


