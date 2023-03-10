@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Edit Ticket')}}</h4>

    <form action="{{ route('ticket.destroy', $ticket)}}" class="pull-right" method="post">
        {{csrf_field()}} {{method_field('delete')}}
        <a href="{{ route('ticket.index')}}" class="btn btn-sm btn-default"><i class="fa fa-chevron-left"></i></a>
        <button class="btn btn-sm btn-warning" type="submit"><i class="fa fa-trash-o"></i></button>
    </form>
@stop

@section('body')
    {{ Form::model($ticket, ['route' => ['ticket.update', $ticket], 'class' => 'col-sm-12']) }}

        {{ method_field('patch') }}

    @include('business-documents.wizard.ticket._form')

    {{ Form::close() }}
@stop
