@extends('layouts.app')

@section('header')
    <h4 class="pull-left">Divisions</h4>
    <form action="" class="form-inline" method="get">
        <div class="input-group">
            <input class="form-control input-sm" type="search" name="search" id="searchTerm" placeholder="Search"
                   value="{{Request::query('search', '')}}">
            <span class="input-group-btn">
                    <button class="btn btn-default btn-sm"><i class="fa fa-search"></i></button>
                </span>
        </div>
        <a href="{{route('admin.division.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i></a>
    </form>
@stop

@section('sidebar')
    @include('admin.partials._sidebar')
@stop

@section('body')
    <section class="col-sm-9">
    @if ($divisions->total())
        <table class="listing-table">
            <thead>
            <tr>
                <th>{{t('Name')}}</th>
                <th>{{t('Actions')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($divisions as $division)
                <tr>
                    <td class="col-md-6"><a href="{{route('admin.division.edit', $division)}}">{{$division->name}}</a></td>
                    <td class="col-md-3">
                        <a class="btn btn-sm btn-primary" href="{{route('admin.division.edit', $division)}}"><i
                                    class="fa fa-edit"></i> Edit</a>
                        <form action="{{route('admin.division.destroy', $division)}}" method="post" class="inline-block">
                            {{csrf_field()}} {{method_field('delete')}}
                            <button class="btn btn-sm btn-warning"><i class="fa fa-trash-o"></i> Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        @include('partials._pagination', ['items' => $divisions])
    @else
        <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> <strong>No divisions found</strong></div>
    @endif
    </section>
@stop