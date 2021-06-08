<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <link href="{{ mix('css/app.css') }}" rel="stylesheet">

        <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    </head>
    <body>
        <div id="app">
            <index></index>

            <vue-progress-bar></vue-progress-bar>
        </div>
        <script src="{{ mix('js/app.js') }}" defer></script>
    </body>
</html>
