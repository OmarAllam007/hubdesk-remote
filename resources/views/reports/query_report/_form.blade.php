<div class="row" id="reports">
    <div class="col-md-6">
        {{csrf_field()}}
        <div class="form-group {{$errors->has('folder_id')? 'has-error' : ''}}">
            {{Form::label('folder_id', 'Folder', ['class' => 'control-label'])}}
            {{Form::select('folder_id',\App\ReportFolder::all()->pluck('name','id'),$report->folder_id ?? null,['class'=>'form-control'])}}
            @if ($errors->has('folder_id'))
                <div class="error-message">{{$errors->first('folder_id')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('title')? 'has-error' : ''}}">
            {{Form::label('title', 'Title', ['class' => 'control-label'])}}
            {{Form::input('text','title', $report->title ?? null , ['class' => 'form-control'])}}
            @if ($errors->has('title'))
                <div class="error-message">{{$errors->first('title')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('query')? 'has-error' : ''}}">
            {{Form::label('query', 'Query', ['class' => 'control-label'])}}
            {{Form::textarea('query', $report->query ?? null, ['class' => 'form-control'])}}
            @if ($errors->has('query'))
                <div class="error-message">{{$errors->first('query')}}</div>
            @endif
        </div>


        <div class="form-group {{$errors->has('users')? 'has-error' : ''}}">
            {{Form::label('users', 'Authorized Users', ['class' => 'control-label'])}}
            {{Form::select('users[]',\App\User::technicians()->orderBy('name')->pluck('name','id'),isset($report) ? $report->users->pluck('user_id')->toArray() : [],['class'=>'form-control select2','multiple'])}}
            @if ($errors->has('users'))
                <div class="error-message">{{$errors->first('users')}}</div>
            @endif
        </div>

        <div class="form-group">
            <button class="btn btn-success"><i class="fa fa-check-circle"></i> {{t('Submit')}}</button>
        </div>
    </div>
    <div class="col-md-6">
        <report-parameters :params="{{json_encode($report->parameters)}}"></report-parameters>
    </div>
</div>
