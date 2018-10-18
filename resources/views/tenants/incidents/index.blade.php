@extends('tenants.layouts.app')

@section('content')
    <floating-add>
        <incident-add></incident-add>
    </floating-add>

    <v-container fluid grid-list-md text-xs-center>
        <v-layout row wrap>
            <v-flex xs12>
                <incidents-list :incidents="{{ $incidents }}"></incidents-list>
            </v-flex>
        </v-layout>
    </v-container>

@endsection


