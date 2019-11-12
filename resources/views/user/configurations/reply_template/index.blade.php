@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Reply Templates')}}</h4>
    <form action="" class="form-inline" method="get">

        <a href="{{route('reply_template.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i></a>
        {{--<a title="Import from active directory" href="#ImportModal" data-toggle="modal" class="btn btn-sm btn-primary"><i class="fa fa-download"></i></a>--}}
    </form>
@stop

@section('sidebar')
    @include('user.configurations._sidebar')
@stop

@section('body')
    <div class="col-sm-9">
        @if ($templates->count())
            <table class="listing-table">
                <thead>
                <tr>
                    <th>{{t('Title')}}</th>
                    <th>{{t('Description')}}</th>
                    <th>{{t('Actions')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($templates as $template)
                    <tr>
                        <td class="col-md-3">{{$template->title}}</td>
                        <td class="col-md-3">{{$template->description}}</td>
                        <td class="col-md-3">
                            <a class="btn btn-sm btn-primary" href="{{route('reply_template.edit', $template)}}"><i class="fa fa-edit"></i> Edit</a>
                            <form action="{{route('reply_template.destroy', $template)}}" method="post" class="inline-block">
                                {{csrf_field()}} {{method_field('delete')}}
                                <button class="btn btn-sm btn-warning"><i class="fa fa-trash-o"></i> Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            @include('partials._pagination', ['items' => $templates])
        @else
            <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> <strong>No Reply Templates found</strong></div>
        @endif
    </div>
@stop