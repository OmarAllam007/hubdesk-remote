@extends('layouts.app')

@section('header')
    <h4 class="pull-left">Branches</h4>
    <form action="" class="form-inline" method="get">
        <div class="input-group">
            <input class="form-control input-sm" type="search" name="search" id="searchTerm" placeholder="Search"
                   value="{{Request::query('search', '')}}">
            <span class="input-group-btn">
                    <button class="btn btn-default btn-sm"><i class="fa fa-search"></i></button>
                </span>
        </div>
        <a href="{{route('admin.branch.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i></a>
        {{--<a title="Import from active directory" href="#ImportModal" data-toggle="modal" class="btn btn-sm btn-primary"><i class="fa fa-download"></i></a>--}}
    </form>
@stop

@section('sidebar')
    @include('admin.partials._sidebar')
@stop

@section('body')
    <div class="col-sm-9">
        @if ($branches->total())
            <table class="listing-table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Business Unit</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($branches as $branch)
                    <tr>
                        <td class="col-md-3"><a href="{{route('admin.branch.edit', $branch)}}">{{$branch->name}}</a></td>
                        <td class="col-md-3">{{$branch->business_unit->name}}</td>
                        <td class="col-md-3">{{$branch->location->name}}</td>
                        <td class="col-md-3">
                            <a class="btn btn-sm btn-primary" href="{{route('admin.branch.edit', $branch)}}"><i class="fa fa-edit"></i> Edit</a>
                            <form action="{{route('admin.branch.destroy', $branch)}}" method="post" class="inline-block">
                                {{csrf_field()}} {{method_field('delete')}}
                                <button class="btn btn-sm btn-warning"><i class="fa fa-trash-o"></i> Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            @include('partials._pagination', ['items' => $branches])
        @else
            <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> <strong>No branches found</strong></div>
        @endif
    </div>
@stop