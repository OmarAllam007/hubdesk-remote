<div class="row">
    <div class="col-md-6">
        {{csrf_field()}}

        <div class="form-group {{$errors->has('title')? 'has-errors' : ''}}">
            {{Form::label('title', 'Title', ['class' => 'control-label'])}}
            {{Form::text('title', null, ['class' => 'form-control'])}}
            @if ($errors->has('title'))
                <div class="error-message">{{$errors->first('title')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('description')? 'has-error' : ''}}">
            {{Form::label('description', 'Description', ['class' => 'control-label'])}}
            {{Form::textarea('description', null, ['class' => 'form-control richeditor', 'rows' => 5])}}
            @if ($errors->has('description'))
                <div class="error-message">{{$errors->first('description')}}</div>
            @endif
        </div>

        <div class="form-group">
            <button class="btn btn-success"><i class="fa fa-check-circle"></i> {{t('Submit')}}</button>
        </div>
    </div>
</div>