@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('businessUnits')}}</h4>
    <form action="" class="form-inline" method="get">
        <div class="input-group">
            <input class="form-control input-sm" type="search" name="search" id="searchTerm" placeholder="Search"
                   value="{{Request::query('search', '')}}">
            <span class="input-group-btn">
            <button class="btn btn-default btn-sm"><i class="fa fa-search"></i></button>
        </span>
        </div>
        {{--<a title="Import from active directory" href="#ImportModal" data-toggle="modal" class="btn btn-sm btn-primary"><i class="fa fa-download"></i></a>--}}
    </form>
@stop


@section('body')

    <section class="col-sm-12">
            <div class="container">
                <div class=form-group></div>
                <h3 class=text-center>{{t('Business Unit') }}</h3>
                <hr/>

            </div>

            {{ Form::open(['route' => 'ticket.store', 'files' => true, 'class' => 'col-sm-12']) }}

            @include('ticket._form')
                    <p><a href="{{route('business-unit.show', $business_unit)}}"
                          class="btn btn-outlined btn-block btn-primary">{{$business_unit->name}}</a></p>

                <hr/>

            {{ Form::open(['route' => 'ticket.store', 'files' => true, 'class' => 'col-sm-12']) }}
            @include('ticket._form')
            {{ Form::close() }}
    </section>
@stop


