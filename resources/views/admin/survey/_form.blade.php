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

        <div class="form-group {{$errors->has('category_id')? 'has-errors' : ''}}">
            {{Form::label('category_id', 'Category', ['class' => 'control-label'])}}
            {{Form::select('category_id[]', \App\Category::selection('Select Category'),$survey->categories ?? null , ['class' => 'form-control','multiple','size'=>20])}}
            @if ($errors->has('category_id'))
                <div class="error-message">{{$errors->first('category_id')}}</div>
            @endif
        </div>


        <div class="form-group">
            <button class="btn btn-success"><i class="fa fa-check-circle"></i> Submit</button>
        </div>
    </div>
</div>