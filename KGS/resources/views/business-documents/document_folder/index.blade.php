@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Folders')}}</h4>
    {{--<form action="" class="form-inline" method="get">--}}
        {{--<div class="input-group">--}}
            {{--<input class="form-control input-sm" type="search" name="search" id="searchTerm" placeholder="Search"--}}
                   {{--value="{{Request::query('search', '')}}">--}}
            {{--<span class="input-group-btn">--}}
                    {{--<button class="btn btn-default btn-sm"><i class="fa fa-search"></i></button>--}}
                {{--</span>--}}
        {{--</div>--}}
        <a href="{{route('kgs.business_documents_folder.create',compact('business_unit'))}}" class="btn btn-sm btn-outlined btn-primary"><i class="fa fa-plus"></i>  {{t('Create')}}</a>
{{--    <a class="btn  btn-default" href="{{route('kgs.document.select_category',compact('business_unit'))}}"><i class="fa fa-1x fa-arrow-right" ></i></a>--}}

    {{--</form>--}}
@stop

{{--@section('sidebar')--}}
    {{--@include('admin.partials._sidebar')--}}
{{--@stop--}}

@section('body')
    <section class="col-sm-12">
        @if ($document_folders->total())
            <table class="listing-table">
                <thead>
                <tr>
                    <th>{{t('Name')}}</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($document_folders as $folder)
                    <tr>
{{--                        <td ><a href="{{route('kgs.document.edit', compact('business_unit','document'))}}">{{$folder->name}}</a></td>--}}
{{--                        <td class="col-md-3">--}}
{{--                            <form action="{{route('kgs.document.destroy', compact('business_unit','folder'))}}" method="post">--}}
{{--                                {{csrf_field()}} {{method_field('delete')}}--}}
{{--                                <a class="btn btn-sm btn-primary" href="{{route('kgs.document.edit', compact('business_unit','folder'))}}"><i class="fa fa-edit"></i> Edit</a>--}}
{{--                                <button class="btn btn-sm btn-warning"><i class="fa fa-trash-o"></i> Delete</button>--}}
{{--                            </form>--}}
{{--                        </td>--}}
                    </tr>
                @endforeach
                </tbody>
            </table>

{{--            @include('partials._pagination', ['items' => $documents])--}}
        @else
            <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> <strong>{{t('No Folders found')}}</strong></div>
        @endif
    </section>
@stop
