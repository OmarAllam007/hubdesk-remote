<div class="form-group {{$errors->first('title', 'has-error')}}">
    <label for="title" class="control-label">{{t('Title')}}</label>
    <input type="text" name="title" id="title" class="form-control" value="{{isset($report) ? $report->title : old('title')}} ">
    {!! $errors->first('title', '<div class="help-block>:message</div>') !!}
</div>

<section class="row">
    <article class="form-group col-sm-6 {{$errors->first('core_report_id', 'has-error')}}">
        <label for="core_report_id" class="control-label">{{t('Report Type')}}</label>
        <select name="core_report_id" id="core_report_id" class="form-control">
            <option class="empty" value="">{{t("[Select Report Type]")}}</option>
            @foreach($core_reports as $r)
                <option value="{{$r->id}}" {{$r->id == old('core_report_id')? 'selected' : ''}} @if(isset($report) && $report->core_report_id == $r->id) selected @endif >{{$r->name}}</option>
            @endforeach
        </select>

        {!! $errors->first('core_report_id', '<div class="help-block>:message</div>') !!}
    </article>

    <article class="form-group col-sm-6 {{$errors->first('folder_id', 'has-error')}}">
        <label for="folder_id" class="control-label">{{t('Folder')}}</label>
        <select name="folder_id" id="folder_id" class="form-control">
            <option class="empty" value="">{{t("[Select Report Type]")}}</option>
            @foreach(auth()->user()->folders as $folder)
                <option value="{{$folder->id}}" {{isset($report) && $report->folder_id  == $folder->id ? 'selected' : ''}}>{{$folder->name}}</option>
            @endforeach
        </select>

        {!! $errors->first('folder_id', '<div class="help-block>:message</div>') !!}

        {{--<a href=""></a>--}}
    </article>
</section>

<section>
    <h3 class="section-header">Parameters</h3>

    <section class="row">
        <article class="form-group col-sm-6">
            <label class="control-label" for="from_date">Date From</label>
            <input type="date" name="parameters[start_date]" id="from_date" class="form-control" value="{{ isset($report)  ? $report->parameters['start_date'] : old('parameters.start_date')}}">
        </article>

        <article class="form-group col-sm-6">
            <label for="to_date">To</label>
            <input type="date" name="parameters[end_date]" id="to_date" class="form-control" value="{{isset($report)  ? $report->parameters['end_date'] : old('parameters.end_date')}}">
        </article>
    </section>

    <section class="row">
        <article class="multi-select col-sm-4">
            <label class="control-label" for="">{{t('Technicians')}}</label>
            <a href="#" class="select-all">{{t('Select All')}}</a> / <a href="#" class="remove-all">{{t('Remove All')}}</a>

            <ul class="multi-select-container list-unstyled">
                <li>
                    <input type="search" class="form-control search" placeholder="{{t('Type to search')}}">
                </li>
                @foreach($technicians as $technician)
                    <li class="checkbox">
                        <label>
                            <input type="checkbox"
                                   name="parameters[technician][{{$technician->id}}]"
                                   id="technician_{{$technician->id}}]"
                                   value="{{$technician->id}}"
                                    {{old("parameters.technician.{$technician->id}")? 'checked' : '' }}>
                            <span class="checkbox-label">{{$technician->name}}</span>
                        </label>
                    </li>
                @endforeach
            </ul>
        </article>

        <article class="multi-select col-sm-4">
            <label class="control-label">{{t('Categories')}}</label>
            <a href="#" class="select-all">{{t('Select All')}}</a> / <a href="#" class="remove-all">{{t('Remove All')}}</a>

            <ul class="multi-select-container list-unstyled">
                <li>
                    <input type="search" class="form-control search" placeholder="{{t('Type to search')}}">
                </li>
                @foreach($categories as $category)
                    <li class="checkbox">
                        <label>
                            <input type="checkbox"
                                   name="parameters[category][{{$category->id}}]"
                                   id="category_{{$category->id}}]"
                                   value="{{$category->id}}"
                                    {{old("parameters.category.{$category->id}")? 'checked' : '' }}>

                            <span class="checkbox-label">{{$category->name}}</span>
                        </label>
                    </li>
                @endforeach
            </ul>
        </article>
        <div class="col-sm-4">
            <div class="form-group {{$errors->has('status')? 'has-error' : ''}}">
                {{Form::label('status', 'Status', ['class' => 'control-label'])}}
                {{Form::select('parameters[status][]',App\Status::all()->pluck('name','id'),null,['class'=>'form-control','multiple','size'=>10])}}
                @if ($errors->has('status'))
                    <div class="error-message">{{$errors->first('status')}}</div>
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group {{$errors->has('users')? 'has-error' : ''}}">
                {{Form::label('users', 'Authorized Users', ['class' => 'control-label'])}}
                {{Form::select('users[]',\App\User::technicians()->orderBy('name')->pluck('name','id'),isset($report) ? $report->users->pluck('user_id')->toArray() : [],['class'=>'form-control select2','multiple'])}}
                @if ($errors->has('users'))
                    <div class="error-message">{{$errors->first('users')}}</div>
                @endif
            </div>
        </div>


    </section>


</section>

<div class="form-group">
    <button class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
    {{--<button>Preview</button>--}}
</div>