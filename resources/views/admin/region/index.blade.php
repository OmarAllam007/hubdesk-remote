@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Regions')}}</h4>
    <div class="heading-actions pull-right">
        <form action="" class="form-inline" method="get">
            <div class="input-group">
                <input class="form-control input-sm" type="search" name="search" id="searchTerm" placeholder="Search"
                       value="{{Request::query('search', '')}}">
                <span class="input-group-btn">
                    <button class="btn btn-default btn-sm"><i class="fa fa-search"></i></button>
                </span>
            </div>
            <a href="{{route('admin.region.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i></a>

            {{--<a title="Import from active directory" href="#ImportModal" data-toggle="modal" class="btn btn-sm btn-primary"><i class="fa fa-download"></i></a>--}}
        </form>
    </div>
@stop

@section('sidebar')
    @include('admin.partials._sidebar')
@stop

@section('body')
    <section class="col-sm-9">
    @if ($regions->total())
        <table class="listing-table">
            <thead>
            <tr>
                <th>{{t('Name')}}</th>
                <th>{{t('Actions')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($regions as $region)
                <tr>
                    <td class="col-md-8"><a href="{{route('admin.region.edit', $region)}}">{{$region->name}}</a></td>
                    <td class="col-md-4">
                        <a class="btn btn-sm btn-primary" href="{{route('admin.region.edit', $region)}}"><i
                                    class="fa fa-edit"></i> Edit</a>
                        <form action="{{route('admin.region.destroy', $region)}}" method="post" class="inline-block">
                            {{csrf_field()}} {{method_field('delete')}}
                            <button class="btn btn-sm btn-warning"><i class="fa fa-trash-o"></i> Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        @include('partials._pagination', ['items' => $regions])
    @else
        <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> <strong>No regions found</strong></div>
    @endif
    </section>
@stop