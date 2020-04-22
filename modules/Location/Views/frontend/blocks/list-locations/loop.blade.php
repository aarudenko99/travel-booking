@php
    $translation = $row->translateOrOrigin(app()->getLocale());
@endphp
<div class="destination-item @if(!$row->image_id) no-image  @endif">
    <a href="{{  $row->getLinkForPageSearch($service_type) }}">
        <div class="image" @if($row->image_id) style="background: url({{$row->getImageUrl()}})" @endif >
            <div class="effect"></div>
            <div class="content">
                <h4 class="title">{{$translation->name}}</h4>
                @if( !empty($layout) and $layout == "style_1")
                    <div class="desc">{{$row->getDisplayNumberServiceInLocation($service_type)}}</div>
                @endif
            </div>
        </div>
    </a>
</div>