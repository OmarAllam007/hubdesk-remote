@extends('layouts.app')

@section('body')
    <form action="{{route('ticket.survey',[$ticket,$survey])}}" method="post">
        {{csrf_field()}}
        <div class="container-fluid">
            <h4><b>Welcome {{$ticket->requester->name}}</b>,</h4>
            <h4>Survey sent for request " {{$ticket->subject}} "</h4>

            <h5><b>Request ID</b>: {{$ticket->id}} |
                <b>Created On</b>: {{$ticket->created_at->format('d-M-Y h:m a')}} |
                <b>Closed On </b>: {{$ticket->close_date->format('d-M-Y h:m a')}}
            </h5>
            <br>

            <h4><b>Please help us to improve our service by participating in this brief survey.</b></h4>
            <hr>
            @if($ticket->category->survey->first())
                @if($ticket->category->survey->first()->questions->count())
                    @foreach($ticket->category->survey->first()->questions as $question)
                        <div class="panel panel-primary ">
                            <div class="panel-heading ">
                                <h4>{{$question->description}}</h4>
                            </div>
                            <div class="panel-body">
                                @foreach($question->answers as $answer)
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="questions[{{$question->id}}]" id="optionsRadios1" value="{{$answer->id}}">
                                            {{$answer->description}}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif
            @endif
            <hr>
            <br>
            <h4><b>Comments</b></h4>

                <div class="form-group">
                    <textarea class="from-control" name="comment" rows="10" cols="100"></textarea>
                </div>
            <button type="submit" class="btn btn-default">Submit</button>
        </div>
    </form>
@endsection