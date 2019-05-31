{{ csrf_field() }}
<div id="TicketForm">

    <div class="form-group form-group-sm {{$errors->has('name')? 'has-error' : ''}}">
        {{ Form::label('name', t('Document Name'), ['class' => 'control-label']) }}
        {{ Form::text('name', null, ['class' => 'form-control']) }}
        @if ($errors->has('name'))
            <div class="error-message">{{$errors->first('name')}}</div>
        @endif
    </div>

    <div class="form-group form-group-sm {{$errors->has('start_date')? 'has-error' : ''}}">
        {{ Form::label('start_date', t('Start Date'), ['class' => 'control-label']) }}
        {{ Form::date('start_date', isset($document) && $document->start_date ? $document->start_date->format('Y-m-d') : '', ['class' => 'form-control']) }}
        @if ($errors->has('start_date'))
            <div class="error-message">{{$errors->first('start_date')}}</div>
        @endif
    </div>

    <div class="form-group form-group-sm {{$errors->has('end_date')? 'has-error' : ''}}">
        {{ Form::label('end_date', t('End Date'), ['class' => 'control-label']) }}
        {{ Form::date('end_date', isset($document) &&  $document->end_date ? $document->end_date->format('Y-m-d') : '' , ['class' => 'form-control']) }}
        @if ($errors->has('end_date'))
            <div class="error-message">{{$errors->first('end_date')}}</div>
        @endif
    </div>

    <div class="form-group form-group-sm {{$errors->has('document')? 'has-error' : ''}}">
        {{ Form::label('document', t('Document'), ['class' => 'control-label']) }}
        {{ Form::file('document', null, ['class' => 'form-control']) }}
        @if ($errors->has('document'))
            <div class="error-message">{{$errors->first('document')}}</div>
        @endif
    </div>

</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <button class="btn btn-success"><i class="fa fa-check"></i> {{t('Submit')}}</button>
        </div>
    </div>
</div>
@section('javascript')

@append