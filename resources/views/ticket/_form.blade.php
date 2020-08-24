@php
    /** @var \App\Sla $sla */
use App\BusinessUnit;use App\ServiceLimit;$ticketObj = new \App\Ticket();
$sla = $ticketObj->getSla($category,$subcategory ?? null ,$item ?? null,$subItem ?? '');


/** @var BusinessUnit $requester_bu */
$requester_bu = auth()->user()->business_unit;
$exceedNoOfTickets = $requester_bu->isExceedNoOfLimitedTickets($category,$subcategory ?? null ,$item ?? null ,$subItem ?? null);

@endphp

@if($sla)
    <p style="font-size: 14pt;background-color: rgb(24, 79, 126); border-radius: 10px;text-align: center;padding: 10px;color: #fff;box-shadow: 2px 5px 2px lightgray">
        {{t('Your Request will Delivered within')}} {{$sla->due_days}} {{t('Days')}} {{$sla->due_hours}} {{t('Hours')}} {{$sla->due_minutes}} {{t('Minutes')}} {{t('(from the last approval)')}}
    </p>
@endif

@if($exceedNoOfTickets)
    <p style="font-size: 14pt;background-color: rgb(126,65,59); border-radius: 10px;text-align: center;padding: 10px;color: #fff;box-shadow: 2px 5px 2px lightgray">
        {{t('The number of allowed requests per month is exceeded')}}
    </p>
@endif



{{ csrf_field() }}
<div id="TicketForm">
    <div class="row">
        <div class="col-md-6">
            @if($errors->count())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            @if (!isset($ticket) && Auth::user()->isSupport())
                <div class="form-group form-group-sm {{$errors->has('requester_id')? 'has-error' : ''}}">
                    {{ Form::label('requester_id', t('Requester'), ['class' => 'control-label']) }}
                    <select name="requester_id" id="requester_id" class="form-control select2">
                        <option value="{{Auth::user()->id}}">{{t('Create for me')}}</option>
                        @foreach(App\User::orderBy('name')->where('employee_id','<>',0)->get() as $requester)
                            <option value="{{$requester->id}}"> {{$requester->employee_id }}
                                - {{$requester->name}}</option>
                        @endforeach
                    </select>
                    <br>
                    <div v-show="loading">
                        <i class="fa fa-2x fa-spinner fa-spin"></i>
                    </div>

                    <div v-show="!loading">
                            <span style="padding-right: 10px" v-if="requester.business_unit_name">
                            <small><strong>{{t('Business Unit')}}</strong></small> : <small
                                        v-text="requester.business_unit_name"> </small>
                        </span>

                        <span style="padding-right: 10px" v-if="requester.department_name">
                            <small><strong>{{t('Department')}}</strong></small> : <small
                                    v-text="requester.department_name"> </small>
                        </span>

                        <span style="padding-right: 10px" v-if="requester.job">
                            <small><strong>{{t('Position')}}</strong></small> : <small
                                    v-text="requester.job"> </small>
                        </span>

                        <span style="padding-right: 10px" v-if="requester.email">
                            <small><strong>{{t('Email')}}</strong></small> : <small v-text="requester.email"> </small>
                        </span>
                        @if(env('BALANCE_SERVICES') && in_array($category->id , explode(',',env('BALANCE_SERVICES'))))
                            <span style="padding-right: 10px"
                                  v-if="requester.extra_fields && requester.extra_fields.leave_balance">
                            <small><strong>{{t('Leave Balance')}}</strong></small> : <small
                                        v-text="requester.extra_fields.leave_balance"></small> <strong></strong>
                        </span>
                        @endif
                    </div>
                    {{--                    {{ Form::select('requester_id', App\User::requesterList()->prepend('Create for me', ''), null, ['class' => 'form-control select2']) }}--}}
                    {!! $errors->first('requester_id', '<div class="error-message">:message</div>') !!}


                </div>



            @endif
            <br>

            @php
                $subject = (auth()->user()->employee_id ? auth()->user()->employee_id.' - ' : '').
                 $category->name.(isset($subcategory->name) ? '  -  '.  $subcategory->name:'').(isset($item->name) ? '  -  '.  $item->name:'').(isset($subItem->name) ? '  -  '.  $subItem->name:'');
            @endphp
            <div class="form-group form-group-sm {{$errors->has('subject')? 'has-error' : ''}}">
                {{ Form::label('subject', t('Subject'), ['class' => 'control-label']) }}
                {{ Form::text('subject', $subject , ['class' => 'form-control']) }}
                @if ($errors->has('subject'))
                    <div class="error-message">{{$errors->first('subject')}}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="row">

        <div id="CustomFields">
            @include('custom-fields.render')
        </div>
    </div>

    <div class="row">
        <div class="col-sm-7">
            <div class="form-group form-group-sm {{$errors->has('description')? 'has-error' : ''}}">
                {{ Form::label('description', t('Description'), ['class' => 'control-label']) }}<strong
                        class="text-danger">*</strong>
                {{ Form::textarea('description', null, ['class' => 'form-control richeditor']) }}

                @if ($errors->has('description'))
                    <div class="error-message">{{$errors->first('description')}}</div>
                @endif
            </div>
        </div>
        <div class="col-sm-5">
            {{Form::hidden('category_id',$category->id)}}
            {{Form::hidden('subcategory_id',$subcategory->id ?? null)}}
            {{Form::hidden('item_id',$item->id ?? null)}}
            {{Form::hidden('subitem_id',$subItem->id ?? null)}}
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 {{$errors->has('priority_id')? 'has-error' : ''}}">
            {{ Form::label('priority_id', t('Priority'), ['class' => 'control-label']) }} <strong
                    class="text-danger">*</strong>
            <select name="priority_id" id="priority_id" class="form-control">
                <option value="">{{t('Select Priority')}}</option>
                @foreach(App\Priority::all() as $priority)
                    <option value="{{$priority->id}}"> {{ t($priority->name) }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <br>
    @if($category->notes || (isset($subcategory) && $subcategory->notes) || (isset($item) && $item->notes) )
        <fieldset>
            <label>{{t('Notes')}}</label>
            <p>
                {!! t($category->notes ?? '')  !!}
            </p>
            <p>
                {!! t($subcategory->notes ?? '')  !!}
            </p>
            <p>
                {!! t($item->notes ?? '')  !!}
            </p>
        </fieldset>
        <br>
    @endif
    <div class="row">
        <div class="col-md-6">
            <strong>{{t('Attachments')}}</strong>
            <attachments limit="5"></attachments>
        </div>
    </div>

    <hr class="form-divider">

</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <button class="btn btn-success"><i class="fa fa-check"></i> {{t('Submit')}}</button>
        </div>
    </div>
</div>

