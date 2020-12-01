<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Мой список задач')</title>

    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <script src="js/jquery-2.1.4.min.js" type="text/javascript"></script>
    <script src="js/app.js" type="text/javascript"></script>
</head>
<body>
<div class="container">
    <div class="content-box">
        <div class="title padding-bottom-30">
            @yield('title', 'Мой список задач')
        </div>
        <div class="content">
            <!-- CONTENT -->
            @yield('content', '')
        </div>
    </div>
</div>
</body>
</html>
