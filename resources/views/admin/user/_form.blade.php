<div class="row">
    <div class="col-md-6">
        <div class="form-group {{$errors->has('name')? 'has-error' : ''}}">
            {{ Form::label('name', 'Name', ['class' => 'control-label']) }}
            {{ Form::text('name', isset($user) ? $user->name : null, ['class' => 'form-control']) }}
            @if ($errors->has('name'))
                <div class="error-message">{{$errors->first('name')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('employee_id')? 'has-error' : ''}}">
            {{ Form::label('employee_id', 'Employee ID', ['class' => 'control-label']) }}
            {{ Form::text('employee_id', isset($user) ? $user->employee_id : null, ['class' => 'form-control']) }}
            @if ($errors->has('employee_id'))
                <div class="error-message">{{$errors->first('employee_id')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('email')? 'has-error' : ''}}">
            {{ Form::label('email', 'Email', ['class' => 'control-label']) }}
            {{ Form::email('email', isset($user) ? $user->email : null, ['class' => 'form-control', 'rows' => 3]) }}
            @if ($errors->has('email'))
                <div class="error-message">{{$errors->first('email')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('login')? 'has-error' : ''}}">
            {{ Form::label('login', 'Login name', ['class' => 'control-label']) }}
            {{ Form::text('login', isset($user) ? $user->login : null, ['class' => 'form-control']) }}
            @if ($errors->has('login'))
                <div class="error-message">{{$errors->first('login')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('job')? 'has-error' : ''}}">
            {{ Form::label('job', 'Job Title', ['class' => 'control-label']) }}
            {{ Form::text('job', isset($user) ? $user->job : null, ['class' => 'form-control']) }}
            @if ($errors->has('job'))
                <div class="error-message">{{$errors->first('job')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('business_unit_id')? 'has-error' : ''}}">
            {{ Form::label('business_unit_id', 'Business Unit', ['class' => 'control-label']) }}
            {{ Form::select('business_unit_id', App\BusinessUnit::selection('Select Business Unit'), isset($user) ? $user->business_unit_id : null, ['class' => 'form-control select2']) }}
            @if ($errors->has('business_unit_id'))
                <div class="error-message">{{$errors->first('business_unit_id')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('department_id')? 'has-error' : ''}}">
            {{ Form::label('department_id', 'Department', ['class' => 'control-label']) }}
            {{ Form::select('department_id', App\Department::selection('Select Department'), isset($user) ? $user->department_id : null, ['class' => 'form-control']) }}
            @if ($errors->has('department_id'))
                <div class="error-message">{{$errors->first('department_id')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('location_id')? 'has-error' : ''}}">
            {{ Form::label('location_id', 'Location', ['class' => 'control-label']) }}
            {{ Form::select('location_id', App\Location::selection('Select Location'), isset($user) ? $user->location_id : null, ['class' => 'form-control']) }}
            @if ($errors->has('location_id'))
                <div class="error-message">{{$errors->first('location_id')}}</div>
            @endif
        </div>

        @php
            $users = App\User::get(['email','name','id'])->map(function ($user){
                return ['name'=> $user->name .' - '. $user->email , 'id'=> $user->id];
            });
        @endphp
        <div class="form-group {{$errors->has('manager_id')? 'has-error' : ''}}">
            {{ Form::label('manager_id', 'Direct Manager', ['class' => 'control-label']) }}
            {{ Form::select('manager_id',$users->pluck('name','id') , isset($user) ? $user->manager_id : null, ['class' => 'form-control select2']) }}
            @if ($errors->has('manager_id'))
                <div class="error-message">{{$errors->first('manager_id')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('phone')? 'has-error' : ''}}">
            {{ Form::label('phone', 'Phone', ['class' => 'control-label']) }}
            {{ Form::text('phone', isset($user) ? $user->phone : null, ['class' => 'form-control']) }}
            @if ($errors->has('phone'))
                <div class="error-message">{{$errors->first('phone')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('mobile1')? 'has-error' : ''}}">
            {{ Form::label('mobile1', 'Mobile #1', ['class' => 'control-label']) }}
            {{ Form::text('mobile1', isset($user) ? $user->mobile1 : null, ['class' => 'form-control']) }}
            @if ($errors->has('mobile1'))
                <div class="error-message">{{$errors->first('mobile1')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('mobile2')? 'has-error' : ''}}">
            {{ Form::label('mobile2', 'Mobile #2', ['class' => 'control-label']) }}
            {{ Form::text('mobile2', isset($user) ? $user->mobile2 : null, ['class' => 'form-control']) }}
            @if ($errors->has('mobile2'))
                <div class="error-message">{{$errors->first('mobile2')}}</div>
            @endif
        </div>

        <div class="form-group">
            <div class="checkbox">
                <label class="control-label" for="vip">
                    {{Form::hidden('vip', 0)}}
                    {{Form::checkbox('vip', 1, isset($user) ? $user->vip : null, ['id' => 'vip'])}}
                    VIP User
                </label>
            </div>
        </div>

        <div class="form-group">
            <div class="checkbox">
                <label for="is_disabled">
                    <input type="checkbox"
                           id="is_disabled" name="is_disabled"
                           @if(isset($user->is_disabled) && $user->is_disabled) checked @endif>
                    Is disabled ?</label>
            </div>
        </div>

        <div class="form-group">
            <button class="btn btn-success"><i class="fa fa-check-circle"></i> Submit</button>
        </div>
    </div>

    <div class="col-md-6">
        <fieldset>
            <legend>Password</legend>
            <div class="form-group {{$errors->has('password')? 'has-error' : ''}}">
                {{ Form::label('password', 'Password', ['class' => 'control-label']) }}
                {{ Form::password('password', ['class' => 'form-control']) }}
                @if ($errors->has('password'))
                    <div class="error-message">{{$errors->first('password')}}</div>
                @endif
            </div>

            <div class="form-group {{$errors->has('password')? 'has-error' : ''}}">
                {{ Form::label('password_confirmation', 'Confirm Password', ['class' => 'control-label']) }}
                {{ Form::password('password_confirmation', ['class' => 'form-control']) }}
                @if ($errors->has('password_confirmation'))
                    <div class="error-message">{{$errors->first('password_confirmation')}}</div>
                @endif
            </div>

            <div class="form-group {{$errors->has('password')? 'has-error' : ''}}">
                <label for="default_password">
                    <input type="checkbox" id="default_password" name="default_password"> {{t('Default Password')}}
                </label>
                {{--{{ Form::label('default_password', 'Default Password', ['class' => 'control-label']) }}--}}
                {{--{{ Form::checkbox('default_password',0,null, ['class' => 'form-control']) }}--}}
                {{--@if ($errors->has('default_password'))--}}
                {{--<div class="error-message">{{$errors->first('default_password')}}</div>--}}
                {{--@endif--}}
            </div>
        </fieldset>

        <fieldset>
            <legend>Groups</legend>
            <div class="form-group">
                {{Form::label('group_ids', 'Groups', ['class' => 'control-label'])}}
                {{Form::select('group_ids', App\Group::selection(), isset($user) ? $user->groups->pluck('id') : null, ['class' => 'form-control multiple', 'multiple' => true, 'name' => 'group_ids[]'])}}
            </div>
        </fieldset>

        <div class="form-group {{$errors->has('signature')? 'has-error' : ''}}">
            {{ Form::label('signature', 'Signature', ['class' => 'control-label']) }}
            <input type="file" class="form-control" name="signature" id="signature">
            {{--            {{ Form::file('signature', ['class' => 'form-control']) }}--}}
            @if ($errors->has('signature'))
                <div class="error-message">{{$errors->first('signature')}}</div>
            @endif
        </div>
    </div>
</div>