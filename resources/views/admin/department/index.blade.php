@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Departments')}}</h4>
    <form action="" class="form-inline" method="get">
        <div class="input-group">
            <input class="form-control input-sm" type="search" name="search" id="searchTerm" placeholder="Search"
                   value="{{Request::query('search', '')}}">
            <span class="input-group-btn">
                    <button class="btn btn-default btn-sm"><i class="fa fa-search"></i></button>
                </span>
        </div>
        <a href="{{route('admin.department.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i></a>

        {{--<a title="Import from active directory" href="#ImportModal" data-toggle="modal" class="btn btn-sm btn-primary"><i class="fa fa-download"></i></a>--}}
    </form>
@stop

@section('sidebar')
    @include('admin.partials._sidebar')
@stop
@section('body')
    <section class="col-sm-9">
    @if ($departments->total())
            <table class="listing-table">
                <thead>
                <tr>
                    <th>{{t('Name')}}</th>
                    <th>{{t('Business Unit')}}</th>
                    <th>{{t('Actions')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($departments as $department)
                    <tr>
                        <td class="col-md-5"><a href="{{route('admin.department.edit', $department)}}">{{$department->name}}</a></td>
                        <td class="col-md-4">{{$department->business_unit->name}}</td>
                        <td class="col-md-3">
                            <a class="btn btn-sm btn-primary" href="{{route('admin.department.edit', $department)}}"><i class="fa fa-edit"></i> Edit</a>
                            <form action="{{route('admin.department.destroy', $department)}}" method="post" class="inline-block">
                                {{csrf_field()}} {{method_field('delete')}}
                                <button class="btn btn-sm btn-warning"><i class="fa fa-trash-o"></i> Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        
            @include('partials._pagination', ['items' => $departments])
        @else
            <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> <strong>No departments found</strong></div>
        @endif
        </section>
@stop