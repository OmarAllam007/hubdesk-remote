@extends('layouts.app')

@section('body')

    @if(can('show_survey',$ticket))

        @if($survey->is_submitted)
            <div class="container text-center">
                <div class="alert alert-info alert-dismissible text-center" role="alert">
                    <h4><strong>{{t('Your survey information for this request has already been received for consideration')}}.
                            <a href="{{route('ticket.show',$ticket)}}">{{t('Return to the ticket')}}</a></strong></h4>
                </div>
            </div>
        @else
            <form action="{{route('ticket.survey',[$ticket,$survey])}}" style="margin-bottom: 20px" method="post">
                {{csrf_field()}}
                <div class="container">
                    <h4><b>{{t('Welcome')}} {{$ticket->requester->name}}</b>,</h4>
                    <h4>{{t('Survey sent for request')}} " {{$ticket->subject}} "</h4>

                    <h5><b>{{t('Request ID')}}</b>: {{$ticket->id}} |
                        <b>{{t('Created On')}}</b>: {{$ticket->created_at ? $ticket->created_at->format('d-M-Y h:m a') : ''}} |
                        <b>{{t('Closed On')}} </b>: {{$ticket->close_date ? $ticket->close_date->format('d-M-Y h:m a') : ''}}
                    </h5>
                    <br>
                    <h4><b>{{t('Please help us to improve our service by participating in this brief survey')}}.</b></h4>
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
                                                    <input type="radio" name="questions[{{$question->id}}]"
                                                           id="optionsRadios1"
                                                           value="{{$answer->id}}">
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
                    <h4><b>{{t('Your suggestions will help us improve our service, kindly let us know if any')}}</b></h4>

                    <div class="form-group">
                        <textarea class="from-control" name="comment" rows="10" cols="100"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">{{t('Submit')}}</button>
                </div>
            </form>
        @endif
    @else
        <div class="container text-center">
            <div class="alert alert-danger alert-dismissible text-center" role="alert">
                <h4><strong>{{t('You are not authorized to view this page')}}.</strong></h4>
            </div>
        </div>
    @endif
@endsection