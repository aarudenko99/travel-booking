@extends('layouts.app')

@section('content')
    @include('layouts.parts.bc')
    <div class="page-profile-content page-template-content">
        @if(view()->exists(ucfirst($type).'::frontend.profile.service'))
            @include(ucfirst($type).'::frontend.profile.service',['view_all'=>1])
        @endif
    </div>
@endsection