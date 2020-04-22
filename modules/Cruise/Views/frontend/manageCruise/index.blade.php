@extends('layouts.user')
@section('head')

@endsection
@section('content')
<h2 class="title-bar">
    {{__("Manage Cruises")}}
    @if(Auth::user()->hasPermissionTo('cruise_create'))
    <a href="{{url(app_get_locale()."/user/cruise/create")}}" class="btn-change-password">{{__("Add Cruise")}}</a>
    @endif
</h2>
@include('admin.message')
@if($rows->total() > 0)
<div class="bravo-list-item">
    <div class="bravo-pagination">
        <span
            class="count-string">{{ __("Showing :from - :to of :total Cruises",["from"=>$rows->firstItem(),"to"=>$rows->lastItem(),"total"=>$rows->total()]) }}</span>
        {{$rows->appends(request()->query())->links()}}
    </div>
    <div class="list-item">
        <div class="row">
            @foreach($rows as $row)
            <div class="col-md-12">
                @include('Cruise::frontend.manageCruise.loop-list')
            </div>
            @endforeach
        </div>
    </div>
    <div class="bravo-pagination">
        <span
            class="count-string">{{ __("Showing :from - :to of :total Cruises",["from"=>$rows->firstItem(),"to"=>$rows->lastItem(),"total"=>$rows->total()]) }}</span>
        {{$rows->appends(request()->query())->links()}}
    </div>
</div>
@else
{{__("No Cruises")}}
@endif
@endsection
@section('footer')
@endsection