<link rel="stylesheet" href="{{asset('app/public/custom.css')}}">
<link rel="stylesheet" href="{{asset('libs/fullcalendar-4.2.0/core/main.css')}}">
<link rel="stylesheet" href="{{asset('libs/fullcalendar-4.2.0/daygrid/main.css')}}">
<link rel="stylesheet" href="{{asset('libs/daterange/daterangepicker.css')}}">


<div class="modal fade" id="calendar_modal" data-isformagency="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content relative">
            <div class="modal-header">
                <h4 class="modal-title">{{__('Agency')}}</h4>
                <span class="c-pointer" data-dismiss="modal" aria-label="Close">
                    <i class="input-icon field-icon fa">
                        <img src="{{url('images/ico_close.svg')}}" alt="close">
                    </i>
                </span>
            </div>
            <div class="modal-body">
                <div id="dates-calendar" class="dates-calendar"></div>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('landing/js/jquery.min.js')}}"></script>
<script src="{{asset('libs/daterange/moment.min.js')}}"></script>
<script src="{{asset('libs/daterange/daterangepicker.min.js?_ver='.config('app.version'))}}"></script>
<script src="{{asset('libs/fullcalendar-4.2.0/core/main.js')}}"></script>
<script src="{{asset('libs/fullcalendar-4.2.0/interaction/main.js')}}"></script>
<script src="{{asset('libs/fullcalendar-4.2.0/daygrid/main.js')}}"></script>
