<div class="row">
    <div class="col-md-6">
        {{csrf_field()}}

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

      <fieldset>
            <div class="form-group">
                {{Form::label('role_id', 'Roles', ['class' => 'control-label'])}}
                {{Form::select('role_id', App\Role::selection(), null, ['class' => 'form-control multiple', 'multiple' => true, 'name' => 'role_id[]'])}}
            </div>
        </fieldset>

        <fieldset>
            <div class="form-group">
                {{Form::label('user_id', 'Users', ['class' => 'control-label'])}}
                {{Form::select('user_id', App\User::selection(), null, ['class' => 'form-control multiple', 'multiple' => true, 'name' => 'user_id[]'])}}
            </div>
        </fieldset>

        <div class="form-group">
            <button class="btn btn-success"><i class="fa fa-check-circle"></i> Submit</button>
        </div>
    </div>
</div>
