<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <title>Laravel Module Frame</title>

</head>
<body>
    <div id="react-app"></div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>