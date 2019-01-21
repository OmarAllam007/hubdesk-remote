@foreach($ticket->replies()->latest()->get() as $reply)
    <div class="panel panel-sm panel-{{$reply->class}}">
        <div class="panel-heading">
            <h5 class="panel-title"><a href="#reply{{$reply->id}}" data-toggle="collapse">{{t('By')}}
                    : {{$reply->user->name}} On {{$reply->created_at->format('d/m/Y H:i A')}}</a></h5>
        </div>
        <div class="panel-body collapse" id="reply{{$reply->id}}">
            <div class="reply">
                {!! tidy_repair_string($reply->content, [], 'utf8') !!}
            </div>
            <br>
            @if($reply->attachments->count())
                <br><br>
                <p><strong>Attachments</strong></p>
                @foreach($reply->attachments as $attachment)
                    <ul class="list-unstyled">
                        <li><a href="{{$attachment->url}}" target="_blank">{{$attachment->display_name}}</a>
                        </li>
                    </ul>
                @endforeach
            @endif
        </div>
    </div>
@endforeach