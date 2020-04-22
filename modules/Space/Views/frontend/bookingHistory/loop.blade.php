<tr>
    <td class="booking-history-type">
        <i class="fa fa-bolt"></i>
        <small>{{$booking->object_model}}</small>
    </td>
    <td>
        @if($service = $booking->service)
            @php
                $translation = $service->translateOrOrigin(app()->getLocale());
            @endphp
            <a target="_blank" href="{{$service->getDetailUrl()}}">
                {{$translation->title}}
            </a>
        @else
            {{__("[Deleted]")}}
        @endif
    </td>
    <td>
        {{($booking->agent) ? $booking->agent->name : '-'}}
        {{($booking->agent) ? "(".$booking->agent->id.")" : ''}}
    </td>
    <td class="a-hidden">{{display_date($booking->created_at)}}</td>
    <td class="a-hidden">
        {{__("Start date")}} : {{display_date($booking->start_date)}} <br>
        {{__("End date")}} : {{display_date($booking->end_date)}} <br>
        {{__("Duration")}} : {{ trans_choice('{1} 1 night|[2,*] :count nights',$booking->duration_nights)  }}
    </td>
    <td>{{format_money($booking->total)}}</td>
    <td class="{{$booking->status}} a-hidden">{{$booking->statusName}}</td>
    <td width="2%">
        @if($service = $booking->service)
            <a class="btn btn-xs btn-primary btn-info-booking" data-toggle="modal" data-target="#modal-booking-{{$booking->id}}">
                <i class="fa fa-info-circle"></i>{{__("Details")}}
            </a>
            @include ($service->checkout_booking_detail_modal_file ?? '')
        @endif
    </td>
</tr>
