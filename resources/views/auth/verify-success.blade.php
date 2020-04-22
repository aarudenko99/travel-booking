@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center bravo-login-form-page bravo-login-page">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Congratulations') }}</div>
                <div class="card-body">
                    @if ($user_type == 'customer')
                        <div class="alert alert-success" role="alert">
                            {{ __('You are successful confirmed your email. You can use our web site now!') }}
                        </div>
                    @elseif ($user_type == 'vendor' || $user_type == 'agent')
                        <div class="alert alert-success" role="alert">
                            {{ __('You are successful confirmed your email. You are waiting for approval from the administrator now.') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection