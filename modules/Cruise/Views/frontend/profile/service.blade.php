<?php
if(!$user->hasPermissionTo('cruise_create')) return;
$services = \Modules\Cruise\Models\Cruise::getVendorServicesQuery($user->id)->orderBy('id','desc')->paginate(10);
?>
@if($services->total())
<div class="bravo-profile-list-services">
    @include('Cruise::frontend.blocks.list-cruise.index', ['rows'=>$services,'style_list'=>empty($view_all) ? 'carousel'
    :
    'normal','title'=>__('Cruise by :name',['name'=>$user->first_name])])

    <div class="container">
        @if(!empty($view_all))
        <div class="review-pag-wrapper">
            <div class="bravo-pagination">
                {{$services->appends(request()->query())->links()}}
            </div>
            <div class="review-pag-text text-center">
                {{ __("Showing :from - :to of :total total",["from"=>$services->firstItem(),"to"=>$services->lastItem(),"total"=>$services->total()]) }}
            </div>
        </div>
        @else
        <div class="text-center mt30"><a class="btn btn-sm btn-primary"
                href="{{route('user.profile.services',['id'=>$user->id,'type'=>'cruise'])}}">{{__('View all (:total)',['total'=>$services->total()])}}</a>
        </div>
        @endif
    </div>
</div>
@endif