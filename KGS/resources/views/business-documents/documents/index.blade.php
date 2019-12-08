@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Documents')}}</h4>
    {{--<form action="" class="form-inline" method="get">--}}
    {{--<div class="input-group">--}}
    {{--<input class="form-control input-sm" type="search" name="search" id="searchTerm" placeholder="Search"--}}
    {{--value="{{Request::query('search', '')}}">--}}
    {{--<span class="input-group-btn">--}}
    {{--<button class="btn btn-default btn-sm"><i class="fa fa-search"></i></button>--}}
    {{--</span>--}}
    {{--</div>--}}
    <a href="{{route('kgs.document.create',compact('folder'))}}" class="btn btn-sm btn-outlined btn-primary"><i
                class="fa fa-plus"></i> {{t('Create')}}</a>
    <a class="btn  btn-default"
       href="{{route('kgs.business_documents_folder.index',['business_unit'=>$folder->business_unit])}}">{{$folder->name}} <i
                class="fa fa-1x fa-arrow-right"></i> </a>

    {{--</form>--}}
@stop

{{--@section('sidebar')--}}
{{--@include('admin.partials._sidebar')--}}
{{--@stop--}}

@section('body')
    <section class="col-sm-12">
        @if ($documents->total())
            <table class="listing-table">
                <thead>
                <tr>
                    <th>{{t('Name')}}</th>
                    <th>{{t('Start Date')}}</th>
                    <th>{{t('End Date')}}</th>
                    <th>{{t('Document')}}</th>
                    <th>{{t('Actions')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($documents as $document)
                    <tr>
                        <td>
                            <a href="{{route('kgs.document.edit', compact('folder','document'))}}">{{$document->name}}</a>
                        </td>
                        <td>{{$document->start_date ? $document->start_date->format('Y-m-d') : ''}}</td>
                        <td>{{$document->end_date ? $document->end_date->format('Y-m-d') : ''}}</td>
                        <td><a href="{{route('kgs.business_document.download',['attachment'=>$document])}}" target="_blank">{{basename($document->path) ?? ''}}</a></td>
                        <td class="col-md-3">
                            <form action="{{route('kgs.document.destroy', compact('folder','document'))}}"
                                  method="post">
                                {{csrf_field()}} {{method_field('delete')}}
                                <a class="btn btn-sm btn-primary"
                                   href="{{route('kgs.document.edit', compact('folder','document'))}}"><i
                                            class="fa fa-edit"></i> {{t('Edit')}}</a>

{{--                                <a class="btn btn-sm btn-default"--}}
{{--                                   href="{{route('kgs.document.edit', compact('folder','document'))}}"><i--}}
{{--                                            class="fa fa-history"></i> {{t('History')}}</a>--}}

                                <button class="btn btn-sm btn-warning"><i class="fa fa-trash-o"></i> {{t('Delete')}}</button>

                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {{--            @include('partials._pagination', ['items' => $documents])--}}
        @else
            <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> <strong>{{t('No Documents found')}}</strong>
            </div>
        @endif
    </section>
@stop
