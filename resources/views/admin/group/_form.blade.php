{{ csrf_field() }}
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{$errors->has('name')? 'has-error' : ''}}">
            {{ Form::label('name', 'Name', ['class' => 'control-label']) }}
            {{ Form::text('name', null, ['class' => 'form-control']) }}
            @if ($errors->has('name'))
                <div class="error-message">{{$errors->first('name')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('type')? 'has-error' : ''}}">
            {{ Form::label('type', 'Type', ['class' => 'control-label']) }}
            {{ Form::select('type', App\Group::types(), null, ['class' => 'form-control']) }}
            @if ($errors->has('type'))
                <div class="error-message">{{$errors->first('type')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('description')? 'has-error' : ''}}">
            {{ Form::label('description', 'Description', ['class' => 'control-label']) }}
            {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => 3]) }}
            @if ($errors->has('description'))
                <div class="error-message">{{$errors->first('description')}}</div>
            @endif
        </div>

        <div class="form-group">
            <input type="checkbox"
                   id="is_disabled" name="is_disabled"
                   @if(isset($group) && $group->is_disabled) checked @endif>
            <label for="is_disabled">Is disabled ?</label>
        </div>

        <div class="form-group">
            <button class="btn btn-success"><i class="fa fa-check"></i> Submit</button>
        </div>
    </div>

{{--    <div class="col-md-6">--}}
{{--        @php--}}
{{--            $users = \App\User::selection();--}}
{{--        @endphp--}}
{{--        <div class="form-group {{$errors->has('users')? 'has-error' : ''}}">--}}
{{--            {{ Form::label('users', 'Users', ['class' => 'control-label']) }}--}}
{{--            {{ Form::select('users[]', $users ,null, ['class' => 'form-control select2', 'multiple' => true ,'size'=>12]) }}--}}
{{--        </div>--}}

{{--        <div class="form-group {{$errors->has('supervisors')? 'has-error' : ''}}">--}}
{{--            {{ Form::label('supervisors', 'Supervisors', ['class' => 'control-label']) }}--}}
{{--            {{ Form::select('supervisors[]', $users ,null, ['class' => 'form-control select2', 'multiple' => true ,'size'=>12]) }}--}}
{{--        </div>--}}

{{--    </div>--}}
</div>
