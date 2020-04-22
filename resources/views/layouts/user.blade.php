<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{$html_class ?? ''}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php($favicon = setting_item('site_favicon'))
    <link rel="icon" type="image/png" href="{{!empty($favicon)?get_file_url($favicon,'full'):url('images/favicon.png')}}" />
    @include('layouts.parts.seo-meta')
    <link href="{{ asset('libs/bootstrap/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/ionicons/css/ionicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/icofont/icofont.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset("libs/daterange/daterangepicker.css") }}" >
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel='stylesheet' id='google-font-css-css'  href='https://fonts.googleapis.com/css?family=Poppins%3A400%2C500%2C600' type='text/css' media='all' />
    <script>
        var bookingCore = {
            url:'{{url( app_get_locale() )}}',
            url_root:'{{ url('') }}',
            booking_decimals:{{(int)setting_item('currency_no_decimal',2)}},
            thousand_separator:'{{setting_item('currency_thousand')}}',
            decimal_separator:'{{setting_item('currency_decimal')}}',
            currency_position:'{{setting_item('currency_format')}}',
            currency_symbol:'{{currency_symbol()}}',
            date_format:'{{get_moment_date_format()}}',
            map_provider:'{{setting_item('map_provider')}}',
            map_gmap_key:'{{setting_item('map_gmap_key')}}',
            routes:{
                login:'{{route('auth.login')}}',
                register:'{{route('auth.register')}}',
            }
        };
    </script>
    <link href="{{ asset('module/user/css/user.css') }}" rel="stylesheet">
    <!-- Styles -->
    @yield('head')
    <style type="text/css">
        .bravo_topbar, .bravo_header, .bravo_footer {
            display: none;
        }
        html, body, .bravo_wrap, .bravo_user_profile,
        .bravo_user_profile > .container-fluid > .row-eq-height > .col-md-3 {
            min-height: 100vh !important;
        }
    </style>
    {{--Custom Style--}}
    @include('layouts.parts.custom-css')
    <link href="{{ asset('libs/carousel-2/owl.carousel.css') }}" rel="stylesheet">
</head>
<body class="{{$body_class ?? ''}}">
    {!! setting_item('body_scripts') !!}
    <div class="bravo_wrap">
        @include('layouts.parts.topbar')
        @include('layouts.parts.header')

        <div class="bravo_user_profile">
            <div class="container-fluid">
                <div class="row row-eq-height">
                    <div class="col-md-3">
                        @include('User::frontend.layouts.sidebar')
                    </div>
                    <div class="col-md-9">
                        <div class="user-form-settings">
                            @include('layouts.parts.user-bc')
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.parts.footer',['is_user_page'=>1])
    </div>
    {!! setting_item('footer_scripts') !!}
</body>
</html>
