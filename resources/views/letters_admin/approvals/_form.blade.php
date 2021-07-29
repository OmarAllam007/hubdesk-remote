{{ csrf_field() }}
<div class="row">
    <div class="col-md-6">
        {{Form::hidden('business_unit_id', $businessUnit->id)}}

        <div class="form-group {{$errors->has('role_id')? 'has-error' : ''}}">
            {{ Form::label('role_id', 'Role', ['class' => 'control-label']) }}
            <select name="role_id" id="role_id" class="form-control select2 ">
                <option value="">Select Role</option>
                @foreach(App\Role::orderBy('name')->get() as $role)
                    <option value="{{$role->id}}">{{$role->name}}</option>
                @endforeach
            </select>
            @if ($errors->has('role_id'))
                <div class="error-message">{{$errors->first('role_id')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('user_id')? 'has-error' : ''}}">
            {{ Form::label('user_id', 'User', ['class' => 'control-label']) }}
            <select name="user_id" id="user_id" class="form-control select2 ">
                <option value="">Select User</option>
                @foreach($users as $role)
                    <option value="{{$role->id}}">{{$role->name}}</option>
                @endforeach
            </select>
            @if ($errors->has('user_id'))
                <div class="error-message">{{$errors->first('user_id')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('order')? 'has-error' : ''}}">
            {{ Form::label('order', 'Order', ['class' => 'control-label']) }}
            {{ Form::number('order', null, ['class' => 'form-control']) }}
            @if ($errors->has('order'))
                <div class="error-message">{{$errors->first('order')}}</div>
            @endif
        </div>

        <div class="form-group">
            <button class="btn btn-success"><i class="fa fa-check"></i> Submit</button>
        </div>
    </div>

</div>
