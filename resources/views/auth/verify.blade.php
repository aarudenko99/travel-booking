@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center bravo-login-form-page bravo-login-page">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verify Your Email Address') }}</div>
                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif
                    {{ __('You have received verification email. Please check your email.') }}
                    {{ __("If you don't see the confirmation email, recheck the spam folder.") }} <br/>
                    {{ __("If you didn't receive the email,") }}
                     <a href="{{ route('verification.resend') }}">{{ __('resend verification email') }}</a>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
