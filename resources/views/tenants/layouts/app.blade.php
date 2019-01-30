<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user" content="{{ formatted_logged_user() }}">
    <meta name="scool_menu" content="{{ scool_menu() }}">
    <meta name="tenant" content="{{ tenant() }}">
    <meta name="git" content="{{ git() }}">
    <meta name="env" content="{{ public_env() }}">
    <link rel="manifest" href="/manifest.json">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" rel="stylesheet">
    {{--<link href="https://unpkg.com/vuetify/dist/vuetify.min.css" rel="stylesheet">--}}
    <link href="https://unpkg.com/vuetify/dist/vuetify.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-corner-indicator.min.css" rel="stylesheet">
    <link href="/tenant/css/app.css" rel="stylesheet">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#2680C2"/>
</head>
<body>
<v-app id="app" v-cloak style="background: #F0F4F8;background: -webkit-linear-gradient(to right, #F0F4F8, #D9E2EC, #BCCCDC);
            background: linear-gradient(to right, #F0F4F8, #D9E2EC, #BCCCDC);">
    <snackbar></snackbar>
    <navigation v-model="drawer"></navigation>

    <v-toolbar
            dark
            color="red"
            app
            clipped-left
            clipped-right
            fixed
            style="background: #2680C2;background: -webkit-linear-gradient(to right, #2680C2, #186FAF);
            background: linear-gradient(to right, #2680C2, #186FAF);"
    >
        <v-toolbar-title :style="$vuetify.breakpoint.smAndUp ? 'width: 300px; min-width: 250px' : 'min-width: 72px'" class="ml-0 pl-3">
            <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
            <span class="hidden-xs-only">{{ config('app.shortname', 'Laravel') }}</span>
        </v-toolbar-title>
        <div class="d-flex align-center" style="margin-left: auto">
            <span class="caption" v-role="'SuperAdmin'"><git-info></git-info></span>
            <notifications-widget></notifications-widget>
            <gravatar :user="{{ Auth::user() }}" size="52px" @click="toogleRightDrawer"></gravatar>
        </div>
    </v-toolbar>
    <v-navigation-drawer
            v-model="drawerRight"
            fixed
            right
            clipped
            app
    >
        <v-card>
            <v-container fluid grid-list-xs class="grey lighten-4">
                <v-layout row wrap>
                    <v-flex xs5>
                        <gravatar :user="{{ Auth::user() }}" size="100px"></gravatar>
                    </v-flex>
                    <v-flex xs7>
                        <h3>@{{  user.name }}</h3>
                        <a href="https://en.gravatar.com/connect/">Canviar Avatar</a>
                    </v-flex>
                </v-layout>
            </v-container>
            <v-card-text class="px-0 grey lighten-3">
                <v-form class="pl-3 pr-1 ma-0">
                    <v-text-field :readonly="!editingUser"
                                  label="Correu electrònic"
                                  :value="user.email"
                                  ref="email"
                                  @input="updateEmail"
                    ></v-text-field>
                    <v-text-field :readonly="!editingUser"
                                  label="Nom usuari"
                                  :value="user.name"
                                  @input="updateName"
                    ></v-text-field>
                    <v-text-field readonly
                                  label="Data creació"
                                  :value="user.formatted_created_at_date"
                                  readonly
                    ></v-text-field>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn :loading="updatingUser" flat color="green" @click="updateUser" v-if="editingUser">
                    <v-icon right dark>save</v-icon>
                    Guardar
                </v-btn>
                <v-btn flat color="orange" @click="editUser()" v-else>
                    <v-icon right dark>edit</v-icon>
                    Editar
                </v-btn>
                <logout-button></logout-button>
                <v-spacer></v-spacer>
            </v-card-actions>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn :loading="changingPassword" flat color="red" @click="changePassword" class="mb-0">Canviar Password</v-btn>
                <v-spacer></v-spacer>
            </v-card-actions>
            <v-card-actions v-show="!isEmailVerified">
                <v-spacer></v-spacer>
                <v-btn :loading="confirmingEmail" flat @click="confirmEmail">Confirmar email</v-btn>
                <v-spacer></v-spacer>
            </v-card-actions>
            <v-card-actions v-show="!isEmailVerified">
                <v-spacer></v-spacer>
                <v-btn flat href="/changelog/user/{{Auth::user()->id}}" target="_blank">Registre de canvis</v-btn>
                <v-spacer></v-spacer>
            </v-card-actions>
        </v-card>
        @if (Auth::user()->isTeacher())
            <v-card>
                <v-card-title class="primary white--text"><h2>Opcions professor</h2></v-card-title>
                <v-container fluid grid-list-md class="grey lighten-4">
                    <v-layout row wrap>
                        <v-flex xs12 >

                        </v-flex>
                    </v-layout>
                    <v-btn class="mx-0" title="Vegeu la fitxa del professor" href="/teacher/profile">
                        <v-icon color="primary">visibility</v-icon> Fitxa del professor
                    </v-btn>
                </v-container>
            </v-card>
        @endif
        @if (Auth::user()->isSuperAdmin() || Auth::user()->isImpersonated())
            <v-card>
                <v-card-title class="primary white--text"><h2>Opcions administrador</h2></v-card-title>
                <v-container fluid grid-list-md class="grey lighten-4">
                    <v-layout row wrap>
                        @impersonating
                        <v-flex xs12>
                            <gravatar :user="{{ Auth::user()->impersonatedBy() }}" size="100px"></gravatar>
                        </v-flex>
                        @endImpersonating
                        <v-flex xs12>
                            @canImpersonate
                            <impersonate-user :users="{{$users}}"></impersonate-user>
                            @endCanImpersonate
                            @impersonating
                            {{ Auth::user()->impersonatedBy()->name }} està suplantant {{ Auth::user()->name }}
                            <a href="/impersonate/leave">Abandonar la suplantació</a>
                            @endImpersonating
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-card>
        @endif
    </v-navigation-drawer>
    <v-content>
        @yield('content')
    </v-content>
</v-app>
@stack('beforeScripts')
<script src="{{ mix('tenant/js/app.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js"></script>
@stack('afterScripts')
</body>
</html>
