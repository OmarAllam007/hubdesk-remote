{{ csrf_field() }}
<div id="TicketForm">

    <div class="form-group form-group-sm @error('name') ? 'has-error' : '' @enderror">
        {{ Form::label('name', t('Folder Name'), ['class' => 'control-label']) }}
        {{ Form::text('name', null, ['class' => 'form-control']) }}
        @if ($errors->has('name'))
            <div class="error-message">{{$errors->first('name')}}</div>
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