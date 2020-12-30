<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png"/>
    <link rel="icon" type="image/png" href="https://www.unesa.ac.id/images/gallery/3/77cbb1fa9a4f2b1c978859c8a5663c25.jpg"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <title>@yield('title') | PMW Unesa</title>

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    <meta name="viewport" content="width=device-width"/>

    <!-- Bootstrap core CSS     -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>

    <!--  Material Dashboard CSS    -->
    <link href="{{ asset('css/material-dashboard.css') }}" rel="stylesheet"/>

    <link href="{{ asset('css/font-awesome.css') }}" rel="stylesheet"/>

    <link href="{{ asset('css/dataTables.bootstrap.css') }}" rel="stylesheet"/>
    <link href="{{ asset('css/dataTables.responsive.css') }}" rel="stylesheet"/>

    <!-- Icon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/unesa_fav.ico') }}"/>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/unesa_fav.ico') }}"/>

    <!--  CSS for Demo Purpose, don't include it in your project     -->
    {{-- <link href="{{ asset('css/demo.css') }}" rel="stylesheet"/> --}}

    {{--DateTimePicker--}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap-material-datetimepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/material-icons.css') }}">

    <!--     Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300|Material+Icons' rel='stylesheet'>

    <link rel="stylesheet" href="{{ asset('css/sweetalert.css')}}">

    <link href="{{ asset('css/custom.css') }} " rel="stylesheet"/>
    @stack('css')

</head>

<body style="background-image: url('{{ asset('img/Background.jpg') }}'); ">

<div class="wrapper">
