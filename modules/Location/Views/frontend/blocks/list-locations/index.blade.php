<div class="container">
    <div class="bravo-list-locations @if(!empty($layout)) {{ $layout }} @endif">
        {{--<div class="title">--}}
            {{--{{$title}}--}}
        {{--</div>--}}
        @if(!empty($desc))
            <div class="sub-title">
                {{$desc}}
            </div>
        @endif
        <?php
        $lang = 'zh';
        if(request()->path() === 'en'){
            $lang = 'en';
        }
        ?>
        <div class="list-item">

            <div class="row">
                <div class="col-lg-8">
                    <div class="destination-item">
                        <a href="https://crooatia.com/{{$lang}}/cruise/croatia-family-yacht-cruise-7-days-holiday-on-the-sailboat-with-skipper-included-private-cruise">
                            <div class="image" style="background: url({{asset('uploads/demo/tour/home1.jpg')}})">
                                <div class="effect"></div>
                                <div class="content">
                                    <h4 class="title">@if(request()->path() === 'en') CROATIA FAMILY YACHT CRUISES @else 克罗地亚家庭游艇巡游 @endif</h4>
                                    <div class="desc">@if(request()->path() === 'en') See more @else 查看更多 @endif</div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="destination-item">
                        <a href="https://crooatia.com/{{$lang}}/cruise/sailboat-day-trips-from-split-or-hvar-private">
                            <div class="image" style="background: url({{asset('uploads/demo/tour/home2.jpg')}})">
                                <div class="effect"></div>
                                <div class="content">
                                    <h4 class="title">@if(request()->path() === 'en') PRIVATE SAILBOAT DAY TRIPS FROM SPLIT OR HVAR @else 来自SPLIT或HVAR的私人帆船一日游 @endif</h4>
                                    <div class="desc">@if(request()->path() === 'en') See more @else 查看更多 @endif</div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <div class="destination-item">
                        <a href="https://crooatia.com/{{$lang}}/tour/highlights-of-croatia-7-nights-package-tour">
                            <div class="image" style="background: url({{asset('uploads/demo/tour/home3.jpg')}})">
                                <div class="effect"></div>
                                <div class="content">
                                    <h4 class="title">@if(request()->path() === 'en') HIGHLIGHTS OF CROATIA - 7 NIGHTS PACKAGE TOUR @else 克罗地亚的亮点-7晚套票之旅 @endif</h4>
                                    <div class="desc">@if(request()->path() === 'en') See more @else 查看更多 @endif</div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="destination-item">
                        <a href="https://crooatia.com/{{$lang}}/tour/croatia-islands-7-days-package-tour">
                            <div class="image" style="background: url({{asset('uploads/demo/tour/home4.jpg')}})">
                                <div class="effect"></div>
                                <div class="content">
                                    <h4 class="title">@if(request()->path() === 'en') CROATIA ISLANDS - 7 DAYS PACKAGE TOUR @else 克罗地亚群岛-7日游 @endif</h4>
                                    <div class="desc">@if(request()->path() === 'en') See more @else 查看更多 @endif</div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="destination-item">
                        <a href="https://crooatia.com/{{$lang}}/cruise/party-holiday-for-young-people-7-days-on-the-sailboat-one-cabin-reservation">
                            <div class="image" style="background: url({{asset('uploads/demo/tour/home5.jpg')}})">
                                <div class="effect"></div>
                                <div class="content">
                                    <h4 class="title">@if(request()->path() === 'en') PARTY HOLIDAY ON THE SAILBOAT @else 假日派对上的帆船 @endif</h4>
                                    <div class="desc">@if(request()->path() === 'en') See more @else 查看更多 @endif</div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>