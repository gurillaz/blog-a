<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
        {{-- <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" /> --}}
        {{-- <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" /> --}}
        <meta charset="utf-8">
        {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{csrf_token()}}">
        <title>{{config('app.name', 'Blog')}}</title>

        {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">   --}}
        

            {{-- Vuetify font and Icons --}}
            <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
            <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css?family=Playfair+Display&display=swap" rel="stylesheet">

            <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">

    </head>
    <body>
            <div id="app">
          
                <index></index>

            </div>

        <script src="{{ asset('js/app.js') }}"></script>
        {{-- <script src="{{ mix('../js/bundle.js') }}"></script> --}}
    </body>
</html>
