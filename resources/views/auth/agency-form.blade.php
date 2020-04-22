<form class="form bravo-form-agency" method="post">
    @csrf

    <!-- add agent number start-->
    <p class="font-weight-bold" style="margin-top: 15px;"> {{_('Please input your Agent Number')}}</p>
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
            {{ __('Continue') }}
    </button>
</form>

