<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SurePredict') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- <script src="{{ mix('js/app.js') }}" defer></script> -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <!-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- <link href="https://fonts.googleapis.com/css2?family=Nunito&family=Arimo&family=Barlow:wght@200;300;400&family=Berkshire+Swash&family=Karla:wght@200;300;400;600&family=Mulish:wght@300;400;600&family=Noto+Sans+TC:wght@100;400&family=Open+Sans:ital,wght@0,300;0,400;1,600&family=Oxygen:wght@300;400&family=PT+Sans&family=Titillium+Web:wght@200;300;400&display=swap" rel="stylesheet"> -->
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Nunito|Lato:100,300,400,700|Open+Sans:400,600|Raleway:300,400|Work+Sans:200|Dancing+Script|Fondamento|Lobster|Pacifico|Poiret+One|Righteous&&family=Arimo&family=Barlow:wght@200;300;400&family=Berkshire+Swash&family=Karla:wght@200;300;400;600&family=Mulish:wght@300;400;600&family=Noto+Sans+TC:wght@100;400&family=Open+Sans:ital,wght@0,300;0,400;1,600&family=Oxygen:wght@300;400&family=PT+Sans&family=Titillium+Web:wght@200;300;400&display=swap|Material+Icons" rel="stylesheet">

    <script>

    </script>

</head>
<body class="body_wrap">
    <div id="app">
        @yield('content')
    </div>
</body>
</html>
