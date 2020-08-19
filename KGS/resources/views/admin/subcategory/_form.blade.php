<div id="Levels">
    <div class="row">
        <div class="col-md-6">

            @if (!empty($category_id))
                {{Form::hidden('category_id', $category_id)}}
            @else
                <div class="form-group {{$errors->has('category_id')? 'has-error' : ''}}">
                    {{Form::label('category_id', 'Category', ['class' => 'control-label'])}}
                    {{Form::select('category_id', App\Category::where('business_unit_id',env('GS_ID'))->selection('Select Category'), null, ['class' => 'form-control'])}}
                    @if ($errors->has('category_id'))
                        <div class="error-message">{{$errors->first('category_id')}}</div>
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
                {{Form::textarea('description', null, ['class' => 'form-control', 'rows' => 5])}}
                @if ($errors->has('description'))
                    <div class="error-message">{{$errors->first('description')}}</div>
                @endif
            </div>

            <div class="form-group {{$errors->has('service_type')? 'has-error' : ''}}">
                {{Form::label('service_type', 'Service Type', ['class' => 'control-label'])}}
                {{--                {{Form::select('service_type[]',\App\Group::requesters()->get()->pluck('name','id'),isset($category) ? $category->service_service_type()->pluck('id')->toArray() : null,['class'=>'form-control select2','multiple'=>'true'])}}--}}
                <select class="form-control" name="service_type" id="service_type">
                    <option value="">{{t('Select Type')}}</option>
                    @foreach(\App\Category::$types as $key=>$type)
                        <option value="{{$key}}"
                                @if(isset($subcategory) && $key == $subcategory->service_type)
                                selected
                                @endif>{{$type}}
                        </option>
                    @endforeach
                </select>

                @if ($errors->has('service_type'))
                    <div class="error-message">{{$errors->first('service_type')}}</div>
                @endif
            </div>


            <div class="form-group {{$errors->has('business_service_type')? 'has-error' : ''}}">
                {{Form::label('business_service_type', 'Business Service Type', ['class' => 'control-label'])}}
                <select class="form-control" name="business_service_type" id="business_service_type">
                    <option value="">{{t('Select Type')}}</option>
                    @foreach(\App\Category::$BUSINESS_TYPES as $key=>$bu_type)
                        <option value="{{$key}}"
                                @if(isset($subcategory) && $key == $subcategory->business_service_type)
                                selected
                                @endif>{{$bu_type}}
                        </option>
                    @endforeach
                </select>

                @if ($errors->has('business_service_type'))
                    <div class="error-message">{{$errors->first('business_service_type')}}</div>
                @endif
            </div>
            {{--            <div class="form-group {{$errors->has('notes')? 'has-error' : ''}}">--}}
            {{--                {{Form::label('notes', 'Notes', ['class' => 'control-label'])}}--}}
            {{--                {{Form::textarea('notes', null, ['class' => 'form-control richeditor', 'rows' => 5])}}--}}
            {{--                @if ($errors->has('notes'))--}}
            {{--                    <div class="error-message">{{$errors->first('notes')}}</div>--}}
            {{--                @endif--}}
            {{--            </div>--}}

            {{--            <div class="form-group {{$errors->has('user_groups')? 'has-error' : ''}}">--}}
            {{--                {{Form::label('user_groups', 'User Group', ['class' => 'control-label'])}}--}}
            {{--                --}}{{--                {{Form::select('user_groups[]',\App\Group::requesters()->get()->pluck('name','id'),isset($category) ? $category->service_user_groups()->pluck('id')->toArray() : null,['class'=>'form-control select2','multiple'=>'true'])}}--}}
            {{--                <select class="form-control" name="user_groups[]" id="user_groups" multiple>--}}
            {{--                    <option value="">{{t('Select Group')}}</option>--}}
            {{--                    @foreach(\App\Group::requesters()->get() as $group)--}}
            {{--                        <option value="{{$group->id}}"--}}
            {{--                                @if(isset($subcategory) && in_array($group->id,$subcategory->service_user_groups()->pluck('group_id')->toArray()))--}}
            {{--                                selected--}}
            {{--                                @endif>{{$group->name}}</option>--}}
            {{--                    @endforeach--}}
            {{--                </select>--}}

            {{--                @if ($errors->has('user_groups'))--}}
            {{--                    <div class="error-message">{{$errors->first('user_groups')}}</div>--}}
            {{--                @endif--}}
            {{--            </div>--}}

            <div class="form-group {{$errors->first('service_cost', 'has-error') }}">
                {{Form::label('service_cost', 'Service Cost', ['class' => 'control-label'])}}
                <div class="input-group">
                    {{Form::text('service_cost', null, ['class' => 'form-control'])}}
                    <span class="input-group-addon">SAR</span>
                </div>
                {!! $errors->first('service_cost', '<div class="error-message">:message</div>') !!}
            </div>

            <fieldset>
                <legend>Additional Fees</legend>
                <additional-fee
                        :fees="{{ isset($subcategory) && $subcategory->fees ? $subcategory->fees : 0 }}"></additional-fee>
            </fieldset>

            <div class="form-group">
                <input type="checkbox" class="checkbox-tick"
                       id="service_request" name="service_request"
                       @if(isset($subcategory->service_request) && $subcategory->service_request ) checked @endif >
                <label for="service_request">Is a service request ?</label>
            </div>

            <fieldset>
                <legend>Roles</legend>
                <approval-levels :roles="{{\App\Role::orderBy('name')->get()}}"
                                 :category="{{isset($subcategory) ? $subcategory: 0}}"
                                 :approvals="{{isset($subcategory) ? $subcategory->levels: 0}}"></approval-levels>
            </fieldset>
            <div class="form-group">
                <button class="btn btn-success"><i class="fa fa-check-circle"></i> Submit</button>
            </div>
        </div>

        <div class="col-md-6">
            <fieldset>
                <legend>Requirements</legend>
                <service-requirements
                        :requirements_data="{{json_encode(isset($subcategory) ? $subcategory->requirements: [] )}}"></service-requirements>
            </fieldset>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{asset('/js/approval-levels.js')}}"></script>
