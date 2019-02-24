<div id="Levels">
    <div class="row">
        <div class="col-md-6">
            {{csrf_field()}}

            @if (!empty($business_unit_id))
                {{Form::hidden('business_unit_id', $business_unit_id)}}
            @else
                <div class="form-group {{$errors->has('business_unit_id')? 'has-error' : ''}}">
                    {{Form::label('business_unit_id', 'BusinessUnit', ['class' => 'control-label'])}}
                    {{Form::select('business_unit_id', App\BusinessUnit::selection('Select BusinessUnit'), null, ['class' => 'form-control'])}}
                    @if ($errors->has('business_unit_id'))
                        <div class="error-message">{{$errors->first('business_unit_id')}}</div>
                    @endif
                </div>
            @endif

            <div class="form-group {{$errors->has('name')? 'has-error' : ''}}">
                {{Form::label('name', 'Name', ['class' => 'control-label'])}}
                {{Form::text('name', null, ['class' => 'form-control'])}}
                @if ($errors->has('name'))
                    <div class="error-message">{{$errors->first('name')}}</div>
                @endif
            </div>

            <div class="form-group {{$errors->has('description')? 'has-error' : ''}}">
                {{Form::label('description', 'Description', ['class' => 'control-label'])}}
                {{Form::textarea('description', null, ['class' => 'form-control', 'rows' => 5])}}
                @if ($errors->has('description'))
                    <div class="error-message">{{$errors->first('description')}}</div>
                @endif
            </div>

            <div class="form-group {{$errors->first('service_cost', 'has-error') }}">
                {{Form::label('service_cost', 'Service Cost', ['class' => 'control-label'])}}
                <div class="input-group">
                    {{Form::text('service_cost', null, ['class' => 'form-control'])}}
                    <span class="input-group-addon">SAR</span>
                </div>
                {!! $errors->first('service_cost', '<div class="error-message">:message</div>') !!}
            </div>

            <div class="form-group">
                <input type="checkbox"
                       id="service_request" name="service_request"
                       @if(isset($category->service_request) && $category->service_request ) checked @endif>
                <label for="service_request">Is a service request ?</label>
            </div>

            <fieldset>
                <legend>Roles</legend>
                <approval-levels :roles="{{\App\Role::orderBy('name')->get()}}"
                       :category="{{isset($category) ? $category: 0}}" :approvals="{{isset($category) ? $category->levels: 0}}"></approval-levels>
            </fieldset>

            <div class="form-group">
                <button class="btn btn-success"><i class="fa fa-check-circle"></i> Submit</button>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{$errors->has('units')? 'has-error' : ''}}">
            {{ Form::label('units', 'Business Units', ['class' => 'control-label']) }}
            {{ Form::select('units[]', \App\BusinessUnit::selection(),$category->businessunits ?? null , ['class' => 'form-control', 'multiple' => true ,'size'=>12]) }}
        </div>
    </div>
</div>
<script type="text/javascript" src="{{asset('/js/approval-levels.js')}}"></script>
