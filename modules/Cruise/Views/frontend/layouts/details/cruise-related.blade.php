@if(count($cruise_related) > 0)
<div class="bravo-list-cruise-related">
    <h2>{{__("You might also like")}}</h2>
    <div class="row">
        @foreach($cruise_related as $k=>$item)
        <div class="col-md-3">
            @include('Cruise::frontend.layouts.search.loop-gird',['row'=>$item])
        </div>
        @endforeach
    </div>
</div>
@endif