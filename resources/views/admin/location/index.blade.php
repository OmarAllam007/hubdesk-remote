@extends('layouts.app')

@section('header')
    <h4 class="pull-left">Locations</h4>
    <form action="" class="form-inline" method="get">
        <div class="input-group">
            <input class="form-control input-sm" type="search" name="search" id="searchTerm" placeholder="Search"
                   value="{{Request::query('search', '')}}">
            <span class="input-group-btn">
                    <button class="btn btn-default btn-sm"><i class="fa fa-search"></i></button>
                </span>
        </div>
        <a href="{{route('admin.location.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i></a>
        {{--<a title="Import from active directory" href="#ImportModal" data-toggle="modal" class="btn btn-sm btn-primary"><i class="fa fa-download"></i></a>--}}
    </form>
@stop


@section('body')
    <div class="flex w-full">
        <div class="w-1/4">
            @include('admin.partials._sidebar')
        </div>

        <section class="w-3/4">
            @if ($locations->total())
                <table class="listing-table">
                    <thead>
                    <tr>
                        <th>{{t('Name')}}</th>
                        <th>{{t('City')}}</th>
                        <th>{{t('Actions')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($locations as $location)
                        <tr>
                            <td class="col-md-5"><a
                                        href="{{route('admin.location.edit', $location)}}">{{$location->name}}</a></td>
                            <td class="col-md-3">{{$location->city->name ?? 'Not Assigned'}}</td>
                            <td class="col-md-3">
                                <a class="btn btn-sm btn-primary" href="{{route('admin.location.edit', $location)}}"><i
                                            class="fa fa-edit"></i> Edit</a>
                                <form action="{{route('admin.location.destroy', $location)}}" method="post"
                                      class="inline-block">
                                    {{csrf_field()}} {{method_field('delete')}}
                                    <button class="btn btn-sm btn-warning"><i class="fa fa-trash-o"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                @include('partials._pagination', ['items' => $locations])
            @else
                <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> <strong>No locations
                        found</strong></div>
            @endif
        </section>
@stop