@extends('layouts.app')

@section('header')
    @can('show',$ticket)
        <div class="display-flex ticket-meta">
            <div class="flex">
                <h4>#{{$ticket->id}} - {{$ticket->subject}}
                </h4>
                <h4>
                    <strong>{{t('By')}} :{{$ticket->requester->name}}</strong>
                </h4>
                @if($ticket->isDuplicated())
                    <h4>{{'Duplicated Request from'}}:
                        <a title="Show Original Request" href="{{route('ticket.show',$ticket->request_id)}}"
                           target="_blank">
                            #{{ $ticket->request_id }}
                        </a>
                    </h4>
                @endif

                @if (Auth::user()->isSupport())
                    @if($ticket->isTask())
                        <h4>{{t('Request')}}:
                            <a title="{{ t('Show Original Request') }}"
                               href="{{route('ticket.show',$ticket->request_id)}}"
                               target="_blank">
                                #{{ $ticket->request_id }}
                            </a>
                        </h4>
                    @endif
                    <div class="btn-toolbar">

                        @can('reassign',$ticket)
                            <button data-toggle="modal" data-target="#AssignForm" type="button"
                                    class="btn btn-sm btn-info btn-rounded btn-outlined" title="{{t('Re-assign')}}">
                                <i class="fa fa-mail-forward"></i> {{t('Re-assign')}}
                            </button>
                        @endcan
                        @can('forward',$ticket)
                            <button data-toggle="modal" data-target="#ForwardForm" type="button"
                                    class="btn btn-sm btn-primary btn-rounded btn-outlined" title="{{t('Forward')}}">
                                <i class="fa fa-arrow-circle-right"></i> {{t('Forward')}}
                            </button>
                        @endcan
                        @if(!$ticket->isTask())
                            <button data-toggle="modal" data-target="#DuplicateForm" type="button"
                                    class="btn btn-sm btn-primary btn-rounded btn-outlined" title="Duplicate">
                                <i class="fa fa-copy"></i> {{t('Duplicate')}}
                            </button>

                            <a href="{{route('ticket.print',$ticket)}}" target="_blank"
                               class="btn btn-sm btn-primary btn-rounded btn-outlined" title="Print">
                                <i class="fa fa-print"></i> {{t('Print')}}
                            </a>
                        @endif

                        @if($ticket->isTask())
                            @can('task_edit',$ticket)
                                <a href="{{route('tasks.edit',$ticket)}}"
                                   class="btn btn-sm btn-primary btn-rounded btn-outlined" title="Edit">
                                    <i class="fa fa-edit"></i> {{t('Edit')}}
                                </a>
                            @endcan
                        @endif

                        @can('send_to_finance',$ticket)
                            <button type="button" class="btn btn-primary btn-sm btn-rounded btn-outlined"
                                    data-toggle="modal" data-target="#FinanceForm" title="{{t('Send to Finance')}}">
                                <i class="fa fa-money"></i> {{t('Send to Finance')}}
                            </button>
                        @endif

                        @can('send_complaint',$ticket)
                            <button data-toggle="modal" data-target="#ComplaintForm" type="button"
                                    class="btn btn-sm btn-primary btn-rounded btn-outlined" title="{{t('Complaint')}}">
                                <i class="fa fa-comments"></i> {{t('Complaint')}}
                            </button>
                        @endcan
                    </div>
                @endif

            </div>


            <div class="card">
                <ul class="list-unstyled">
                    <li>
                        @if($ticket->overdue)
                            <i class="fa fa-flag text-danger" aria-hidden="true"
                               title="{{t('SLA violated')}}"></i>
                        @endif
                        <small><strong>{{t('Status')}}:</strong> {{t($ticket->status->name)}}</small>
                    </li>
                    <li>
                        <small><strong>{{t('Created at')}}:</strong> {{$ticket->created_at->format('d/m/Y H:i')}}
                        </small>
                    </li>
                    @if ($ticket->due_date)
                        <li>
                            <small><strong>{{t('Due Date')}} :</strong> {{$ticket->due_date->format('d/m/Y H:i')}}
                            </small>
                        </li>
                    @endif

                    @if($ticket->resolve_date)
                        <li>

                            <small><strong>{{t('Resolve Date')}}
                                    :</strong> {{$ticket->resolve_date->format('d/m/Y H:i')}}
                            </small>
                        </li>
                    @endif
                    @if($ticket->last_updated_approval)
                        <li>
                            <small>
                                <strong>{{t('Approval Status')}}:</strong>
                                <i class="fa fa-lg fa-{{$ticket->last_updated_approval->approval_icon}} text-{{$ticket->last_updated_approval->approval_color}}"
                                   aria-hidden="true"></i> {{\App\TicketApproval::$statuses[$ticket->last_updated_approval->status]}}
                            </small>
                        </li>
                    @endif

                    @if($ticket->user_survey && $ticket->user_survey->is_submitted)
                        <li>
                            <small>
                                <strong>{{t('Ticket Survey Score')}}
                                    : {{number_format($ticket->user_survey->total_score,2)}} </strong>

                            </small>
                        </li>
                    @endif

                </ul>
            </div>
        </div>
    @endcan
