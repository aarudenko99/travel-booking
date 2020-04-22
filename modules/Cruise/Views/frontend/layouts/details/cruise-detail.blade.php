<div class="g-header">
    <div class="left">
        <h2>{{$translation->title}}</h2>
        @if($translation->address)
        <p class="address"><i class="fa fa-map-marker"></i>
            {{$translation->address}}
        </p>
        @endif
    </div>
    <div class="right">
        @if($review_score)
        <div class="review-score">
            <div class="head">
                <div class="left">
                    <span class="head-rating">{{$review_score['score_text']}}</span>
                    <span
                        class="text-rating">{{__("from :number reviews",['number'=>$review_score['total_review']])}}</span>
                </div>
                <div class="score">
                    {{$review_score['score_total']}}<span>/5</span>
                </div>
            </div>
            <div class="foot">
                {{__(":number% of guests recommend",['number'=>$row->recommend_percent])}}
            </div>
        </div>
        @endif
    </div>
</div>
<div class="g-cruise-feature">
    <div class="row">
        @if($row->duration)
        <div class="col-xs-6 col-lg-3 col-md-6">
            <div class="item">
                <div class="icon">
                    <i class="icofont-wall-clock"></i>
                </div>
                <div class="info">
                    <h4 class="name">{{__("Duration")}}</h4>
                    <p class="value">
                        @if($row->duration > 1)
                        {{ __(":number hours",array('number'=>$row->duration)) }}
                        @else
                        {{ __(":number hour",array('number'=>$row->duration)) }}
                        @endif
                    </p>
                </div>
            </div>
        </div>
        @endif
        @if(!empty($row->category_cruise->name))
        @php $cat = $row->category_cruise->translateOrOrigin(app()->getLocale()) @endphp
        <div class="col-xs-6 col-lg-3 col-md-6">
            <div class="item">
                <div class="icon">
                    <i class="icofont-beach"></i>
                </div>
                <div class="info">
                    <h4 class="name">{{__("Cruise Type")}}</h4>
                    <p class="value">
                        {{$cat->name ?? ''}}
                    </p>
                </div>
            </div>
        </div>
        @endif
        @if($row->max_people)
        <div class="col-xs-6 col-lg-3 col-md-6">
            <div class="item">
                <div class="icon">
                    <i class="icofont-travelling"></i>
                </div>
                <div class="info">
                    <h4 class="name">{{__("Group Size")}}</h4>
                    <p class="value">
                        @if($row->max_people > 1)
                        {{ __(":number persons",array('number'=>$row->max_people)) }}
                        @else
                        {{ __(":number person",array('number'=>$row->max_people)) }}
                        @endif
                    </p>
                </div>
            </div>
        </div>
        @endif
        @if(!empty($row->location->name))
        @php $location = $row->location->translateOrOrigin(app()->getLocale()) @endphp
        <div class="col-xs-6 col-lg-3 col-md-6">
            <div class="item">
                <div class="icon">
                    <i class="icofont-island-alt"></i>
                </div>
                <div class="info">
                    <h4 class="name">{{__("Location")}}</h4>
                    <p class="value">
                        {{$location->name ?? ''}}
                    </p>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@if($row->getGallery())
<div class="g-gallery">
    <div class="fotorama" data-width="100%" data-thumbwidth="135" data-thumbheight="135" data-thumbmargin="15"
        data-nav="thumbs" data-allowfullscreen="true">
        @foreach($row->getGallery() as $key=>$item)
        <a href="{{$item['large']}}" data-thumb="{{$item['thumb']}}"></a>
        @endforeach
    </div>
</div>
@endif
@if($translation->content)
<div class="g-overview">
    <h3>{{__("Overview")}}</h3>
    <div class="description">
        <?php echo $translation->content ?>
    </div>
</div>
@endif
{{--@include('Cruise::frontend.layouts.details.cruise-attributes')--}}
@if($translation->faqs)
<div class="g-faq">
    <h3> {{__("FAQs")}} </h3>
    @foreach($translation->faqs as $item)
    <div class="item">
        <div class="header">
            <i class="field-icon icofont-support-faq"></i>
            <h5>{{$item['title']}}</h5>
            <span class="arrow"><i class="fa fa-angle-down"></i></span>
        </div>
        <div class="body">
            {!! clean($item['content']) !!}
        </div>
    </div>
    @endforeach
</div>
@endif
@if($row->map_lat && $row->map_lng)
<div class="g-location">
    <h3>{{__("Cruise Location")}}</h3>
    <div class="location-map">
        <div id="map_content"></div>
    </div>
</div>
@endif