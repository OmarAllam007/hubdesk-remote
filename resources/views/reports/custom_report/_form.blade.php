{{method_field('post')}}
<div class="row">
    <div class="col-md-6">
        {{csrf_field()}}

        <div class="form-group {{$errors->has('folder_id')? 'has-error' : ''}}">
            {{Form::label('folder_id', 'Folder', ['class' => 'control-label'])}}
            {{Form::select('folder_id',$folders,$report->folder_id ?? null,['class'=>'form-control'])}}
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


        <div class="form-group {{$errors->has('fields')? 'has-error' : ''}}">
            <label for="columns">{{t('Select Required Columns')}}</label>
            <select name="fields[]" id="columns" class="form-control" size="15" multiple>
                <option value="id">Ticket ID</option>
                <option value="requester">Requester</option>
                <option value="technician">Technician</option>
                <option value="created_by">Created By</option>
                <option value="category">Category</option>
                <option value="subcategory">Subcategory</option>
                <option value="item">Item</option>
                <option value="group">Group</option>
                <option value="created_at">Created Date</option>
                <option value="updated_at">Last Update</option>
                <option value="resolve_date">Resolved Date</option>
                <option value="close_date">Closed Date</option>
                <option value="status">Status</option>
                <option value="business_unit">Business Unit</option>
                <option value="performance">Performance</option>
            </select>
            @if ($errors->has('fields'))
                <div class="error-message">{{$errors->first('fields')}}</div>
            @endif
        </div>

        <div class="form-inline form-group">
            <label for="date_filters">{{t('Date Filter')}}</label>
            <select id="date_filters" name="date_filters[by]" class="form-control">
                <option value="created_at">Created Date</option>
                <option value="due_date">Due Date</option>
                <option value="resolved_date">Resolved Date</option>
            </select>

            <label for="from" class="control-label {{$errors->has('date_filters.from')? 'has-error' : ''}}"
                   style="padding-left: 10px">
                {{t('From')}}
                <input type="date" name="date_filters[from]" id="from" class="form-control">
            </label>

            <label for="to" class="control-label">
                {{t('To')}}
                <input type="date" name="date_filters[to]" id="to" class="form-control">
            </label>
            <div class="error-message">{{$errors->first('date_filters.from')}}</div>

        </div>

        <div class="form-group">
            <label for="business_unit_filter">{{t('Service Unit')}}</label>
            <select id="business_unit_filter" name="business_unit_filter[]" class="form-control" multiple size="20">
                <option value="">Select</option>
                @foreach(\App\BusinessUnit::whereHas('categories')->orderBy('name')->get() as $bu)
                    <option value="{{$bu->id}}">{{$bu->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="technicians_filter">{{t('Technicians')}}</label>
            <select id="technicians_filter" name="technicians_filter[]" class="form-control" multiple size="20">
                <option value="">Select</option>
                @foreach(\App\User::technicians()->orderBy('name')->get() as $user)
                    <option value="{{$user->id}}">{{$user->name}}</option>
                @endforeach
            </select>
        </div>




        <div class="form-group">
            <label for="group_by">{{t('Group By')}}</label>
            <select id="group_by" name="group_by" class="form-control">
                <option value="">Select</option>
                <option value="ID">Ticket ID</option>
                <option value="Requester">Requester</option>
                <option value="Technician">Technician</option>
                <option value="Created By">Created By</option>
                <option value="Category">Category</option>
                <option value="Subcategory">Subcategory</option>
                <option value="Item">Item</option>
                <option value="Group">Group</option>
                <option value="Created_at">Created Date</option>
                <option value="Updated_at">Last Update</option>
                <option value="Resolve_date">Resolved Date</option>
                <option value="Closed_date">Closed Date</option>
                <option value="Business Unit">Business Unit</option>
                <option value="Status">Status</option>
                <option value="Performance">Performance</option>
            </select>
        </div>

        <div class="form-group">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <strong>
                        {{t('Graph')}}
                    </strong>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr class="warning">
                            <td>Type</td>
                            <td>Bar Chart</td>
                            <td>Line Chart</td>
                            <td>Pie Chart</td>
                        </tr>
                        </thead>
                        <tr>
                            <td>
                            </td>
                            <td>
                                <input type="checkbox" name="summary_by[bar]">
                            </td>
                            <td>
                                <input type="checkbox" name="summary_by[line]">
                            </td>
                            <td>
                                <input type="checkbox" name="summary_by[pie]">
                            </td>

                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="form-group">
            <button class="btn btn-success"><i class="fa fa-check-circle"></i> {{t('Submit')}}</button>
        </div>
    </div>
</div>
