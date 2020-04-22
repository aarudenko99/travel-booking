<div class="row">
    <div class="col-sm-4">
        <h3 class="form-group-title">{{__('Config Vendor')}}</h3>
        <p class="form-group-desc">{{__('Change your config vendor system')}}</p>
    </div>
    <div class="col-sm-8">
        <div class="panel">
            <div class="panel-body">
                @if(is_default_lang())
                    <div class="form-group">
                        <div class="form-controls">
                            <div class="form-group">
                                <label> <input type="checkbox" @if($settings['vendor_enable'] ?? '' == 1) checked @endif name="vendor_enable" value="1"> {{__("Vendor Enable?")}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" data-condition="vendor_enable:is(1)">
                        <label>{{__('Vendor Commission Type')}}</label>
                        <div class="form-controls">
                            <select name="vendor_commission_type" class="form-control">
                                <option value="percent" {{($settings['vendor_commission_type'] ?? '') == 'percent' ? 'selected' : ''  }}>{{__('Percent')}}</option>
                                <option value="amount" {{($settings['vendor_commission_type'] ?? '') == 'amount' ? 'selected' : ''  }}>{{__('Amount')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" data-condition="vendor_enable:is(1)">
                        <label>{{__('Vendor commission value')}}</label>
                        <div class="form-controls">
                            <input type="text" class="form-control" name="vendor_commission_amount" value="{{!empty($settings['vendor_commission_amount'])?$settings['vendor_commission_amount']:"0" }}">
                        </div>
                        <p><i>{{__('Example: 10% commssion. Vendor get 90%, Admin get 10%')}}</i></p>
                    </div>
                @else
                    <p>{{__('You can edit on main lang.')}}</p>
                @endif
            </div>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-sm-4">
        <h3 class="form-group-title">{{__('Vendor Register')}}</h3>
    </div>
    <div class="col-sm-8">
        <div class="panel">
            <div class="panel-body">
                @if(is_default_lang())
                    <div class="form-group">
                        <div class="form-controls">
                            <div class="form-group">
                                <label> <input type="checkbox" @if($settings['vendor_auto_approved'] ?? '' == 1) checked @endif name="vendor_auto_approved" value="1"> {{__("Vendor Auto Approved?")}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{__('Vendor Role')}}</label>
                        <div class="form-controls">
                            <select name="vendor_role" class="form-control">

                                @foreach(\Spatie\Permission\Models\Role::all() as $role)
                                <option value="{{$role->id}}" {{($settings['vendor_role'] ?? '') == $role->id ? 'selected': ''  }}>{{ucfirst($role->name)}}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                @else
                    <p>{{__('You can edit on main lang.')}}</p>
                @endif
            </div>
        </div>
    </div>
</div>
<hr>

