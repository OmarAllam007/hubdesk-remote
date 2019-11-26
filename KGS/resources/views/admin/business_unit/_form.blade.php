<div id="Levels">
    <div class="row">
        <div class="col-md-6">
            {{csrf_field()}}

                <div class="form-group {{$errors->has('business_unit_id')? 'has-error' : ''}}">
                    {{Form::label('business_unit_id', 'BusinessUnits', ['class' => 'control-label'])}}
                    {{Form::select('business_unit_id[]', App\BusinessUnit::orderBy('name')->pluck('name','id'),
                    $business_units, ['class' => 'form-control select2','multiple'=>'true','size'=>'25'])}}
                    @if ($errors->has('business_unit_id'))
                        <div class="error-message">{{$errors->first('business_unit_id')}}</div>
                    @endif
                </div>

            <div class="form-group">
                <button class="btn btn-success"><i class="fa fa-check-circle"></i> Submit</button>
            </div>
    </div>
    </div>

    {{--<div class="col-md-6">--}}
    {{--<div class="form-group {{$errors->has('units')? 'has-error' : ''}}">--}}
    {{--{{ Form::label('units', 'Business Units', ['class' => 'control-label']) }}--}}
    {{--{{ Form::select('units[]', \App\BusinessUnit::selection(),$category->businessunits ?? null , ['class' => 'form-control', 'multiple' => true ,'size'=>12]) }}--}}
    {{--</div>--}}
    {{--</div>--}}
</div>

