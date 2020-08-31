@extends('layouts.app')

@section('header')
    <h4 class="pull-left">#{{$ticketApproval->ticket->id}} - {{$ticketApproval->ticket->subject}} - Approval</h4>
    <div class="heading-actions pull-right">
        <a href="{{route('ticket.show', $ticketApproval->ticket)}}" class="btn btn-sm btn-info"
           title="Back to ticket" target="_blank"><i class="fa fa-ticket"></i> {{t('Display Ticket')}}</a>
    </div>
@stop

@section('body')
    <section class="col-md-12">
        <section id="ticket">
            <h4>{{t('Request Description')}}</h4>
            <div class="well well-sm well-white">
                {!! $ticketApproval->ticket->description !!}
            </div>

            <h4>{{t('Approval Content')}}</h4>
            <div class="well well-sm well-white">
                {!! $ticketApproval->content !!}
            </div>
        </section>
    </section>

    <section class="ticket">
        <div class="col-md-6">
            <section id="action">
                {{Form::open(['route' => ['approval.update', $ticketApproval]])}}

                @if($ticketApproval->questions->count())
                    <h4>{{t('Questions')}}</h4>
                    @foreach($ticketApproval->questions as $key=>$question)
                        <p>{{$question->description}}</p>
                        <div class="row form-group">
                            <div class="col-md-3">
                                <label for="approve{{$key}}" class="radio-online">
                                    {{Form::radio('questions['.$question->id.']', \App\TicketApproval::APPROVED, 1, ['id' => 'approve'.$key])}}
                                    {{t('Approve')}}
                                    {{--                                 <i class="fa fa-thumbs-o-up"></i>--}}
                                </label>
                            </div>
                            <div class="col-md-3">
                                <label for="deny{{$key}}" class="radio-online">
                                    {{Form::radio('questions['.$question->id.']', \App\TicketApproval::DENIED, null, ['id' => 'deny'.$key])}}
                                    {{t('Deny')}}
                                    {{--                                <i class="fa fa-thumbs-o-down"></i>--}}
                                </label>
                            </div>
                        </div>
                    @endforeach
                @endif

                @if(!$ticketApproval->questions->count())
                    <h4>Action</h4>
                    <div class="row form-group {{$errors->has('status')? 'has-error' : ''}}">
                        <div class="col-md-3">
                            <label for="approve" class="radio-online">
                                {{Form::radio('status', \App\TicketApproval::APPROVED, null, ['id' => 'approve'])}}
                                {{t('Approve')}}
                                {{--                            <i class="fa fa-thumbs-o-up"></i>--}}
                            </label>
                        </div>
                        <div class="col-md-3">
                            <label for="deny" class="radio-online">
                                {{Form::radio('status', \App\TicketApproval::DENIED, null, ['id' => 'deny'])}}
                                {{t('Deny')}}
                                {{--                            <i class="fa fa-thumbs-o-down"></i>--}}
                            </label>
                        </div>
                        <div class="col-md-12">
                            @if($errors->has('status'))
                                <div class="error-message">{{$errors->first('status')}}</div>
                            @endif
                        </div>
                    </div>
                @endif
                <div class="form-group {{$errors->has('comment')? 'has-error' : ''}}">
                    {{Form::label('comment', 'Comment', ['class' => 'control-label'])}}
                    {{Form::textarea('comment', null, ['class' => 'form-control'])}}

                    @if($errors->has('comment'))
                        <div class="error-message">{{$errors->first('comment')}}</div>
                    @endif
                </div>
                <div class="form-group {{$errors->has('hidden_comment')? 'has-error' : ''}}">
                    <label for="hidden_comment" class="control-label">
                        <input type="checkbox" name="hidden_comment" class="form-check" id="hidden_comment">
                        {{t('mark as a private comment ?')}}
                    </label>


                    @if($errors->has('hidden_comment'))
                        <div class="error-message">{{$errors->first('hidden_comment')}}</div>
                    @endif
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-pencil"></i> Update</button>
                </div>

                {{Form::close()}}
            </section>
        </div>
        <div class="col-md-6">
            @include('ticket.partials._requester_details',['ticket'=>$ticketApproval->ticket])
            @include('ticket.partials._ticket_additional_fields',['ticket'=>$ticketApproval->ticket])
            @include('ticket.partials._ticket_replies',['ticket'=>$ticketApproval->ticket])
        </div>
    </section>
@stop