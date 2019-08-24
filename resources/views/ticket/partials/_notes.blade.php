
@if($ticket->notes->count())
    <div class="panel panel-default panel-design">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="fa fa-sticky-note-o"></i> {{t('Discussion Notes')}}</h4>
        </div>
        <table class="table table-striped table-condensed details-tbl">
            <thead>
            <tr>
                <th>{{t('Created By')}}</th>
                <th>{{t('Note')}}</th>
                <th>{{t('Created at')}}</th>
                <th>{{t('Actions')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($ticket->notes as $note)
                <tr>
                    <td>{{$note->creator->name}}</td>
                    <td>@if($note->display_to_requester || Auth::user()->isSupport()) {!!$note->note !!} @else <b>private</b> @endif
                    </td>
                    <td>{{$note->created_at->format('d/m/Y H:i A') }}</td>
                    <td>
                        <button type="button" id="editNote" data-note="{{$note}}"
                                class="btn btn-primary btn-xs editNote" data-toggle="modal"
                                data-target="#ReplyModal">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button type="button" id="removeNote" data-note="{{$note}}"
                                class="btn btn-danger btn-xs removeNote" data-toggle="modal"
                                data-target="#removeNoteModal">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif