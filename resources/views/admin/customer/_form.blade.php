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

        <div class="form-group {{$errors->has('mobile')? 'has-errors' : ''}}">
            {{Form::label('mobile', 'Mobile', ['class' => 'control-label'])}}
            {{Form::text('mobile', null, ['class' => 'form-control'])}}
            @if ($errors->has('mobile'))
                <div class="error-message">{{$errors->first('mobile')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('phone')? 'has-errors' : ''}}">
            {{Form::label('phone', 'Phone', ['class' => 'control-label'])}}
            {{Form::text('phone', null, ['class' => 'form-control'])}}
            @if ($errors->has('phone'))
                <div class="error-message">{{$errors->first('phone')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('email')? 'has-errors' : ''}}">
            {{Form::label('email', 'Email', ['class' => 'control-label'])}}
            {{Form::text('email', null, ['class' => 'form-control'])}}
            @if ($errors->has('email'))
                <div class="error-message">{{$errors->first('email')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('city')? 'has-errors' : ''}}">
            {{Form::label('city', 'City', ['class' => 'control-label'])}}
            {{Form::text('city', null, ['class' => 'form-control'])}}
            @if ($errors->has('city'))
                <div class="error-message">{{$errors->first('city')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('business_unit_id')? 'has-errors' : ''}}">
            {{Form::label('business_unit_id', 'Business Unit', ['class' => 'control-label'])}}
            {{Form::select('business_unit_id', \App\BusinessUnit::selection('Select Business Unit'),null, ['class' => 'form-control'])}}
            @if ($errors->has('business_unit_id'))
                <div class="error-message">{{$errors->first('business_unit_id')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('branch_id')? 'has-errors' : ''}}">
            {{Form::label('branch_id', 'Branch', ['class' => 'control-label'])}}
            {{Form::select('branch_id', \App\Branch::selection('Select Branch'),null, ['class' => 'form-control'])}}
            @if ($errors->has('branch_id'))
                <div class="error-message">{{$errors->first('branch_id')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('national_id')? 'has-errors' : ''}}">
            {{Form::label('national_id', 'National ID', ['class' => 'control-label'])}}
            {{Form::text('national_id', null, ['class' => 'form-control'])}}
            @if ($errors->has('national_id'))
                <div class="error-message">{{$errors->first('national_id')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('type')? 'has-errors' : ''}}">
            {{Form::label('type', t('Type'), ['class' => 'control-label'])}}
            {{Form::select('type', [t('individual'),t('corporate')],null, ['class' => 'form-control'])}}
            @if ($errors->has('type'))
                <div class="error-message">{{$errors->first('type')}}</div>
            @endif
        </div>

        <div class="form-group">
            <button class="btn btn-success"><i class="fa fa-check-circle"></i> Submit</button>
        </div>
    </div>
</div>