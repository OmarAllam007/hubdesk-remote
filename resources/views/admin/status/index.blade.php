@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Status')}}</h4>
    <a href="{{ route('admin.status.create') }} " class="btn btn-sm btn-primary pull-right"><i
                class="fa fa-plus"></i></a>
@stop



@section('body')
    <div class="flex w-full">
        <div class="w-1/4">
            @include('admin.partials._sidebar')
        </div>

        <section class="w-3/4">
            @if ($statuses->total())
                <table class="listing-table">
                    <thead>
                    <tr>
                        <th>{{t('Name')}}</th>
                        <th>{{t('Actions')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($statuses as $status)
                        <tr>
                            <td class="col-md-5"><a
                                        href="{{ route('admin.status.edit', $status) }}">{{ $status->name }}</a></td>
                            <td class="col-md-3">
                                <form action="{{ route('admin.status.destroy', $status) }}" method="post">
                                    {{csrf_field()}} {{method_field('delete')}}
                                    <a class="btn btn-sm btn-primary" href="{{ route('admin.status.edit', $status) }} "><i
                                                class="fa fa-edit"></i> Edit</a>
                                    <button class="btn btn-sm btn-warning"><i class="fa fa-trash-o"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                @include('partials._pagination', ['items' => $statuses])
            @else
                <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> <strong>No status found</strong>
                </div>
            @endif
        </section>
@stop
