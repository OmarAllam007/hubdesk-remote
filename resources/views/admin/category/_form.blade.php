<div id="Levels">
    <div class="row">
        {{csrf_field()}}
        <ul class="nav nav-tabs" id="myTabs" role="tablist">
            <li role="presentation" class="active"><a href="#basic" id="basic-tab" role="tab" data-toggle="tab"
                                                      aria-controls="basic" aria-expanded="true">Basic Information</a>
            </li>
            <li role="presentation" class=""><a href="#permissions" role="tab" id="profile-tab" data-toggle="tab"
                                                aria-controls="profile" aria-expanded="false">Permissions & Roles</a>
            </li>
            <li role="presentation" class=""><a href="#config" role="tab" id="config-tab" data-toggle="tab"
                                                aria-controls="config" aria-expanded="false">Configurations</a></li>
        </ul>

        <div class="tab-content col-md-6" id="myTabContent">
            <div class="tab-pane fade in active" role="tabpanel" id="basic" aria-labelledby="basic-tab">

                @if (!empty($business_unit_id))
                    {{Form::hidden('business_unit_id', $business_unit_id)}}
                @else
                    <div class="form-group {{$errors->has('business_unit_id')? 'has-error' : ''}}">
                        {{Form::label('business_unit_id', 'BusinessUnit', ['class' => 'control-label'])}}
                        {{Form::select('business_unit_id', App\BusinessUnit::selection('Select BusinessUnit'), null, ['class' => 'form-control'])}}
                        @if ($errors->has('business_unit_id'))
                            <div class="error-message">{{$errors->first('business_unit_id')}}</div>
                        @endif
                    </div>
                @endif

                <div class="form-group {{$errors->has('name')? 'has-error' : ''}}">
                    {{Form::label('name', 'Name', ['class' => 'control-label'])}}
                    {{Form::text('name', null, ['class' => 'form-control'])}}
                    @if ($errors->has('name'))
                        <div class="error-message">{{$errors->first('name')}}</div>
                    @endif
                </div>

                <div class="form-group {{$errors->has('description')? 'has-error' : ''}}">
                    {{Form::label('description', 'Description', ['class' => 'control-label'])}}
                    {{Form::textarea('description', null, ['class' => 'form-control', 'rows' => 2])}}
                    @if ($errors->has('description'))
                        <div class="error-message">{{$errors->first('description')}}</div>
                    @endif
                </div>

                <div class="form-group {{$errors->has('logo')? 'has-errors' : ''}}">
                    {{Form::label('logo', 'Logo', ['class' => 'control-label'])}}
                    {{Form::input('file','logo', null, ['class' => 'form-control'])}}
                    @if ($errors->has('logo'))
                        <div class="error-message">{{$errors->first('logo')}}</div>
                    @endif
                </div>

                <div class="form-group {{$errors->has('service_type')? 'has-error' : ''}}">
                    {{Form::label('service_type', 'Service Type', ['class' => 'control-label'])}}
                    {{--                {{Form::select('service_type[]',\App\Group::requesters()->get()->pluck('name','id'),isset($category) ? $category->service_service_type()->pluck('id')->toArray() : null,['class'=>'form-control select2','multiple'=>'true'])}}--}}
                    <select class="form-control" name="service_type" id="service_type">
                        <option value="">{{t('Select Type')}}</option>
                        @foreach(\App\Category::$types as $key=>$type)
                            <option value="{{$key}}"
                                    @if(isset($category) && $key == $category->service_type)
                                    selected
                                    @endif>{{$type}}
                            </option>
                        @endforeach
                    </select>

                    @if ($errors->has('service_type'))
                        <div class="error-message">{{$errors->first('service_type')}}</div>
                    @endif
                </div>


                <div class="form-group {{$errors->has('notes')? 'has-error' : ''}}">
                    {{Form::label('notes', 'Notes', ['class' => 'control-label'])}}
                    {{Form::textarea('notes', null, ['class' => 'form-control richeditor', 'rows' => 5])}}
                    @if ($errors->has('notes'))
                        <div class="error-message">{{$errors->first('notes')}}</div>
                    @endif
                </div>


                <div class="form-group {{$errors->first('service_cost', 'has-error') }}">
                    {{Form::label('service_cost', 'Service Cost', ['class' => 'control-label'])}}
                    <div class="input-group">
                        {{Form::text('service_cost', null, ['class' => 'form-control'])}}
                        <span class="input-group-addon">SAR</span>
                    </div>
                    {!! $errors->first('service_cost', '<div class="error-message">:message</div>') !!}
                </div>

                <div class="form-group">
                    <input type="checkbox"
                           id="service_request" name="service_request"
                           @if(isset($category->service_request) && $category->service_request ) checked @endif>
                    <label for="service_request">is a service request ?</label>
                </div>

                <div class="form-group">
                    <input type="checkbox"
                           id="is_disabled" name="is_disabled"
                           @if(isset($category->is_disabled) && $category->is_disabled) checked @endif>
                    <label for="is_disabled">is disabled ?</label>
                </div>
            </div>
            <div class="tab-pane fade" role="tabpanel" id="permissions" aria-labelledby="permissions-tab">
                <div class="form-group {{$errors->has('user_groups')? 'has-error' : ''}}">
                    {{Form::label('user_groups', 'User Group', ['class' => 'control-label'])}}
                    {{--                {{Form::select('user_groups[]',\App\Group::requesters()->get()->pluck('name','id'),isset($category) ? $category->service_user_groups()->pluck('id')->toArray() : null,['class'=>'form-control select2','multiple'=>'true'])}}--}}
                    <select class="form-control" name="user_groups[]" id="user_groups" multiple>
                        <option value="">{{t('Select Group')}}</option>
                        @foreach(\App\Group::requesters()->get() as $group)
                            <option value="{{$group->id}}"
                                    @if(isset($category) && in_array($group->id,$category->service_user_groups()->pluck('group_id')->toArray()))
                                    selected
                                    @endif>{{$group->name}}
                            </option>
                        @endforeach
                    </select>

                    @if ($errors->has('user_groups'))
                        <div class="error-message">{{$errors->first('user_groups')}}</div>
                    @endif
                </div>

                <fieldset>
                    <legend>Roles</legend>
                    <approval-levels :roles="{{\App\Role::orderBy('name')->get()}}"
                                     :category="{{isset($category) ? $category: 0}}"
                                     :approvals="{{isset($category) ? $category->levels: 0}}"></approval-levels>
                </fieldset>
            </div>
            <div class="tab-pane fade" role="tabpanel" id="config" aria-labelledby="config-tab">


                <fieldset>
                    <legend>Requirements</legend>
                    <service-requirements
                            :requirements_data="{{json_encode(isset($category) ? $category->requirements: [] )}}"></service-requirements>
                </fieldset>

                <fieldset>
                    <legend>Additional Fees</legend>
                    <additional-fee
                            :fees="{{ isset($category) && $category->fees ? $category->fees : 0 }}"></additional-fee>
                </fieldset>

                <fieldset>
                    <legend>Availability</legend>
                    <availability
                            :types="{{collect(\App\Availability::TYPES)->toJson()}}"
                            :business_units="{{\App\BusinessUnit::orderBy('name')->get(['name','id'])}}"
                            :availabilities_data="{{ isset($category) && $category->availabilities ? $category->availabilities : null }}"
                    >
                    </availability>
                </fieldset>


            </div>


            <div class="form-group">
                <button class="btn btn-success"><i class="fa fa-check-circle"></i> Submit</button>
            </div>
        </div>


    </div>

</div>