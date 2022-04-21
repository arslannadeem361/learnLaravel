@inject('request', 'Illuminate\Http\Request')

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Learn Laravel') }}</title>

    @include('layouts.partials.css')
    @yield('css')
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        @include('layouts.partials.navbar')
        @include('layouts.partials.sidebar')

        @yield('content')
    </div>

    @include('layouts.partials.javascripts')
    @yield('javascript')

</body>
</html>
