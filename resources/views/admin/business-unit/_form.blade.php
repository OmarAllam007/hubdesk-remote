<div id="BusinessRoles">
    <div class="row">
        <div class="col-md-6">
            {{csrf_field()}}
            {{Form::hidden('id')}}

            <div class="form-group {{$errors->has('division_id')? 'has-errors' : ''}}">
                {{Form::label('division_id', 'Division', ['class' => 'control-label'])}}
                {{Form::select('division_id', \App\Division::selection('Select Location'), null, ['class' => 'form-control'])}}
                @if ($errors->has('division_id'))
                    <div class="error-message">{{$errors->first('division_id')}}</div>
                @endif
            </div>
            <div class="form-group {{$errors->has('code')? 'has-errors' : ''}}">
                {{Form::label('code', 'Code', ['class' => 'control-label'])}}
                {{Form::text('code', null, ['class' => 'form-control'])}}
                @if ($errors->has('code'))
                    <div class="error-message">{{$errors->first('code')}}</div>
                @endif
            </div>

            <div class="form-group {{$errors->has('name')? 'has-errors' : ''}}">
                {{Form::label('name', 'Name', ['class' => 'control-label'])}}
                {{Form::text('name', null, ['class' => 'form-control'])}}
                @if ($errors->has('name'))
                    <div class="error-message">{{$errors->first('name')}}</div>
                @endif
            </div>

            <div class="form-group {{$errors->has('location_id')? 'has-errors' : ''}}">
                {{Form::label('location_id', 'Default Location', ['class' => 'control-label'])}}
                {{Form::select('location_id', \App\Location::selection('Select Location'), null, ['class' => 'form-control'])}}
                @if ($errors->has('location_id'))
                    <div class="error-message">{{$errors->first('location_id')}}</div>
                @endif
            </div>
            <div class="form-group {{$errors->has('logo_img')? 'has-errors' : ''}}">
                {{Form::label('logo_img', 'Logo', ['class' => 'control-label'])}}
                {{Form::input('file','logo_img', null, ['class' => 'form-control'])}}
                @if ($errors->has('logo_img'))
                    <div class="error-message">{{$errors->first('logo_img')}}</div>
                @endif
            </div>

            <div class="form-group {{$errors->has('business_unit_bgd_img')? 'has-errors' : ''}}">
                {{Form::label('business_unit_bgd_img', 'Background', ['class' => 'control-label'])}}
                {{Form::input('file','business_unit_bgd_img', null, ['class' => 'form-control'])}}
                @if ($errors->has('business_unit_bgd_img'))
                    <div class="error-message">{{$errors->first('business_unit_bgd_img')}}</div>
                @endif
            </div>

            <fieldset>
                <legend>Roles</legend>
                <roles :users="{{\App\User::orderBy('name')->get()}}" :roles="{{\App\Role::all()}}"
                       :bu_id="{{isset($business_unit) ? $business_unit : 0}}"></roles>
            </fieldset>

            <div class="form-group">
                <button class="btn btn-success"><i class="fa fa-check-circle"></i> Submit</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{asset('/js/roles.js')}}"></script>
