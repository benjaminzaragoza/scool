@extends('tenants.layouts.app')

@section('content')
    <v-container
            fill-height
            fluid
            grid-list-xl
            text-xs-center
    >
        <v-layout wrap row>
            {{--NEW USERS--}}
            @if (($user->user_type_id === null))
                <welcome></welcome>
            @endif
            {{--TEACHERS--}}
            @if (($user->user_type_id === \App\Models\UserType::TEACHER))
                <teachers-welcome :user="{{ $user }}"></teachers-welcome>
            @endif
            {{--<v-flex xs12>--}}
            {{--User Type: {{ boolval($user->user_type_id) ? 'true' : 'false' }}--}}
            {{--########--}}
            {{--{{ $user }}--}}
            {{--</v-flex>--}}
        </v-layout>
    </v-container>
@endsection
