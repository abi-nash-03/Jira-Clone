<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js" integrity="sha256-xLD7nhI62fcsEZK2/v8LsBcb4lG7dgULkuXoXB/j91c=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" />

</head>
<body>
    <style>
        /* for board.blade */
        .hvr-fade {
            display: inline-block;
            vertical-align: middle;
            -webkit-transform: perspective(1px) translateZ(0);
            transform: perspective(1px) translateZ(0);
            box-shadow: 0 0 1px rgba(0, 0, 0, 0);
            overflow: hidden;
            -webkit-transition-duration: 0.3s;
            transition-duration: 0.3s;
            -webkit-transition-property: color, background-color;
            transition-property: color, background-color;
        }
        .hvr-fade:hover, .hvr-fade:focus, .hvr-fade:active {
            background-color: #e1e1e1;
            color: #373737;
        }
        .box1::-webkit-scrollbar {
            display: none;
        }

        .box2::-webkit-scrollbar {
            display: none;
        }

        .box3::-webkit-scrollbar {
            display: none;
        }

        .title {
            /* position: unset; */
        }

        .main_container {
            display: flex;
            width: 100%;
            height: 100%;
            padding: 50px;
            justify-content: space-around;
            /* background-color: aquamarine; */
        }

        .box1 {
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 400px;
            height: 600px;
            /* box-shadow: 0.7px 0.7px rgb(217, 217, 217), -0.7px -0.7px rgb(179, 172, 172); */
            box-shadow: 1px 1px 5px rgb(173, 173, 173);
            background-color: #f4f5f7;
            overflow-y: scroll;
            scroll-behavior: smooth;
            margin-right: 5px;
            border-radius: 3px;


        }

        .box2 {
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 400px;
            height: 600px;
            /* box-shadow: 0.7px 0.7px rgb(183, 183, 183), -0.7px -0.7px rgb(179, 172, 172); */
            box-shadow: 1px 1px 5px rgb(173, 173, 173);
            background-color: #F4F5F7;
            overflow-y: scroll;
            scroll-behavior: smooth;
            margin-right: 5px;
            border-radius: 3px;

        }

        .box3 {
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 400px;
            height: 600px;
            box-shadow: 1px 1px 5px rgb(173, 173, 173);
            background-color: #F4F5F7;
            overflow-y: scroll;
            scroll-behavior: smooth;
            border-radius: 3px;

        }

        .card_custom {
            margin: 10px;
            box-shadow: 1px 1px 5px rgb(173, 173, 173);
        }

        .board_title {
            position: relative;
        }

        .buttons {
            display: flex;
            justify-content: end;
            margin-right: 90px;
        }


        .col1{
            background-color: rgb(255, 255, 255);
        }
        .col2row1{
            background-color: rgb(255, 255, 255);
        }
        .col2row2{
            background-color: rgb(255, 255, 255);
        }
        #card_images{
            height: 25px;
            width: 25px;
        }
        #shared_email{
            /* background-color: black */
            /* border: 2px solid; */
            border-radius: 10px;
            padding:3px;
            background-color: rgb(209, 244, 255)
        }
        /* .shared_emails{
            background-color: lightblue;
        } */
        #remaining_profiles{
            height: 20px;
            width: 20px;
            background-color: aliceblue;
        }
        .card-custom{
            /* box-shadow: 1px 1px 1px rgb(173, 173, 173); */
        }   
        #expired{
            height: 17px;
            /* width: 20px; */
        }

        /* for home.blade */
        #card_images{
            height: 25px;
            width: 25px;
        }
        #edit_task_card_images{
            height: 20px;
            width: 20px;
        }
        #shared_email{
            /* background-color: black */
            /* border: 2px solid; */
            border-radius: 10px;
            padding:3px;
            background-color: rgb(209, 244, 255)
        }
    </style>
    @include('inc.nav')
    {{-- <div id="app"> --}}
        

        <main class="">
            @include('inc.message')
            @yield('content')
        </main>
    {{-- </div> --}}
    @yield('modal.script')
</body>
</html>