@endsection

@section('body')
    @php
        $users = \App\User::whereNotNull('email')->orderBy('name')->get();
    @endphp

    @if(can('show',$ticket))
        <section class="col-sm-12" id="ticketArea">
            <ul class="nav nav-tabs" role="tablist">
                <li class="active">
                    <a href="#main" role="tab" data-toggle="tab"><i
                                class="fa fa-ticket"></i>
                        @if(!$ticket->isTask())
                            {{t('Request')}}
                        @else
                            {{t('Task')}}
                        @endif

                    </a>
                </li>
                <li><a href="#conversation" role="tab" data-toggle="tab"><i
                                class="fa fa-comments-o"></i> {{t('Conversation')}}</a></li>
                <li><a href="#tasks" role="tab" data-toggle="tab"><i class="fa fa-tasks"></i> {{t('Tasks')}}</a></li>
                @if ($ticket->resolution || can('resolve', $ticket))
                    <li><a href="#resolution" role="tab" data-toggle="tab"><i
                                    class="fa fa-support"></i> {{t('Resolution')}}</a></li>
                @endif
                @if (($ticket->approvals->count() || Auth::user()->isSupport()))
                    <li><a href="#approvals" role="tab" data-toggle="tab"><i
                                    class="fa fa-check"></i> {{t('Approvals')}}</a></li>
                @endif


                @if(!$ticket->isTask())
                    <li><a href="#tasks" role="tab" data-toggle="tab"><i
                                    class="fa fa-tasks"></i> {{t('Tasks')}}</a></li>
                @endif

                <li><a href="#history" role="tab" data-toggle="tab"><i
                                class="fa fa-history"></i>

                        @if(!$ticket->isTask())
                            {{t('Ticket Log')}}
                        @else
                            {{t('Task Log')}}
                        @endif
                    </a></li>

                @if ($ticket->files->count())
                    <li><a href="#attachments" role="tab" data-toggle="tab"><i
                                    class="fa fa-file-o"></i> {{t('Attachments')}}</a></li>
                @endif

                @if($ticket->user_survey && $ticket->user_survey->is_submitted)
                    <li><a href="#survey" role="tab" data-toggle="tab"><i
                                    class="fa fa-files-o"></i> {{t('Survey')}}</a></li>
                @endif
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="main">
                    @include('ticket.tabs._main')
                </div>

                <div role="tabpanel" class="tab-pane" id="resolution">
                    @include('ticket.tabs._resolution')
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

                <div role="tabpanel" class="tab-pane " id="approvals">
                    @include('ticket.tabs._approvals')
                </div>


                @if ($ticket->files->count())
                    <div role="tabpanel" class="tab-pane" id="attachments">
                        @include('ticket.tabs._attachment')
                    </div>
                @endif


                <div role="tabpanel" class="tab-pane" id="tasks">
                    @include('ticket.tabs.tasks')
                </div>

                @if($ticket->user_survey && $ticket->user_survey->survey_answers->count())
                    <div role="tabpanel" class="tab-pane" id="survey">
                        @include('ticket.tabs._survey')
                    </div>
                @endif


                @include('ticket._assign_modal')
                @include('ticket.partials._send_to_finance')
                @include('ticket._forward_modal')
                @include('ticket._notes_modal')
                @include('ticket._remove_note_modal')
                @include('ticket._duplicate_modal')
                @include('ticket._complaint_modal')
            </div>
        </section>
    @else

        <div class="container-fluid">
            <div class="alert alert-warning text-center"><i class="fa fa-exclamation-circle"></i>
                <strong>
                    {{ t('You are not authorized to display this request') }}
                </strong>
            </div>
        </div>

    @endif
@endsection

@section('javascript')
        <script>
            var category = '{{Form::getValueAttribute('category_id') ?? $ticket->category_id}}';
            var subcategory = '{{Form::getValueAttribute('subcategory_id') ?? $ticket->subcategory_id}}';
            var item = '{{Form::getValueAttribute('item_id') ?? $ticket->item_id}}';
            var subitem = '{{Form::getValueAttribute('subitem_id') ?? $ticket->subitem_id}}';
            var group = '{{Form::getValueAttribute('group_id') ?? $ticket->group_id}}';
            var technician_id = '{{Form::getValueAttribute('technician_id') ?? $ticket->technician_id}}';
        </script>
    <script src="{{asset('/js/ticket-form.js')}}?version={{time()}}"></script>


@append