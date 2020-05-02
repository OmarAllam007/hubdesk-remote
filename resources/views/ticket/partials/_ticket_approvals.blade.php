<div class="conversation-approvals">
    @foreach($ticket->approvals as $approval)
        <div class="panel panel-sm panel-warning">
            <div class="panel-heading">
                <h5 class="panel-title"><a href="#approval{{$approval->id}}"
                                           data-toggle="collapse">{{t('Approval Submitted By')}}
                        : {{$approval->created_by->name}}
                        On {{$approval->created_at->format('d/m/Y H:i A')}}
                        To {{$approval->approver->name}}
                    </a>
                </h5>
            </div>
            <div class="panel-body collapse" id="approval{{$approval->id}}">
                <div class="reply">
                    {!! tidy_repair_string($approval->content, [], 'utf8') !!}
                </div>
                <br>

                @if($approval->status != App\TicketApproval::PENDING_APPROVAL && $approval->questions->count())
                    <table class="table table-bordered">
                        <thead style="background-color: cadetblue;color: white;font-weight: bold;">
                        <tr>
                            <td>Question</td>
                            <td>Status</td>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($approval->questions as $question)
                            <tr class="bg-{{$question->color}}">
                                <td>{{$question->description}}</td>
                                <td >{{$question->answer_str}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif

                <span class="label label-default">Status: {{t(\App\TicketApproval::$statuses[$approval->status])}}</span>

                @if($approval->attachments->count())
                    <br><br>
                    <p><strong>Attachments</strong></p>
                    @foreach($approval->attachments as $attachment)
                        <ul class="list-unstyled">
                            <li><a href="{{$attachment->url}}" target="_blank">{{$attachment->display_name}}</a>
                            </li>
                        </ul>
                    @endforeach
                @endif
            </div>
        </div>
    @endforeach
</div>