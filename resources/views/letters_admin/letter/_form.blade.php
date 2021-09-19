{{ csrf_field() }}
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{$errors->has('letter_group_id')? 'has-error' : ''}}">
            {{ Form::label('letter_group_id', 'Letter Group', ['class' => 'control-label']) }}
            <select name="letter_group_id" id="letter_group_id" class="form-control">
                <option value="">Select Group</option>
                @foreach(App\LetterGroup::orderBy('name')->get() as $group)
                    <option value="{{$group->id}}">{{$group->name}}</option>
                @endforeach
            </select>
            @if ($errors->has('letter_group_id'))
                <div class="error-message">{{$errors->first('letter_group_id')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('name')? 'has-error' : ''}}">
            {{ Form::label('name', 'Name', ['class' => 'control-label']) }}
            {{ Form::text('name', null, ['class' => 'form-control']) }}
            @if ($errors->has('name'))
                <div class="error-message">{{$errors->first('name')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('view_path')? 'has-error' : ''}}">
            {{ Form::label('view_path', 'Path (letters.view_path)', ['class' => 'control-label']) }}
            {{ Form::text('view_path', null, ['class' => 'form-control']) }}
            @if ($errors->has('view_path'))
                <div class="error-message">{{$errors->first('view_path')}}</div>
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
