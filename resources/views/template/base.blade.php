<!DOCTYPE html>
<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>@yield('title')</title>
    <meta name="keywords" content="@yield('keywords')">
    <meta name="description" content="@yield('description')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('css/app.css')}}" type="text/css">
    @yield('head')
</head>
<body>
    <section>
        @yield('content')
    </section>
    <script type="text/javascript" src="{{asset('js/app.js')}}"></script>
    @yield('script')
</body>
</html>