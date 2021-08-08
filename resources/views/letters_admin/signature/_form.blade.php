{{ csrf_field() }}
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{$errors->has('letter_id')? 'has-error' : ''}}">
            {{ Form::label('letter_id', 'User', ['class' => 'control-label']) }}

            <select name="letter_id" id="letter_id" class="form-control select2">
                <option value="" selected>Letter</option>
                @foreach($letters as $letter)
                    <option value="{{$letter->id}}"
                            @if(isset($signature) && $signature->letter_id == $letter->id) selected @endif>
                        {{$letter->group->name .' > '. $letter->name}}</option>
                @endforeach
            </select>
            @if ($errors->has('letter_id'))
                <div class="error-message">{{$errors->first('letter_id')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('business_unit_id')? 'has-error' : ''}}">
            {{ Form::label('business_unit_id', 'User', ['class' => 'control-label']) }}
            <select name="business_unit_id" id="business_unit_id" class="form-control select2 ">
                <option value="" selected disabled>Business Unit</option>
                @foreach($businessUnits as $businessUnit)
                    <option value="{{$businessUnit->id}}"
                            @if(isset($signature) && $signature->business_unit_id == $businessUnit->id) selected @endif
                    >{{$businessUnit->name}}</option>
                @endforeach
            </select>
            @if ($errors->has('business_unit_id'))
                <div class="error-message">{{$errors->first('business_unit_id')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('user_id')? 'has-error' : ''}}">
            {{ Form::label('user_id', 'User', ['class' => 'control-label']) }}
            <select name="user_id" id="user_id" class="form-control select2 ">
                <option value="" selected disabled>Select User</option>
                @foreach($users as $user)
                    <option value="{{$user->id}}"
                            @if(isset($signature) && $signature->user_id == $user->id) selected @endif
                    >{{$user->name .' - '. $user->email }}</option>
                @endforeach
            </select>
            @if ($errors->has('user_id'))
                <div class="error-message">{{$errors->first('user_id')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('order')? 'has-error' : ''}}">
            {{ Form::label('order', 'Order', ['class' => 'control-label']) }}
            {{ Form::number('order', null, ['class' => 'form-control','placeholder'=>'0']) }}
            @if ($errors->has('order'))
                <div class="error-message">{{$errors->first('order')}}</div>
            @endif
        </div>


        <div class="form-group">
            <button class="btn btn-success"><i class="fa fa-check"></i> Submit</button>
        </div>
    </div>

</div>
