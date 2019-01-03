<div id="BusinessRoles">
    <div class="row">
        <div class="col-md-6">
            {{csrf_field()}}

            {{Form::hidden('id')}}
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
            <div class="form-group {{$errors->has('logo')? 'has-errors' : ''}}">
                {{Form::label('logo', 'Logo', ['class' => 'control-label'])}}
                {{Form::input('file','logo', null, ['class' => 'form-control'])}}
                @if ($errors->has('logo'))
                    <div class="error-message">{{$errors->first('logo')}}</div>
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
