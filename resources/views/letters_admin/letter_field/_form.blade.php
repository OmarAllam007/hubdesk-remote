{{ csrf_field() }}
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{$errors->has('letter_id')? 'has-error' : ''}}">
            {{ Form::label('letter_id', 'Letter', ['class' => 'control-label']) }}
            <select name="letter_id" id="letter_id" class="form-control select2">
                <option value="">Select Letter</option>
                @foreach(App\Letter::orderBy('name')->get() as $letter)
                    <option value="{{$letter->id}}"
                    @if(isset($letter_field) && $letter_field->letter_id == $letter->id) selected @endif>
                        {{$letter->name}}</option>
                @endforeach
            </select>
            @if ($errors->has('letter_id'))
                <div class="error-message">{{$errors->first('letter_id')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('name')? 'has-error' : ''}}">
            {{ Form::label('name', 'Name', ['class' => 'control-label']) }}
            {{ Form::text('name', null, ['class' => 'form-control']) }}
            @if ($errors->has('name'))
                <div class="error-message">{{$errors->first('name')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('type')? 'has-error' : ''}}">
            {{ Form::label('type', 'Letter Group', ['class' => 'control-label']) }}
            <select name="type" id="type" class="form-control select2">
                <option value="">Select Letter</option>
                @foreach(App\LetterField::TYPES as $key=>$type)
                    <option value="{{$key}}" @if(isset($letter_field) && $letter_field->type == $key) selected @endif>{{$type}}</option>
                @endforeach
            </select>
            @if ($errors->has('type'))
                <div class="error-message">{{$errors->first('type')}}</div>
            @endif
        </div>


        <div class="form-group">
            <button class="btn btn-success"><i class="fa fa-check"></i> Submit</button>
        </div>
    </div>

</div>
