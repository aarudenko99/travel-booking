<div class="item-list">
    @if($row->discount_percent)
        <div class="sale_info">{{$row->discount_percent}}</div>
    @endif
    <div class="row">
        <div class="col-md-3">
            @if($row->is_featured == "1")
                <div class="featured">
                    {{__("Featured")}}
                </div>
            @endif
            <div class="thumb-image">
                <a href="{{$row->getDetailUrl()}}" target="_blank">
                    @if($row->image_url)
                        <img src="{{$row->image_url}}" class="img-responsive" alt="">
                    @endif
                </a>
                <div class="service-wishlist {{$row->isWishList()}}" data-id="{{$row->id}}" data-type="{{$row->type}}">
                    <i class="fa fa-heart"></i>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="item-title">
                <a href="{{$row->getDetailUrl()}}" target="_blank">
                    {{$row->title}}
                </a>
            </div>
            <div class="location">
                @if(!empty($row->location->name))
                    <i class="icofont-paper-plane"></i>
                    {{__("Location")}}: {{$row->location->name ?? ''}}
                @endif
            </div>
            <div class="location">
                <i class="icofont-money"></i>
                {{__("Price")}}: <span class="sale-price">{{ $row->display_sale_price }}</span> <span class="price">{{ $row->display_price }}</span>
            </div>
            <div class="location">
                <i class="icofont-ui-settings"></i>
                {{__("Status")}}: <span class="badge badge-{{ $row->status }}">{{ $row->status }}</span>
            </div>
            <div class="control-action">
                <a href="{{$row->getDetailUrl()}}" target="_blank" class="btn btn-info">{{__("View")}}</a>
                @if(Auth::user()->hasPermissionTo('space_update'))
                    <a href="{{url(app_get_locale()."/user/space/edit/".$row->id)}}" class="btn btn-warning">{{__("Edit")}}</a>
                @endif
                @if(Auth::user()->hasPermissionTo('space_delete'))
                    <a href="{{url(app_get_locale()."/user/space/del/".$row->id)}}" class="btn btn-danger" data-confirm="<?php echo e(__("Do you want to delete?")); ?>">{{__("Del")}}</a>
                @endif
            </div>
        </div>
    </div>
</div>