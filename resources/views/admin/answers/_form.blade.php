<div class="row">
    <div class="col-md-6">
        {{csrf_field()}}
        <div class="form-group {{$errors->has('description')? 'has-error' : ''}}">
            {{Form::label('description', 'Description', ['class' => 'control-label'])}}
            {{Form::text('description', null, ['class' => 'form-control'])}}
            @if ($errors->has('description'))
                <div class="error-message">{{$errors->first('description')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('degree')? 'has-error' : ''}}">
            {{Form::label('degree', 'Degree', ['class' => 'control-label'])}}
            {{Form::input('text','degree', null, ['class' => 'form-control'])}}
            @if ($errors->has('degree'))
                <div class="error-message">{{$errors->first('degree')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('is_default')? 'has-error' : ''}}">
            {{Form::label('is_default', 'Is Default?', ['class' => 'control-label'])}}
            {{Form::checkbox("is_default",1,null, ['class' => 'form-control'])}}
            @if ($errors->has('is_default'))
                <div class="error-message">{{$errors->first('is_default')}}</div>
            @endif
        </div>

        <div class="form-group">
            <button class="btn btn-success"><i class="fa fa-check-circle"></i> Submit</button>
        </div>
    </div>
</div>
