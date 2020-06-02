{{Form::model($ticket, ['route' => ['ticket.complaint', $ticket], 'class' => 'modal fade', 'id' => 'ComplaintForm'])}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">{{t('Send Complaint')}}</h4>
        </div>
{{--        @dump($errors)--}}
        <div class="modal-body" id="TicketForm">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group {{$errors->has('complaint.description')? 'has-errors' : ''}}">
                        {{Form::label('complaint[description]', t('Description'), ['class' => 'control-label'])}}
                        {{Form::textarea('complaint[description]', null, ['class' => 'form-control richeditor', 'rows' => 5])}}
                        @error('complaint.description')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
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