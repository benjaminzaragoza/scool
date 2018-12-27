@extends('tenants.layouts.public')

@section('content')
    <v-layout justify-center row fill-height>
        <v-flex xs12 class="ma-3">
            <curriculum-public :studies="{{ $studies }}" :departments="{{ $departments }}" :families="{{ $families }}" :tags="{{ $tags }}"></curriculum-public>
        </v-flex>
    </v-layout>
@endsection
