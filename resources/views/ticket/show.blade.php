@extends('layouts.app')

@section('header')

<div class="display-flex ticket-meta">
    <div class="flex">
        <h4>#{{$ticket->id}} - {{$ticket->subject}}</h4>
        @if (Auth::user()->isSupport())
            <div class="btn-toolbar">
                <button data-toggle="modal" data-target="#AssignForm" type="button" class="btn btn-sm btn-default" title="Re-assign">
                    <i class="fa fa-mail-forward"></i> {{t('Re-assign')}}
                </button>
                <a href="{{route('ticket.duplicate', $ticket)}}" class="btn btn-sm btn-default"><i class="fa fa-clone"></i>
                {{t('Duplicate')}}</a>
            </div>
        @endif
    </div>

    <div class="card">
        <ul class="list-unstyled">
            <li>
                <small><strong>{{t('Status')}} :</strong> {{$ticket->status->name}}</small>
            </li>
            @if ($ticket->due_date)
            <li>
                <small><strong>{{t('Due Date')}} :</strong> {{$ticket->due_date->format('d/m/Y H:i')}}</small>
            </li>
            @endif

            @if($ticket->resolve_date)
            <li>
                <small><strong>{{t('Resolve Date')}}:</strong> {{$ticket->resolve_date->format('d/m/Y H:i')}}</small>
            </li>
            @endif
        </ul>
    </div>
@endsection

@section('body')
    <section class="col-sm-12" id="ticketArea">
        <ul class="nav nav-tabs" role="tablist">
            <li class="active"><a href="#main" role="tab" data-toggle="tab"><i class="fa fa-ticket"></i> {{t('Request')}}</a></li>
            <li><a href="#conversation" role="tab" data-toggle="tab"><i class="fa fa-comments-o"></i> {{t('Conversation')}}</a></li>
            {{--<li><a href="#tasks" role="tab" data-toggle="tab"><i class="fa fa-tasks"></i> {{t('Tasks')}}</a></li>--}}
            @if ($ticket->resolution || can('resolve', $ticket))
                <li><a href="#resolution" role="tab" data-toggle="tab"><i class="fa fa-support"></i> {{t('Resolution')}}</a></li>
            @endif

            @if ($ticket->approvals->count() || Auth::user()->isSupport())
                <li><a href="#approvals" role="tab" data-toggle="tab"><i class="fa fa-check"></i> {{t('Approvals')}}</a></li>
            @endif

            <li><a href="#history" role="tab" data-toggle="tab"><i class="fa fa-history"></i> {{t('Ticket Log')}}</a></li>

            @if ($ticket->files->count())
                <li><a href="#attachments" role="tab" data-toggle="tab"><i class="fa fa-file-o"></i> {{t('Attachments')}}</a></li>
            @endif
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="main">
                @include('ticket.tabs._main')

            </div>

            <div role="tabpanel" class="tab-pane" id="conversation">
                @include('ticket.tabs._conversation')

            </div>

            <div role="tabpanel" class="tab-pane" id="resolution">
                @include('ticket.tabs._resolution')

            </div>

            <div role="tabpanel" class="tab-pane" id="history">
                @include('ticket.tabs._history')
            </div>

            <div role="tabpanel" class="tab-pane" id="approvals">
                @include('ticket.tabs._approvals')
            </div>

            @if ($ticket->files->count())
                <div role="tabpanel" class="tab-pane" id="attachments">
                    @include('ticket.tabs._attachment')
                </div>
            @endif

            {{--<div role="tabpanel" class="tab-pane" id="tasks">
                @include('ticket.tabs._tasks')
            </div>--}}

            @include('ticket._assign_modal')
        </div>
    </section>
@endsection

@section('javascript')
    <script src="{{asset('/js/ticket.js')}}"></script>
    <script src="{{asset('/js/tinymce/tinymce.min.js')}}"></script>

@endsection