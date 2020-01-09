<div id="Levels">
    <div class="row">
        <div class="col-md-6">

            @if (!empty($subcategory_id))
                {{Form::hidden('subcategory_id', $subcategory_id)}}
            @else
                <div class="form-group {{$errors->has('subcategory_id')? 'has-error' : ''}}">
                    {{Form::label('subcategory_id', 'Subcategory', ['class' => 'control-label'])}}
                    {{Form::select('subcategory_id',
                     App\Subcategory::generalService()->selection('Select Subcategory')
                     , \request('subcategory')
                     , ['class' => 'form-control'])}}
                    @if ($errors->has('subcategory_id'))
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
{{--                                @if(isset($item) && in_array($group->id,$item->service_user_groups()->pluck('group_id')->toArray()))--}}
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
                        :fees="{{ isset($item) && $item->fees ? $item->fees : 0 }}"></additional-fee>
            </fieldset>

            <div class="form-group">
                <input type="checkbox" class="checkbox-tick"
                       id="service_request" name="service_request" @if(isset($item) && $item->service_request) checked @endif>
                <label for="service_request">Is a service request ?</label>
            </div>


            <div class="form-group">
                <fieldset>
                    <legend>Roles</legend>
                    <approval-levels :roles="{{\App\Role::orderBy('name')->get()}}"
                                     :category="{{isset($item) ? $item: 0}}"
                                     :approvals="{{isset($item) ? $item->levels: 0}}"></approval-levels>
                </fieldset>
            </div>

            <div class="form-group">
                <button class="btn btn-success"><i class="fa fa-check-circle"></i> Submit</button>
            </div>
        </div>
        <div class="col-md-6">
            <fieldset>
                <legend>Requirements</legend>
                <service-requirements
                        :requirements_data="{{json_encode(isset($item) ? $item->requirements: [] )}}"></service-requirements>
            </fieldset>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{asset('/js/approval-levels.js')}}"></script>
