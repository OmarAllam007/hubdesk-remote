@if ($ticket->resolution)
    <div class="well well-sm well-white resolution-area">
        <div class="row">
            <div class="col-md-4"><p>{{t('Added by')}}{{$ticket->resolution->user->name }}
                    at {{$ticket->resolution->created_at->format('d/m/Y H:i:s')}}

                </p></div>
            <div class="col-md-2">
                @if(Auth::user()->id == $ticket->resolution->user_id)
                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#editResolution">
                        {{t('Edit')}}
                    </button>
                @endif
            </div>
        </div>
        <hr style="background-color:black; height: 1px; border: 0; margin:0 0 5px 0px">

        {!! tidy_repair_string($ticket->resolution->content,[],'utf8') !!}
    </div>
@elseif (can('resolve', $ticket))
    {{Form::open(['route' => ['ticket.resolution', $ticket],'files'=>'true'])}}
    {{csrf_field()}}

    <div class="row">
        <div class="form-group">
            @if(auth()->user()->isSupport() && auth()->user()->reply_templates->count())
                <div class="col-md-6">
                    <div class="form-group">
                        {{Form::label('template', t('Reply Templates') , ['class' => 'control-label'])}}
                        {{Form::select('template', auth()->user()->reply_templates->pluck('title','id')->prepend('Select Template',""), null, ['class' => 'form-control'])}}
                        @if ($errors->has('template'))
                            <div class="error-message">{{$errors->first('template')}}</div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="form-group">
        {{Form::label('content', t('Description'), ['class' => 'control-label'])}}
        {{Form::textarea('content', null, ['class' => 'form-control richeditor'])}}
        @error('content')
            <div class="error-message">{{$errors->first('content')}}</div>
        @enderror
    </div>

    <div class="row">
        <div class="col-md-4">
            <table class="listing-table table-condensed">
                <thead>
                <tr>
                    <th>{{t('Attachments')}}</th>

                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="col-md-10">
                        <input type="file" class="form-control input-xs" name="attachments[]" multiple>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> {{t('Add resolution')}}</button>
    </div>
    {{Form::close()}}
@endif

@if($ticket-> resolution && Auth::user()->id == $ticket->resolution->user_id)
    <div id="editResolution" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">{{t('Edit Resolution')}}</h4>
                </div>
                <div class="modal-body">
                    {{Form::open(['route' => ['ticket.edit-resolution', $ticket]])}}
                    {{csrf_field()}}
                    <div class="form-group">
                        {{Form::label('content', 'Description', ['class' => 'control-label'])}}
                        {{Form::textarea('content',  $ticket->resolution->content ?? '' , ['class' => 'form-control richeditor'])}}
                    </div>


                    <div class="form-group">
                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> {{t('Save')}}
                        </button>
                    </div>
                    {{Form::close()}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{t('Close')}}</button>
                </div>
            </div>

        </div>
    </div>
@endif

