@extends('tenants.layouts.app')

@section('content')
    <v-container
            fill-height
            fluid
            grid-list-xl
    >
        <v-layout wrap>
            <v-flex
                    md12
                    sm12
                    lg4
            >
                <dashboard-positions :user="{{ $user }}"></dashboard-positions>
            </v-flex>
        </v-layout>
    </v-container>
@endsection


