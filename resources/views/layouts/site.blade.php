<!DOCTYPE html>
<html>
    <head>
        <title>Title Here</title>
        <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
        <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}" />
    </head>
    <body>
        @include('components.nav-bar')
        @yield('body')
    </body>
</html>
