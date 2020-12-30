<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <style>
        .container {

        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            @yield('header')
        </div>

        <div class="body">
            @yield('content')
        </div>

        <div class="footer">
            PMW Unversitas Negeri Surabaya
            <br>
            Mohon untuk tidak membalas email ini
        </div>
    </div>
</body>

</html>