@extends('tenants.layouts.app')

@section('content')
    <v-container
        fill-height
        fluid
        grid-list-xl
        text-xs-center
    >
        <v-layout wrap row>
            <v-flex xs12>
                <v-layout align-center justify-center>
                    <v-flex sm12
                            md8
                    >
                        <h1 class="display-3 grey--text text--darken-2 mt-3">&#128075; Benvingut!</h1>
                        <h3 class="headline grey--text mt-5">Escolliu quin tipus de relació voleu tenir amb la nostra comunitat educativa:</h3>
                        <v-layout align-center justify-center row wrap class="mt-5">
                            <v-flex sm12
                                    md4
                            >
                                <material-card class="v-card-profile ma-5">
                                    <v-avatar
                                            slot="offset"
                                            class="mx-auto d-block"
                                            size="130"
                                    >
                                        <img
                                                src="/img/userTypes/student.jpg"
                                        >
                                    </v-avatar>
                                    <v-card-text class="text-xs-center">
                                        <v-btn
                                                large
                                                color="success"
                                                round
                                        >Alumne</v-btn>
                                    </v-card-text>
                                </material-card>
                            </v-flex>
                            <v-flex xs12
                                    sm12
                                    md4
                            >
                                <material-card class="v-card-profile ma-5">
                                    <v-avatar
                                            slot="offset"
                                            class="mx-auto d-block"
                                            size="130"
                                    >
                                        <img
                                                src="/img/userTypes/teacher.jpg"
                                        >
                                    </v-avatar>
                                    <v-card-text class="text-xs-center">
                                        <v-btn
                                                large
                                                color="accent"
                                                round
                                        >Professor</v-btn>
                                    </v-card-text>
                                </material-card>
                            </v-flex>
                            <v-flex sm12
                                    md4
                            >
                                <material-card class="v-card-profile ma-5">
                                    <v-avatar
                                            slot="offset"
                                            class="mx-auto d-block"
                                            size="130"
                                    >
                                        <img
                                                src="/img/userTypes/staff.jpg"
                                        >
                                    </v-avatar>
                                    <v-card-text class="text-xs-center">
                                        <v-btn  large
                                                color="secondary"
                                                round
                                        >Personal</v-btn>
                                    </v-card-text>
                                </material-card>
                            </v-flex>
                        </v-layout>
                    </v-flex>
                </v-layout>
            </v-flex>
            {{--TEACHERS--}}
            @if (($user->user_type_id === 1))
                <v-flex md12
                        sm12
                        lg4>
                    <dashboard-positions :user="{{ $user }}"></dashboard-positions>
                </v-flex>
            @endif
            {{--<v-flex xs12>--}}
            {{--User Type: {{ boolval($user->user_type_id) ? 'true' : 'false' }}--}}
            {{--########--}}
            {{--{{ $user }}--}}
            {{--</v-flex>--}}
        </v-layout>
    </v-container>
@endsection
