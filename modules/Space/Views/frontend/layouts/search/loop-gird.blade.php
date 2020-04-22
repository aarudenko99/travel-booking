@php
    $translation = $row->translateOrOrigin(app()->getLocale());
    $random = rand(2,30);
@endphp
<div class="item-loop {{$wrap_class ?? ''}}">
    @if($row->is_featured == "1")
        <div class="featured">
            {{__("Featured")}}
        </div>
    @endif
    <div class="thumb-image ">
        <a @if(!empty($blank)) target="_blank" @endif href="{{$row->getDetailUrl()}}">
            @if($row->image_url)
                @if(!empty($disable_lazyload))
                    <img src="{{$row->image_url}}" class="img-responsive" alt="">
                @else
                    {!! get_image_tag($row->image_id,'medium',['class'=>'img-responsive','alt'=>$row->title]) !!}
                @endif
            @endif
        </a>
        <div class="price-wrapper">
            <div class="price">
                <span class="onsale">{{ $row->display_sale_price }}</span>
                <span class="text-price">{{ $row->price > 0 ? $row->display_price : '$111' }} <span class="unit">/night</span></span>
            </div>
        </div>
        <div class="service-wishlist {{$row->isWishList()}}" data-id="{{$row->id}}" data-type="{{$row->type}}">
            <i class="fa fa-heart"></i>
        </div>
    </div>
    <div class="item-title">
        <a @if(!empty($blank)) target="_blank" @endif href="{{$row->getDetailUrl()}}">
            @if($row->is_instant)
                <i class="fa fa-bolt d-none"></i>
            @endif
            {{$translation->title}}
        </a>
        @if($row->discount_percent)
            <div class="sale_info">{{$row->discount_percent}}</div>
        @endif
    </div>
    <div class="location">
        @if(!empty($row->location->name))
            @php $location =  $row->location->translateOrOrigin(app()->getLocale()) @endphp
            {{$location->name ?? ''}}
        @endif
    </div>
    <?php
    $reviewData = $row->getScoreReview();
    $score_total = $reviewData['score_total'];
    ?>
    <div class="service-review">
        <span class="rate">
            5/5 Excellent </span>
        </span>
        <span class="review"><?php
            echo($random);
        ?>
        </span>
    </div>
    <div class="amenities clearfix">
        @if($row->max_guests)
            <span class="amenity total" data-toggle="tooltip"  title="{{ __("No. People") }}">
                <i class="input-icon field-icon icofont-people  "></i> {{$row->max_guests}}
            </span>
        @endif
        @if($row->bed)
            <span class="amenity bed" data-toggle="tooltip" title="{{__("No. Bed")}}">
                <i class="input-icon field-icon icofont-hotel"></i> {{$row->bed}}
            </span>
        @endif
        @if($row->bathroom)
            <span class="amenity bath" data-toggle="tooltip" title="{{__("No. Bathroom")}}" >
                <i class="input-icon field-icon icofont-bathtub"></i> {{$row->bathroom}}
            </span>
        @endif
        @if($row->square)
            <span class="amenity size" data-toggle="tooltip" title="{{__("Square")}}" >
                <i class="input-icon field-icon icofont-ruler-compass-alt"></i>{{$row->square}}m<sup>2</sup>
            </span>
        @endif
    </div>
</div>
