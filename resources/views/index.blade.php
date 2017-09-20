<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>ManyLinks</title>
        {{-- We hate IE --}}
        <link rel="stylesheet" href="{{ mix('/css/main.css') }}">
        <script src="https://cdn.polyfill.io/v2/polyfill.min.js"></script>
    </head>
    <body>
        <div id="app"></div>
        <script src="{{ mix('/js/index.js') }}"></script>
    </body>
</html>
