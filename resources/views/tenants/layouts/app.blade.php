<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user" content="{{ formatted_logged_user() }}">
    <style>
        [v-cloak] > * { display:none; }
        [v-cloak]::before {
            content: " ";
            display: block;
            width: 40px;
            height: 40px;
            position: absolute;
            top: 50%;
            left: 50%;
            background-image: url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBzdGFuZGFsb25lPSJubyI/Pgo8IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiPgo8c3ZnIHdpZHRoPSI0MHB4IiBoZWlnaHQ9IjQwcHgiIHZpZXdCb3g9IjAgMCA0MCA0MCIgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4bWw6c3BhY2U9InByZXNlcnZlIiBzdHlsZT0iZmlsbC1ydWxlOmV2ZW5vZGQ7Y2xpcC1ydWxlOmV2ZW5vZGQ7c3Ryb2tlLWxpbmVqb2luOnJvdW5kO3N0cm9rZS1taXRlcmxpbWl0OjEuNDE0MjE7IiB4PSIwcHgiIHk9IjBweCI+CiAgICA8ZGVmcz4KICAgICAgICA8c3R5bGUgdHlwZT0idGV4dC9jc3MiPjwhW0NEQVRBWwogICAgICAgICAgICBALXdlYmtpdC1rZXlmcmFtZXMgc3BpbiB7CiAgICAgICAgICAgICAgZnJvbSB7CiAgICAgICAgICAgICAgICAtd2Via2l0LXRyYW5zZm9ybTogcm90YXRlKDBkZWcpCiAgICAgICAgICAgICAgfQogICAgICAgICAgICAgIHRvIHsKICAgICAgICAgICAgICAgIC13ZWJraXQtdHJhbnNmb3JtOiByb3RhdGUoLTM1OWRlZykKICAgICAgICAgICAgICB9CiAgICAgICAgICAgIH0KICAgICAgICAgICAgQGtleWZyYW1lcyBzcGluIHsKICAgICAgICAgICAgICBmcm9tIHsKICAgICAgICAgICAgICAgIHRyYW5zZm9ybTogcm90YXRlKDBkZWcpCiAgICAgICAgICAgICAgfQogICAgICAgICAgICAgIHRvIHsKICAgICAgICAgICAgICAgIHRyYW5zZm9ybTogcm90YXRlKC0zNTlkZWcpCiAgICAgICAgICAgICAgfQogICAgICAgICAgICB9CiAgICAgICAgICAgIHN2ZyB7CiAgICAgICAgICAgICAgICAtd2Via2l0LXRyYW5zZm9ybS1vcmlnaW46IDUwJSA1MCU7CiAgICAgICAgICAgICAgICAtd2Via2l0LWFuaW1hdGlvbjogc3BpbiAxLjVzIGxpbmVhciBpbmZpbml0ZTsKICAgICAgICAgICAgICAgIC13ZWJraXQtYmFja2ZhY2UtdmlzaWJpbGl0eTogaGlkZGVuOwogICAgICAgICAgICAgICAgYW5pbWF0aW9uOiBzcGluIDEuNXMgbGluZWFyIGluZmluaXRlOwogICAgICAgICAgICB9CiAgICAgICAgXV0+PC9zdHlsZT4KICAgIDwvZGVmcz4KICAgIDxnIGlkPSJvdXRlciI+CiAgICAgICAgPGc+CiAgICAgICAgICAgIDxwYXRoIGQ9Ik0yMCwwQzIyLjIwNTgsMCAyMy45OTM5LDEuNzg4MTMgMjMuOTkzOSwzLjk5MzlDMjMuOTkzOSw2LjE5OTY4IDIyLjIwNTgsNy45ODc4MSAyMCw3Ljk4NzgxQzE3Ljc5NDIsNy45ODc4MSAxNi4wMDYxLDYuMTk5NjggMTYuMDA2MSwzLjk5MzlDMTYuMDA2MSwxLjc4ODEzIDE3Ljc5NDIsMCAyMCwwWiIgc3R5bGU9ImZpbGw6YmxhY2s7Ii8+CiAgICAgICAgPC9nPgogICAgICAgIDxnPgogICAgICAgICAgICA8cGF0aCBkPSJNNS44NTc4Niw1Ljg1Nzg2QzcuNDE3NTgsNC4yOTgxNSA5Ljk0NjM4LDQuMjk4MTUgMTEuNTA2MSw1Ljg1Nzg2QzEzLjA2NTgsNy40MTc1OCAxMy4wNjU4LDkuOTQ2MzggMTEuNTA2MSwxMS41MDYxQzkuOTQ2MzgsMTMuMDY1OCA3LjQxNzU4LDEzLjA2NTggNS44NTc4NiwxMS41MDYxQzQuMjk4MTUsOS45NDYzOCA0LjI5ODE1LDcuNDE3NTggNS44NTc4Niw1Ljg1Nzg2WiIgc3R5bGU9ImZpbGw6cmdiKDIxMCwyMTAsMjEwKTsiLz4KICAgICAgICA8L2c+CiAgICAgICAgPGc+CiAgICAgICAgICAgIDxwYXRoIGQ9Ik0yMCwzMi4wMTIyQzIyLjIwNTgsMzIuMDEyMiAyMy45OTM5LDMzLjgwMDMgMjMuOTkzOSwzNi4wMDYxQzIzLjk5MzksMzguMjExOSAyMi4yMDU4LDQwIDIwLDQwQzE3Ljc5NDIsNDAgMTYuMDA2MSwzOC4yMTE5IDE2LjAwNjEsMzYuMDA2MUMxNi4wMDYxLDMzLjgwMDMgMTcuNzk0MiwzMi4wMTIyIDIwLDMyLjAxMjJaIiBzdHlsZT0iZmlsbDpyZ2IoMTMwLDEzMCwxMzApOyIvPgogICAgICAgIDwvZz4KICAgICAgICA8Zz4KICAgICAgICAgICAgPHBhdGggZD0iTTI4LjQ5MzksMjguNDkzOUMzMC4wNTM2LDI2LjkzNDIgMzIuNTgyNCwyNi45MzQyIDM0LjE0MjEsMjguNDkzOUMzNS43MDE5LDMwLjA1MzYgMzUuNzAxOSwzMi41ODI0IDM0LjE0MjEsMzQuMTQyMUMzMi41ODI0LDM1LjcwMTkgMzAuMDUzNiwzNS43MDE5IDI4LjQ5MzksMzQuMTQyMUMyNi45MzQyLDMyLjU4MjQgMjYuOTM0MiwzMC4wNTM2IDI4LjQ5MzksMjguNDkzOVoiIHN0eWxlPSJmaWxsOnJnYigxMDEsMTAxLDEwMSk7Ii8+CiAgICAgICAgPC9nPgogICAgICAgIDxnPgogICAgICAgICAgICA8cGF0aCBkPSJNMy45OTM5LDE2LjAwNjFDNi4xOTk2OCwxNi4wMDYxIDcuOTg3ODEsMTcuNzk0MiA3Ljk4NzgxLDIwQzcuOTg3ODEsMjIuMjA1OCA2LjE5OTY4LDIzLjk5MzkgMy45OTM5LDIzLjk5MzlDMS43ODgxMywyMy45OTM5IDAsMjIuMjA1OCAwLDIwQzAsMTcuNzk0MiAxLjc4ODEzLDE2LjAwNjEgMy45OTM5LDE2LjAwNjFaIiBzdHlsZT0iZmlsbDpyZ2IoMTg3LDE4NywxODcpOyIvPgogICAgICAgIDwvZz4KICAgICAgICA8Zz4KICAgICAgICAgICAgPHBhdGggZD0iTTUuODU3ODYsMjguNDkzOUM3LjQxNzU4LDI2LjkzNDIgOS45NDYzOCwyNi45MzQyIDExLjUwNjEsMjguNDkzOUMxMy4wNjU4LDMwLjA1MzYgMTMuMDY1OCwzMi41ODI0IDExLjUwNjEsMzQuMTQyMUM5Ljk0NjM4LDM1LjcwMTkgNy40MTc1OCwzNS43MDE5IDUuODU3ODYsMzQuMTQyMUM0LjI5ODE1LDMyLjU4MjQgNC4yOTgxNSwzMC4wNTM2IDUuODU3ODYsMjguNDkzOVoiIHN0eWxlPSJmaWxsOnJnYigxNjQsMTY0LDE2NCk7Ii8+CiAgICAgICAgPC9nPgogICAgICAgIDxnPgogICAgICAgICAgICA8cGF0aCBkPSJNMzYuMDA2MSwxNi4wMDYxQzM4LjIxMTksMTYuMDA2MSA0MCwxNy43OTQyIDQwLDIwQzQwLDIyLjIwNTggMzguMjExOSwyMy45OTM5IDM2LjAwNjEsMjMuOTkzOUMzMy44MDAzLDIzLjk5MzkgMzIuMDEyMiwyMi4yMDU4IDMyLjAxMjIsMjBDMzIuMDEyMiwxNy43OTQyIDMzLjgwMDMsMTYuMDA2MSAzNi4wMDYxLDE2LjAwNjFaIiBzdHlsZT0iZmlsbDpyZ2IoNzQsNzQsNzQpOyIvPgogICAgICAgIDwvZz4KICAgICAgICA8Zz4KICAgICAgICAgICAgPHBhdGggZD0iTTI4LjQ5MzksNS44NTc4NkMzMC4wNTM2LDQuMjk4MTUgMzIuNTgyNCw0LjI5ODE1IDM0LjE0MjEsNS44NTc4NkMzNS43MDE5LDcuNDE3NTggMzUuNzAxOSw5Ljk0NjM4IDM0LjE0MjEsMTEuNTA2MUMzMi41ODI0LDEzLjA2NTggMzAuMDUzNiwxMy4wNjU4IDI4LjQ5MzksMTEuNTA2MUMyNi45MzQyLDkuOTQ2MzggMjYuOTM0Miw3LjQxNzU4IDI4LjQ5MzksNS44NTc4NloiIHN0eWxlPSJmaWxsOnJnYig1MCw1MCw1MCk7Ii8+CiAgICAgICAgPC9nPgogICAgPC9nPgo8L3N2Zz4K');
        }
    </style>
    <meta name="scool_menu" content="{{ scool_menu() }}">
    <meta name="tenant" content="{{ tenant() }}">
    <meta name="git" content="{{ git() }}">
    <meta name="env" content="{{ public_env() }}">
    <link rel="manifest" href="/manifest.json">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-corner-indicator.min.css" rel="stylesheet">
    <link href="/tenant/css/app.css" rel="stylesheet">
    <meta name="theme-color" content="#2680C2"/>
    <meta property="og:type" content="website" />
    <meta property="og:image:width" content="1125">
    <meta property="og:image:height" content="750">
    <meta property="og:description" content="Aplicació per a tota la comunitat educativa de l'Institut de l'Ebre">
    <meta property="og:title" content="L'App de l'Institut de l'Ebre">
    <meta property="og:url" content="https://iesebre.scool.cat/">
    <meta property="og:image" content="https://iesebre.scool.cat/img/iesebre/cellular-education-classroom-159844.jpeg">
    <meta property="og:sitename" content="iesebre.scool.cat" />

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@acachawiki" />
    <meta name="twitter:creator" content="@acacha1" />

    <link rel="apple-touch-icon" sizes="180x180" href="/img/appIcon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/appIcon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/img/appIcon/android-chrome-192x192.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/appIcon/favicon-16x16.png">

    <meta name="description" content="Aplicació per a tota la comunitat educativa de l'Institut de l'Ebre">
    <meta name="author" content="Sergi Tur Badenas - scool.cat">

    @stack('beforeScripts')
    <script async defer src="{{ mix('tenant/js/app.js') }}"></script>
    <script async defer src="https://cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js"></script>
    @stack('afterScripts')

</head>
<body>
<v-app id="app" v-cloak style="background: #F0F4F8;background: -webkit-linear-gradient(to right, #F0F4F8, #D9E2EC, #BCCCDC);
            background: linear-gradient(to right, #F0F4F8, #D9E2EC, #BCCCDC);">
    <snackbar></snackbar>
    <service-worker></service-worker>
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
                        <div>
                            <a href="/user/profile">Més informació</a>
                        </div>
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
</body>
</html>
