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
        <a href="{{route('admin.business-unit.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i></a>
        {{--<a title="Import from active directory" href="#ImportModal" data-toggle="modal" class="btn btn-sm btn-primary"><i class="fa fa-download"></i></a>--}}
    </form>
@stop


@section('body')

    <section class="col-sm-12">
    @if ($businessUnits->total())

    <div class="container">

           

            @foreach($businessUnits as $business_unit)
                <div class="alert alert-info">
                    <a  class="btn btn-xs btn-primary pull-right" href="{{route('business-unit.show', $business_unit)}}"><img src="http://localhost/hubdesk-KGS/public/images/arrow.png"></a>
        <strong>Business Unit:</strong> {{$business_unit->name}}
                      </div>
      
            @endforeach

    @else
        <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> <strong>{{t('No businessUnits found')}}</strong></div>
    @endif
    </section>
@stop


