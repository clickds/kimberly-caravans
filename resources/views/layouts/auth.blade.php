<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin</title>
    <link href="{{ mix('css/admin.css') }}" rel="stylesheet">
</head>
<body>
  @include('admin._partials.flash-messages')
  @include('admin._partials.errors')

  @yield('content')
</body>