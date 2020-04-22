<div class="bravo_space_book_wrap">
    <div class="bravo_space_book">
        <div id="bravo_space_book_app" v-cloak>
            @if($row->discount_percent)
            <div class="tour-sale-box">
                <span class="sale_class box_sale sale_small">{{$row->discount_percent}}</span>
            </div>
            @endif
            <div class="form-head">
                <div class="price">
                    <span class="label">
                        {{__("From")}}
                    </span>
                    <span class="value">
                        <span class="onsale">{{ $row->display_sale_price }}</span>
                        <span class="text-lg">{{ $row->display_price }}</span>
                    </span>
                </div>
            </div>
            <div class="form-content">
                <div class="form-group form-date-field form-date-search clearfix "
                    data-format="{{get_moment_date_format()}}">
                    <div class="date-wrapper clearfix" @click="openStartDate">
                        <div class="check-in-wrapper">
                            <label>{{__("Select Dates")}}</label>
                            <div class="render check-in-render" v-html="start_date_html"></div>
                        </div>
                        <i class="fa fa-angle-down arrow"></i>
                    </div>
                    <input type="text" class="start_date" ref="start_date" style="height: 1px; visibility: hidden">
                </div>
                <div class="">
                    <div class="form-group form-guest-search">
                        <div class="guest-wrapper d-flex justify-content-between align-items-center">
                            <div class="flex-grow-1">
                                <label>{{__('Adults')}}</label>
                                <div class="render check-in-render">{{__('Ages 12+')}}</div>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="input-number-group">
                                    <i class="icon ion-ios-remove-circle-outline"
                                        @click="minusPersonType('adults')"></i>
                                    <span class="input">@{{adults}}</span>
                                    <i class="icon ion-ios-add-circle-outline" @click="addPersonType('adults')"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-guest-search">
                        <div class="guest-wrapper d-flex justify-content-between align-items-center">
                            <div class="flex-grow-1">
                                <label>{{__('Children')}}</label>
                                <div class="render check-in-render">{{__('Ages 2–12')}}</div>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="input-number-group">
                                    <i class="icon ion-ios-remove-circle-outline"
                                        @click="minusPersonType('children')"></i>
                                    <span class="input">@{{children}}</span>
                                    <i class="icon ion-ios-add-circle-outline" @click="addPersonType('children')"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-section-group" v-if="extra_price.length">
                    <h4 class="form-section-title">{{__('Extra prices:')}}</h4>
                    <div class="form-group " v-for="(type,index) in extra_price">
                        <div class="extra-price-wrap d-flex justify-content-between">
                            <div class="flex-grow-1">
                                <label><input type="checkbox" v-model="type.enable"> @{{type.name}}</label>
                                <div class="render" v-if="type.price_type">(@{{type.price_type}})</div>
                            </div>
                            <div class="flex-shrink-0">@{{type.price_html}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-section-total" v-if="total_price > 0">
                <label>{{__("Total")}}</label>
                <span class="price">@{{total_price_html}}</span>
            </div>
            <div v-html="html"></div>
            @if(!$vendor && !$admin && !$agent)
            <div class="submit-group">
                <p><i>{{trans_choice('{1}1 Guest in maximum|[2,*] :count Guests in maximum',$row->max_guests)}}</i></p>
                <a class="btn btn-large" @click="doSubmit($event)"
                    :class="{'disabled':onSubmit,'btn-success':(step == 2),'btn-primary':step == 1}" name="submit">
                    <span v-if="step == 1">{{__("BOOK NOW")}}</span>
                    <span v-if="step == 2">{{__("Book Now")}}</span>
                    <i v-show="onSubmit" class="fa fa-spinner fa-spin"></i>
                </a>
                <div class="alert-text mt10" v-show="message.content" v-html="message.content"
                    :class="{'danger':!message.type,'success':message.type}"></div>
            </div>
            @endif
            <input type="hidden" name="pre_agent_id" id="pre_agent_id" value="{{$pre_agent_id}}">

            <div class="date-wrapper clearfix price-list-calendar">
                <div class="check-in-wrapper ">
                    <label>{{__("Availability Calendar and Price List")}}</label>
                </div>
                <i class="fa fa-calendar" aria-hidden="true" style="cursor: pointer;"></i>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var g_space_id = '{{$row->id}}';
</script>