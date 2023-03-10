{{Form::model($ticket, ['route' => ['ticket.forward', $ticket], 'class' => 'modal fade', 'id' => 'ForwardForm'])}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">{{t('Forward Ticket')}}</h4>
        </div>
        <div class="modal-body" id="TicketForm">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group form-group-sm @error('to')? 'has-error' : '' @enderror">
                        {{Form::label('to', t('To'), ['class' => 'control-label'])}}
                        <select class="form-control select2" name="to[]" multiple>
                            @foreach($users as $user)
                                <option value="{{$user->email}}">{{$user->name}} - {{$user->email}}</option>
                            @endforeach
                        </select>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <div class="form-group form-group-sm">
                        {{Form::label('cc', t('Cc'), ['class' => 'control-label'])}}
                        <select class="form-control select2" name="cc[]" multiple>
                            <option value="">{{t('Select')}}</option>
                            @foreach($users as $user)
                                <option value="{{$user->email}}">{{$user->name}} - {{$user->email}}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="form-group {{$errors->has('forward.description')? 'has-errors' : ''}}">
                        {{Form::label('forward[description]', t('Description'), ['class' => 'control-label'])}}
                        {{Form::textarea('forward[description]', null, ['class' => 'form-control richeditor', 'rows' => 5])}}
                        @if ($errors->has('forward.content'))
                            <div class="error-message">{{$errors->first('forward.content')}}</div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-mail-forward"></i> {{t('Forward')}}</button>
        </div>
    </div>
</div>
{{Form::close()}}