@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Group')}}</h4>
    <div class="heading-actions pull-right">
        <form action="" class="form-inline" method="get">
            <div class="input-group">
                <input class="form-control input-sm" type="search" name="search" id="searchTerm" placeholder="Search"
                       value="{{Request::query('search', '')}}">
                <span class="input-group-btn">
                    <button class="btn btn-default btn-sm"><i class="fa fa-search"></i></button>
                </span>
            </div>
            <a href="{{ route('admin.group.create') }} " class="btn btn-sm btn-primary"><i
                        class="fa fa-plus"></i></a>
            {{--<a title="Import from active directory" href="#ImportModal" data-toggle="modal" class="btn btn-sm btn-primary"><i class="fa fa-download"></i></a>--}}
        </form>
    </div>
@stop

@section('sidebar')
    @include('admin.partials._sidebar')
@stop

@section('body')
    <section class="col-sm-9">
        @if ($groups->total())
            <table class="listing-table">
                <thead>
                <tr>
                    <th>{{t('Name')}}</th>
                    <th>{{t('Is Active')}}</th>
                    <th>{{t('Actions')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($groups as $group)
                    <tr>
                        <td class="col-md-5"><a href="{{ route('admin.group.edit', $group) }}">{{ $group->name }}</a>
                        </td>
                        <td>{{$group->is_disabled ? 'No' : 'Yes'}}</td>
                        <td class="col-md-3">
                            <form action="{{ route('admin.group.destroy', $group) }}" method="post">
                                {{csrf_field()}} {{method_field('delete')}}
                                <a class="btn btn-sm btn-primary" href="{{ route('admin.group.edit', $group) }} "><i
                                            class="fa fa-edit"></i> Edit</a>
                                <button class="btn btn-sm btn-warning"><i class="fa fa-trash-o"></i> Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @include('partials._pagination', ['items' => $groups])
        @else
            <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> <strong>No group found</strong></div>
        @endif
    </section>
@stop
