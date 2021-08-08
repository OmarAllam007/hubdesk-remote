{{ csrf_field() }}
<div class="row">
    <div class="col-md-6">


        <div class="form-group {{$errors->has('business_unit_id')? 'has-error' : ''}}">
            {{ Form::label('business_unit_id', 'User', ['class' => 'control-label']) }}
            <select name="business_unit_id" id="business_unit_id" class="form-control select2 ">
                <option value="" selected disabled>Business Unit</option>
                @foreach($businessUnits as $businessUnit)
                    <option value="{{$businessUnit->id}}"
                            @if(isset($header) && $header->business_unit_id == $businessUnit->id) selected @endif
                    >{{$businessUnit->name}}</option>
                @endforeach
            </select>
            @if ($errors->has('business_unit_id'))
                <div class="error-message">{{$errors->first('business_unit_id')}}</div>
            @endif
        </div>


        <div class="form-group {{$errors->has('path')? 'has-error' : ''}}">
            {{ Form::label('path', 'Image', ['class' => 'control-label']) }}
            {{ Form::file('path', ['class' => 'form-control']) }}
            @if ($errors->has('path'))
                <div class="error-message">{{$errors->first('path')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('stamp_path')? 'has-error' : ''}}">
            {{ Form::label('stamp_path', 'Stamp', ['class' => 'control-label']) }}
            {{ Form::file('stamp_path', ['class' => 'form-control']) }}
            @if ($errors->has('stamp_path'))
                <div class="error-message">{{$errors->first('stamp_path')}}</div>
            @endif
        </div>


        <div class="form-group">
            <button class="btn btn-success"><i class="fa fa-check"></i> Submit</button>
        </div>
    </div>

</div>
