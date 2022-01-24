<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - @yield('title')</title>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css">
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link href="{{ mix('css/admin.css') }}" rel="stylesheet">
    <script defer src="{{ mix('js/admin.js') }}"></script>
</head>
<body>
@include('admin._partials.flash-messages')
<div id="app" class="h-screen flex flex-row">
    <div class="overflow-y-auto w-2/12 bg-gray-200 h-full py-2">
        @include('admin._partials.navigation')
    </div>
    <div class="w-10/12 flex flex-col">
        <div class="w-full bg-gray-300 px-5 py-2">
            <h1 class="font-bold p-0">@yield('page-title')</h1>
        </div>
        <div class="p-5 overflow-y-scroll border">
            <div class="w-full flex flex-row justify-end">
                @yield('page-actions')
            </div>
            @yield('page')
        </div>
    </div>
</div>
@stack('scripts')
</body>
</html>