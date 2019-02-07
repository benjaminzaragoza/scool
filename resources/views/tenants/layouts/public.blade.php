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
    {{--<link href="https://unpkg.com/vuetify/dist/vuetify.min.css" rel="stylesheet">--}}
    <link href="https://unpkg.com/vuetify/dist/vuetify.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-corner-indicator.min.css" rel="stylesheet">
    <link href="/tenant/css/app.css" rel="stylesheet">
    <link rel="manifest" href="/manifest.json">
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

    <meta name="description" content="Aplicació per a tota la comunitat educativa de l'Institut de l'Ebre">
    <meta name="author" content="Sergi Tur Badenas - scool.cat">
</head>
<body>
<v-app id="app" v-cloak>
    <snackbar></snackbar>
    <v-toolbar
            color="blue darken-3"
            dark
            app
            clipped-left
            clipped-right
            fixed
    >
        <v-toolbar-title :style="$vuetify.breakpoint.smAndUp ? 'width: 300px; min-width: 250px' : 'min-width: 72px'" class="ml-0 pl-3">
            <v-toolbar-side-icon></v-toolbar-side-icon>
            <span class="hidden-xs-only">{{ config('app.shortname', 'Laravel') }}</span>
        </v-toolbar-title>
    </v-toolbar>
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
