@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Business rule')}}</h4>
    <form action="" class="form-inline" method="get">
        <div class="input-group">
            <input class="form-control input-sm" type="search" name="search" id="searchTerm" placeholder="Search"
                   value="{{Request::query('search', '')}}">
            <span class="input-group-btn">
                    <button class="btn btn-default btn-sm"><i class="fa fa-search"></i></button>
                </span>
        </div>
        <a href="{{ route('admin.business-rule.create') }} " class="btn btn-sm btn-primary pull-right"><i
                    class="fa fa-plus"></i></a>
        {{--<a title="Import from active directory" href="#ImportModal" data-toggle="modal" class="btn btn-sm btn-primary"><i class="fa fa-download"></i></a>--}}
    </form>
@stop


@section('body')
    <div class="flex w-full">
        <div class="w-1/4">
            @include('admin.partials._sidebar')
        </div>

        <section class="w-3/4">
            @if ($businessRules->total())
                <table class="listing-table">
                    <thead>
                    <tr>
                        <th>{{t('Name')}}</th>
                        <th>{{t('Actions')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($businessRules as $business_rule)
                        <tr>
                            <td class="col-md-5"><a
                                        href="{{ route('admin.business-rule.edit', $business_rule) }}">{{ $business_rule->name }}</a>
                            </td>
                            <td class="col-md-3">
                                <form action="{{ route('admin.business-rule.destroy', $business_rule) }}" method="post">
                                    {{csrf_field()}} {{method_field('delete')}}
                                    <a class="btn btn-sm btn-primary"
                                       href="{{ route('admin.business-rule.edit', $business_rule) }} "><i
                                                class="fa fa-edit"></i> Edit</a>
                                    <button class="btn btn-sm btn-warning"><i class="fa fa-trash-o"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                @include('partials._pagination', ['items' => $businessRules])
            @else
                <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> <strong>No business rule
                        found</strong></div>
        @endif
    </div>
@stop
