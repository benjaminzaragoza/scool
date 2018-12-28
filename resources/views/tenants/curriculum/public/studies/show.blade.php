@extends('tenants.layouts.public')

@section('content')
    <v-layout justify-center row fill-height>
        <v-flex xs12 class="mt-4 ml-3">
            <curriculum-study-public :study="{{ $study }}"></curriculum-study-public>
        </v-flex>
    </v-layout>
@endsection
