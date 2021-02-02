@extends('layouts.app')

@section('stylesheets')
    <style>
        #wrapper {
            background: #eaeaea;
        }
    </style>
    @if(isset($business_unit))
        <style>
            body {
                background: #f9f9f9 url(../images/white_texture.png) repeat top left;
                background-image: url({{url('/storage'.$business_unit->business_unit_bgd ?? '')}});
                background-position: top center;
                background-repeat: no-repeat;
                background-attachment: fixed;
                background-size: cover;
                color: #333;
                /*font-family: 'Esphimere Regular', Arial, sans-serif;*/
                font-size: 13px;
            }
        </style>
    @endif
@endsection
@section('body')

    @if(can('show_survey',$ticket))
        @if($survey->is_submitted)
            <div class="flex pt-10 w-full justify-center">
                <div class="text-center p-5  w-1/2  break-words bg-white  border-t text-gray-800   mb-6 shadow-md  rounded-xl ">
                    <strong><i class="fa fa-check-circle text-green-700 fa-lg  "></i> {{t('Your survey information for this request has already been received for consideration')}}
                        .
                        <a class="text-blue-500 hover:text-blue-700  underline"
                           href="{{route('ticket.show',$ticket)}}">{{t('Return to the ticket')}}</a>
                    </strong>

                </div>
            </div>
        @else
            <form action="{{route('ticket.survey',[$ticket,$survey])}}" method="post">
                {{csrf_field()}}
                <div class="flex pt-10 w-full justify-center">
                    <div class="w-1/2 ">
                        <div class=" p-5  break-words bg-white  border-t-2  border-blue-600  text-gray-800   mb-6 shadow-md  rounded-xl ">
                            <h4><b>{{t('Welcome')}} {{$ticket->requester->name}}</b>,</h4>
                            <h4 class="pt-5 ">{{t('Survey sent for request')}} " {{$ticket->subject}} "</h4>
                            <div class="pt-5 flex justify-between ">
                                <div><b>{{t('Request ID')}}</b>: <a class="text-blue-700 underline" target="_blank" href="{{route('ticket.show',$ticket->id)}}">{{$ticket->id}}</a></div>
                                <div><b>{{t('Created On')}}</b>: {{$ticket->created_at ? $ticket->created_at->format('d-M-Y h:m a') : ''}} </div>
                                <div><b>{{t('Closed On')}} </b>: {{$ticket->close_date ? $ticket->close_date->format('d-M-Y h:m a') : ''}}</div>
                            </div>
                        </div>
                        <div class=" p-5 break-words mb-6 flex justify-center underline text-2xl ">
                            <p><b>{{t('Please help us to improve our service by participating in this brief survey')}}.</b></p>
                        </div>
                        <hr>
                        @if($ticket->category->survey->first())
                            @if($ticket->category->survey->first()->questions->count())
                                @foreach($ticket->category->survey->first()->questions as $key=>$question)
                                    <div class="pb-5 ">
                                        <h4><b>{{$key+1 }} - {{t($question->description)}}</b></h4>
                                    </div>

                                    <div class="text-center p-5 break-words bg-white  border-l-2   border-blue-700  text-gray-800   mb-6 shadow-md  rounded-xl ">
                                        <div class="flex flex-col ">
                                            @foreach($question->answers as $answer)
                                                <div class="radio hover:bg-gray-200  p-3 rounded-2xl ">
                                                    <label class=" flex justify-start w-full">
                                                        <input type="radio" name="questions[{{$question->id}}]"
                                                               id="optionsRadios1"
                                                               value="{{$answer->id}}">
                                                        {{t($answer->description)}}
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
                        <div class="pb-5">
                            <h4><b>{{t('Your suggestions will help us improve our service, kindly let us know if any')}}</b>
                            </h4>
                        </div>


                        <div class="form-group">
                        <textarea class="form-control border border-gray-400 rounded-xl " name="comment" rows="10"
                                  cols="100"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">{{t('Submit')}}</button>
                    </div>
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