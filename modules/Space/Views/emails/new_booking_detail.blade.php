<?php
$translation = $service->translateOrOrigin(app()->getLocale());
$lang_local = app()->getLocale();
?>
<div class="b-panel-title">{{__('Space information')}}</div>
<div class="b-table-wrap">
    <table class="b-table" cellspacing="0" cellpadding="0">
        <tr>
            <td class="label">{{__('Booking Number')}}</td>
            <td class="val">#{{$booking->id}}</td>
        </tr>
        <tr>
            <td class="label">{{__('Booking Status')}}</td>
            <td class="val">{{$booking->statusName}}</td>
        </tr>
        @if($booking->gatewayObj)
            <tr>
                <td class="label">{{__('Payment method')}}</td>
                <td class="val">{{$booking->gatewayObj->getOption('name')}}</td>
            </tr>
        @endif
        <tr>
            <td class="label">{{__('Space name')}}</td>
            <td class="val">
                <a href="{{$service->getDetailUrl()}}">{{$translation->title}}</a>
            </td>

        </tr>
        <tr>
            @if($translation->address)
                <td class="label">{{__('Address')}}</td>
                <td class="val">
                    {{$translation->address}}
                </td>
            @endif
        </tr>
        @if($booking->start_date && $booking->end_date)
            <tr>
                <td class="label">{{__('Start date')}}</td>
                <td class="val">{{display_date($booking->start_date)}}</td>
            </tr>
            <tr>
                <td class="label">{{__('End date:')}}</td>
                <td class="val">
                    {{display_date($booking->end_date)}}
                </td>
            </tr>
            <tr>
                <td class="label">{{__('Nights:')}}</td>
                <td class="val">
                    {{$booking->duration_nights}}
                </td>
            </tr>
        @endif

        @if($meta = $booking->getMeta('adults'))
            <tr>
                <td class="label">{{__('Adults')}}:</td>
                <td class="val">
                    <strong>{{$meta}}</strong>
                </td>
            </tr>
        @endif
        @if($meta = $booking->getMeta('children'))
            <tr>
                <td class="label">{{__('Children')}}:</td>
                <td class="val">
                    <strong>{{$meta}}</strong>
                </td>
            </tr>
        @endif
        <tr>
            <td class="label">{{__('Pricing')}}</td>
            <td class="val">
                <table class="pricing-list" width="100%">
                    <tr>
                        <td class="label">{{format_money($booking->total/$booking->duration_nights)}} * {{trans_choice('{1} 1 night|[2,*] :count nights',$booking->duration_nights)}}:</td>
                        <td class="val no-r-padding">
                            <strong>{{format_money($booking->total)}}</strong>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="label fsz21">{{__('Total')}}</td>
            <td class="val fsz21"><strong style="color: #FA5636">{{format_money($booking->total)}}</strong></td>
        </tr>
    </table>
</div>
<div class="text-center mt20">
    <a href="{{url('user/booking-history')}}" target="_blank" class="btn btn-primary">{{__('Manage Bookings')}}</a>
</div>
