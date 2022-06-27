<div class="row p-5 ">
    <div class="col-md-6 bg-white p-10 rounded-xl shadow-md">

        <div class="form-group {{$errors->has('employee_id')? 'has-error' : ''}}">
            <i class="fa fa-user "></i> {{ Form::label('employee_id', 'Employee ID', ['class' => 'control-label']) }}
            {{ Form::text('employee_id', isset($user) ? $user->employee_id : null, ['class' => 'form-control','autocomplete'=>'off']) }}
            @if ($errors->has('employee_id'))
                <div class="error-message">{{$errors->first('employee_id')}}</div>
            @endif
        </div>


        <div class="form-group {{$errors->has('name')? 'has-error' : ''}}">
            <i class="fa fa-user-plus"></i> {{ Form::label('name', 'Name', ['class' => 'control-label']) }}
            {{ Form::text('name', isset($user) ? $user->name : null, ['class' => 'form-control','autocomplete'=>'off']) }}
            @if ($errors->has('name'))
                <div class="error-message">{{$errors->first('name')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('email')? 'has-error' : ''}}">
            <i class="fa fa-envelope"></i> {{ Form::label('email', 'Email', ['class' => 'control-label']) }}
            {{ Form::email('email', isset($user) ? $user->email : null, ['class' => 'form-control', 'autocomplete'=>'off']) }}
            @if ($errors->has('email'))
                <div class="error-message">{{$errors->first('email')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('position')? 'has-error' : ''}}">
            <i class="fa fa-briefcase"></i> {{ Form::label('position', 'Position', ['class' => 'control-label']) }}
            {{ Form::text('position', isset($user) ? $user->position : null, ['class' => 'form-control','autocomplete'=>'off']) }}
            @if ($errors->has('position'))
                <div class="error-message">{{$errors->first('position')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('business_unit')? 'has-error' : ''}}">
            <i class="fa fa-building"></i> {{ Form::label('business_unit', 'Business Unit', ['class' => 'control-label']) }}
            {{ Form::text('business_unit', isset($user) ? $user->business_unit : null, ['class' => 'form-control','autocomplete'=>'off']) }}

            @if ($errors->has('business_unit'))
                <div class="error-message">{{$errors->first('business_unit')}}</div>
            @endif
        </div>


        <div class="form-group {{$errors->has('department')? 'has-error' : ''}}">
            <i class="fa fa-briefcase"></i> {{ Form::label('department', 'Department', ['class' => 'control-label']) }}
            {{ Form::text('department', isset($user) ? $user->department : null, ['class' => 'form-control','autocomplete'=>'off']) }}
            @if ($errors->has('department'))
                <div class="error-message">{{$errors->first('department')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('gender')? 'has-error' : ''}}">
            <i class="fa fa-venus-mars"></i> {{ Form::label('gender', 'Gender', ['class' => 'control-label']) }}
            <select name="gender" id="gender" class="form-control select2">
                <option value="">{{t('Select Gender')}}</option>
                <option value="1" @if(isset($user) && $user->gender == 1) selected @endif>Male</option>
                <option value="2" @if(isset($user) && $user->gender == 2) selected @endif>Female</option>
            </select>
            @if ($errors->has('gender'))
                <div class="error-message">{{$errors->first('gender')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('phone')? 'has-error' : ''}}">
            <i class="fa fa-phone"></i> {{ Form::label('phone', 'Phone', ['class' => 'control-label']) }}
            {{ Form::text('phone', isset($user) ? $user->phone : null, ['class' => 'form-control','autocomplete'=>'off']) }}
            @if ($errors->has('phone'))
                <div class="error-message">{{$errors->first('phone')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('mobile')? 'has-error' : ''}}">
            <i class="fa fa-mobile"></i> {{ Form::label('mobile', 'Mobile', ['class' => 'control-label']) }}
            {{ Form::text('mobile', isset($user) ? $user->mobile : null, ['class' => 'form-control','autocomplete'=>'off']) }}
            @if ($errors->has('mobile'))
                <div class="error-message">{{$errors->first('mobile')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('website')? 'has-error' : ''}}">
            <i class="fa fa-link"></i> {{ Form::label('website', 'Website', ['class' => 'control-label']) }}
            {{ Form::text('website', isset($user) ? $user->website : null, ['class' => 'form-control','autocomplete'=>'off']) }}
            @if ($errors->has('website'))
                <div class="error-message">{{$errors->first('website')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('facebook_url')? 'has-error' : ''}}">
            <i class="fa fa-facebook-square"></i> {{ Form::label('facebook_url', 'Facebook', ['class' => 'control-label']) }}
            {{ Form::text('facebook_url', isset($user) ? $user->facebook_url : null, ['class' => 'form-control','autocomplete'=>'off']) }}
            @if ($errors->has('facebook_url'))
                <div class="error-message">{{$errors->first('facebook_url')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('twitter_url')? 'has-error' : ''}}">
            <i class="fa fa-twitter-square"></i> {{ Form::label('twitter_url', 'Twitter', ['class' => 'control-label']) }}
            {{ Form::text('twitter_url', isset($user) ? $user->twitter_url : null, ['class' => 'form-control','autocomplete'=>'off']) }}
            @if ($errors->has('twitter_url'))
                <div class="error-message">{{$errors->first('twitter_url')}}</div>
            @endif
        </div>


        <div class="form-group {{$errors->has('linkedin_url')? 'has-error' : ''}}">
            <i class="fa fa-linkedin-square"></i> {{ Form::label('linkedin_url', 'Linkedin', ['class' => 'control-label']) }}
            {{ Form::text('linkedin_url', isset($user) ? $user->linkedin_url : null, ['class' => 'form-control','autocomplete'=>'off']) }}
            @if ($errors->has('linkedin_url'))
                <div class="error-message">{{$errors->first('linkedin_url')}}</div>
            @endif
        </div>



        <div class="form-group {{$errors->has('image_url')? 'has-error' : ''}}">
            <i class="fa fa-image"></i> {{ Form::label('image_url', 'Image Url', ['class' => 'control-label','autocomplete'=>'off']) }}
            <input type="file" class="form-control" name="image_url" id="image_url">
            @if ($errors->has('image_url'))
                <div class="error-message">{{$errors->first('image_url')}}</div>
            @endif
        </div>


        @if(isset($user))
            <div class="form-group {{$errors->has('password')? 'has-error' : ''}}">
                <label for="remove_image">
                    <input type="checkbox" id="remove_image" name="remove_image"> {{t('Remove Image')}}
                </label>
            </div>
        @endif


        <div class="form-group">
            <button class="btn btn-success"><i class="fa fa-check-circle"></i> {{t('Submit')}}</button>
        </div>
    </div>

    @if(isset($user) && $user->image_url)
        <div class="pl-5 col-md-3 ">
            <img src="{{$user->image}}" class="rounded-2xl h-96 w-96 object-fill shadow-md hover:shadow-lg" height="200"
                 width="200">
        </div>
    @endif
</div>

