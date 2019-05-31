{{Form::model($ticket, ['route' => ['kgs.ticket.finance.send', $ticket], 'class' => 'modal fade', 'id' => 'FinanceForm'])}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">{{t('Send to Finance')}}</h4>
        </div>
        <div class="modal-body" id="TicketForm">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group form-group-sm {{$errors->has('to')? 'has-error' : ''}}">
                        {{Form::label('to', t('To'), ['class' => 'control-label'])}}
                        <select class="form-control select2" name="to[]" multiple>
                            <option value="">{{t('Select User')}}</option>
                            @foreach(App\User::whereNotNull('email')->orderBy('name')->get() as $user)
                                <option value="{{$user->id}}">
                                    {{$user->name}} ( {{$user->email}} )
                                </option>
                            @endforeach
                        </select>
                        @if (count($errors)&&count($errors->has('to')))
                            <div class="error-message">
                                {{ $errors->first() }}
                            </div>
                        @endif
                    </div>


                    <div class="form-group {{$errors->has('finance_content')? 'has-errors' : ''}}">
                        {{Form::label('finance_content', t('Description'), ['class' => 'control-label'])}}
                        {{Form::textarea('finance_content', null, ['class' => 'form-control richeditor', 'rows' => 5])}}
                        @if ($errors->has('finance_content'))
                            <div class="error-message">{{$errors->first('finance_content')}}</div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-send"></i> {{t('Send')}}</button>
        </div>
    </div>
</div>
{{Form::close()}}