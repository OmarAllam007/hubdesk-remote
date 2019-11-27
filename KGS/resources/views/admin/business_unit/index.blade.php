@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Business Unit')}}</h4>
    <form action="" class="form-inline" method="get">
        <div class="input-group">
            <input class="form-control input-sm" type="search" name="search" id="searchTerm" placeholder="Search"
                   value="{{Request::query('search', '')}}">
            <span class="input-group-btn">
                    <button class="btn btn-default btn-sm"><i class="fa fa-search"></i></button>
                </span>
        </div>
        {{--        <a href="{{route('admin.category.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i></a>--}}
        {{--<a title="Import from active directory" href="#ImportModal" data-toggle="modal" class="btn btn-sm btn-primary"><i class="fa fa-download"></i></a>--}}
    </form>
@stop

@section('sidebar')
    @include('kgs::admin.partials._sidebar')
@stop

@section('body')
    <section class="col-sm-9">
        <table class="listing-table">
            <thead>
            <tr>
                <th>{{t('Business Units')}}</th>
                <th>{{t('Actions')}}</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="col-md-9">
                    @foreach($business_units as $business_unit)
                       <h3 style="display: inline-block">
                           <span class="label label-default">
                            {{$business_unit->business_unit->name}}</span>
                       </h3>
                    @endforeach

                </td>
                <td class="col-md-3">
                    <a class="btn btn-lg btn-primary" href="{{route('kgs.admin.business_unit.edit')}}"><i
                                class="fa fa-edit"></i> {{t('Edit')}}</a>
                </td>
            </tr>
            </tbody>
        </table>

    </section>
@stop
