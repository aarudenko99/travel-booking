@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center bravo-login-form-page bravo-login-page">
            <div class="col-md-5">
                <div class="">
                    <h4 class="form-title">{{ __('Change Agency') }}</h4>
                    @include('auth.change-agency-form',['captcha_action'=>'agency_normal'])
                </div>
            </div>
        </div>
    </div>
@endsection