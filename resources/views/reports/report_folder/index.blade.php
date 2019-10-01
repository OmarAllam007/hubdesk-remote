@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Folders')}}</h4>
    <a href="{{route('folder.create')}}" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus"></i></a>
@stop

@section('body')
    <section class="col-sm-9">
    @if ($folders->count())
        <table class="listing-table">
            <thead>
            <tr>
                <th>{{t('Name')}}</th>
                <th>{{t('Actions')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($folders as $folder)
                <tr>
                    <td class="col-md-9"><a href="{{route('folder.edit', compact('folder'))}}">{{$folder->name}}</a></td>
                    <td class="col-md-3">
                        <form action="{{route('folder.destroy', compact('folder'))}}" method="post">
                            {{csrf_field()}} {{method_field('delete')}}
                            <a class="btn btn-sm btn-primary" href="{{route('admin.question.edit',  compact('folder'))}}"><i class="fa fa-edit"></i> {{t('Edit')}}</a>
                            <button class="btn btn-sm btn-warning"><i class="fa fa-trash-o"></i> {{t('Delete')}}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    @else
        <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> <strong>{{t('No Folders found')}}</strong></div>
    @endif
    </section>
@stop
