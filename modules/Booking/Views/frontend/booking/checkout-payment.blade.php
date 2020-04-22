<div class="form-section">
    <h4 class="form-section-title">{{__('Select Payment Method')}}</h4>
    <div class="gateways-table accordion" id="accordionExample">
        @foreach($gateways as $k=>$gateway)
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <label class="" data-toggle="collapse" data-target="#gateway_{{$k}}" >
                            @if($k == 'offline_payment')
                                <input type="radio" name="payment_gateway" checked value="{{$k}}">
                            @else
                                <input type="radio" name="payment_gateway" value="{{$k}}">
                            @endif
                            @if($logo = $gateway->getDisplayLogo())
                                <img src="{{$logo}}" alt="{{$gateway->getDisplayName()}}">
                            @endif
                            {{$gateway->getDisplayName()}}
                        </label>
                    </h4>
                </div>
                <div id="gateway_{{$k}}" class="collapse {{($k == 'offline_payment') ? 'show' : ''}}" aria-labelledby="headingOne" data-parent="#accordionExample">
                    <div class="card-body">
                        <div class="gateway_name">
                            <!-- {!! $gateway->getDisplayName() !!} -->
                            <p>                                
                                {{__('Payment details will be sent to you through your travel agency.')}}
                            </p>
                            
                            @if($k == 'offline_payment' && $customer)
                                
                                <p style="font-weight: bolder;">{{__('Local Agency')}}

                                    <a href="#" id="changeAgent" class="text-danger">
                                        {{$agent_name ?? 'Please input your agent number'}}
                                        <span class="required">*</span>
                                    </a>
                                    <input type="hidden" name="agent" value="{{$agent_id}}">
                                </p>
                                <p>                                
                                    {{__('(Note: If you wish to change your local agency click on the agency link above and insert ID from another agency.)')}}
                                </p>
                            @else
                            <p class="text-danger">                                
                                {{__('Sorry, you are not a customer. The customer only can be booked!')}}
                            </p>
                            @endif
                        </div>
                        {!! $gateway->getDisplayHtml() !!}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
