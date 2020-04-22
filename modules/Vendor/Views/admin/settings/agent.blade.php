<div class="row">
    <div class="col-sm-4">
        <h3 class="form-group-title">{{__('Config Agent')}}</h3>
        <p class="form-group-desc">{{__('Change your config agent system')}}</p>
    </div>
    <div class="col-sm-8">
        <div class="panel">
            <div class="panel-body">
                @if(is_default_lang())
                    <div class="form-group">
                        <div class="form-controls">
                            <div class="form-group">
                                <label> <input type="checkbox" @if($settings['agent_enable'] ?? '' == 1) checked @endif name="agent_enable" value="1"> {{__("Agent Enable?")}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" data-condition="agent_enable:is(1)">
                        <label>{{__('Agent Commission Type')}}</label>
                        <div class="form-controls">
                            <select name="agent_commission_type" class="form-control">
                                <option value="percent" {{($settings['agent_commission_type'] ?? '') == 'percent' ? 'selected' : ''  }}>{{__('Percent')}}</option>
                                <option value="amount" {{($settings['agent_commission_type'] ?? '') == 'amount' ? 'selected' : ''  }}>{{__('Amount')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" data-condition="agent_enable:is(1)">
                        <label>{{__('Agent commission value')}}</label>
                        <div class="form-controls">
                            <input type="text" class="form-control" name="agent_commission_amount" value="{{!empty($settings['agent_commission_amount'])?$settings['agent_commission_amount']:"0" }}">
                        </div>
                        <p><i>{{__('Example: 10% commssion. Agent get 10%.')}}</i></p>
                    </div>
                @else
                    <p>{{__('You can edit on main lang.')}}</p>
                @endif
            </div>
        </div>
    </div>
</div>


