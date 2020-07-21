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
       href="{{route('kgs.business_documents_folder.index',['business_unit'=> $folder->business_unit])}}">{{$folder->name}}
        <i
                class="fa fa-1x fa-arrow-right"></i> </a>

    {{--</form>--}}
@stop

{{--@section('sidebar')--}}
{{--@include('admin.partials._sidebar')--}}
{{--@stop--}}
@php
    $isAuth = auth()->user()->isAdmin() || auth()->user()->groups()->whereType(App\Group::KGS_ADMIN)->exists();
@endphp

@section('body')
    <section class="col-sm-12">
        @if ($documents->total())
            <table class="listing-table">
                <thead>
                <tr>
                    <th>{{t('Name')}}</th>
                    <th>{{t('Start Date')}}</th>
                    <th>{{t('End Date')}}</th>
                    <th>{{t('Remaining Days')}}</th>
                    <th>{{t('Document')}}</th>
                    <th>{{t('Actions')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($documents as $document)
                    <tr @if($document->markAsShouldRenew()) class="danger" @endif>
                        <td>
                            @if($isAuth)
                                <a href="{{route('kgs.document.edit', compact('folder','document'))}}">{{$document->name}}</a>
                            @else
                                <p>{{$document->name}}</p>
                            @endif
                        </td>

                        <td>{{$document->start_date ? $document->start_date->format('Y-m-d') : ''}}</td>
                        <td>{{$document->end_date ? $document->end_date->format('Y-m-d') : ''}}</td>
                        <td>{{$document->end_date ? $document->end_date->diffInDays(\Carbon\Carbon::now()) : ''}}</td>
                        <td><a href="{{route('kgs.business_document.download',['attachment'=>$document])}}"
                               target="_blank">{{basename($document->path) ?? ''}}</a></td>

                        <td class="col-md-3">


                            <form action="{{route('kgs.document.destroy', compact('folder','document'))}}"
                                  method="post">
                                {{--                        <td>--}}
                                <a class="btn btn-sm btn-primary"
                                   href="{{route('kgs.document.select_category', ['business_unit'=>$folder->business_unit])}}"
                                ><i
                                            class="fa fa-plus"></i> {{t('New Ticket')}}</a>
                                {{--                        </td>--}}
                                <a href="{{route('kgs.business_document.download',['attachment'=>$document])}}"
                                   class="btn btn-sm btn-success"
                                   target="_blank"><i class="fa fa-download"></i></a>
                                {{csrf_field()}} {{method_field('delete')}}
                                @if(auth()->user()->isAdmin() || auth()->user()->groups()->whereType(App\Group::KGS_ADMIN)->exists())
                                    <a class="btn btn-sm btn-primary"
                                       href="{{route('kgs.document.edit', compact('folder','document'))}}"><i
                                                class="fa fa-edit"></i> </a>
                                    <button data-toggle="modal" data-target="#MoveForm" type="button"
                                            class="btn btn-sm btn-success btn-outlined " title="Move"
                                            onclick="changeDocumentId({{$document->id}})">
                                        <i class="fa fa-mail-forward"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning"><i class="fa fa-trash-o"></i>
                                    </button>
                                @endif

                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {{--            @include('partials._pagination', ['items' => $documents])--}}
        @else
            <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i>
                <strong>{{t('No Documents found')}}</strong>
            </div>
        @endif
    </section>
    <section id="documentArea">
        @include('kgs::business-documents.documents._modal._move')
    </section>
@endsection

@section('javascript')
    <script>
        var document_id;

        function changeDocumentId(id) {
            $('#document_id').val(id)
        }

        var business_unit = '{{Form::getValueAttribute('business_unit') ?? $folder->business_unit->id}}';
        var folder = '{{Form::getValueAttribute('folder') ?? $folder}}';
    </script>
    <script src="{{asset('/js/document-form.js')}}"></script>
@endsection
