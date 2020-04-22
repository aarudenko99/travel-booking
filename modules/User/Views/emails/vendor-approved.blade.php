@extends('Email::layout')
@section('content')
    <div class="b-container">
        <div class="b-panel">
            <h1>{{__("Hello :name",['name'=>$user->first_name])}}</h1>
            @if($user->hasRole('vendor'))
            	<p>{{__('You are receiving this email because we approved your vendor registration request.')}}</p>
            	<p>{{__('You can check your dashboard here:')}} <a href="{{url('user/dashboard')}}">{{__('View dashboard')}}</a></p>
            @else
            	<p>{{__('You are receiving this email because we approved your agent registration request.')}}</p>
            @endif
            <br>
            <p>{{__('Regards')}},<br>{{setting_item('site_title')}}</p>
        </div>
    </div>
@endsection
