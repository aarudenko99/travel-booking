<form class="form bravo-change-form-agency" method="post">
    @csrf
    <div class="row current-agent-info">
        <p class="col-lg-12 col-md-12 bold font-weight-bold"> {{_('Current Agent Information:')}}</p>
        <div class="col-lg-6 col-md-12">
            <div class="form-group">
                <p>{{__('Name')}}</p>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="form-group">
                <span class="current-agent-name"></span>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="form-group">
                <p>{{__('Number')}}</p>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="form-group">
                <span class="current-agent-number"></span>
            </div>
        </div>
    </div>
    <!-- add agent number start-->
    <p class="font-weight-bold">{{_('Are you sure change agent number?')}}</p>
    <p> {{_('Insert new agent number below:')}}</p>
    <div class="alert alert-danger error-agent" hidden>
        <span> {{__('Agent Number incorrect')}} </span>
    </div>
    <div class="form-group agent">
        <input type="text" class="form-control agent-id current-agent-id" value="" name="agent_id" autocomplete="off" placeholder="{{__('Agent Number')}}">
        <input type="hidden" name="is_valid_agent_id" value="0">
        <i class="input-icon field-icon fa">
            <img src="/images/ico_fullname_signup.svg">
        </i>
    </div>
    <!-- add agent number end -->

    <button class="btn btn-primary form-submit" type="submit">
            {{ __('Save') }}
    </button>
</form>

