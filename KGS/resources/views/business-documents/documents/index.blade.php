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
    <style>
        .light-danger {
            /*background-color: #84082e !important;*/

        }

        .deep_danger {
            background-color: #810303 !important;
            color: #ffff;

        }

        .deep_danger > td > a {
            color: #ffff;
        }
    </style>
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
                    <th>{{t('Remarks')}}</th>
                    <th>{{t('Actions')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($documents as $document)
                    <tr @if($document->markAsShouldRenew()) class="{{$document->warning_color}}" @endif>
                        <td>
                            @if($isAuth)
                                <a href="{{route('kgs.document.edit', compact('folder','document'))}}">{{$document->name}}</a>
                            @else
                                <p>{{$document->name}}</p>
                            @endif
                        </td>
                        <td>{{$document->start_date ? $document->start_date->format('Y-m-d') : ''}}</td>
                        <td>{{$document->end_date ? $document->end_date->format('Y-m-d') : ''}}</td>
                        <td>{{$document->end_date ? $document->remaining_days : ''}}</td>
                        <td><a href="{{route('kgs.business_document.download',['attachment'=>$document])}}"
                               target="_blank">{{basename($document->path) ?? ''}}</a></td>
                        <td class="col-md-2 ">
                            @if($document->remarks)
                                <a href="#" data-toggle="modal" data-target="#showRemarks" type="button"
                                   class="btn btn-sm btn-primary btn-outlined showRemarks" title="Remarks"
                                   data-remarks="{{ strip_tags($document->remarks) }}">
                                    <i class="fa fa-sticky-note"></i>
                                    {{t('Remarks')}}</a>
                            @endif
                        </td>
                        <td class="col-md-4">
                            <form action="{{route('kgs.document.destroy', compact('folder','document'))}}"
                                  method="post">
                                {{--                        <td>--}}
                                <a class="btn btn-sm btn-primary"
                                   href="{{route('kgs.document.create_check', compact('document'))}}"
                                ><i
                                            class="fa fa-plus"></i> {{t('New Ticket')}}</a>

                                <a class="btn btn-sm btn-success"
                                   data-toggle="modal" data-target="#issueModal"
                                   data-document-id="{{$document->id}}"
                                   data-document-name="{{$document->name}}"
                                ><i
                                            class="fa fa-refresh"></i> {{t('Issue/New')}}</a>

                                <a class="btn btn-sm btn-danger"
                                   data-toggle="modal" data-target="#cancelModal"
                                   data-document-id="{{$document->id}}"
                                   data-document-name="{{$document->name}}"
{{--                                   href="{{route('kgs.document.create_check', compact('document'))}}"--}}
                                ><i
                                            class="fa fa-close"></i> {{t('Cancel')}}</a>

                                <a href="{{route('kgs.business_document.download',['attachment'=>$document])}}"
                                   class="btn btn-sm btn-default btn-outlined"
                                   target="_blank"><i class="fa fa-download"></i></a>
                                {{csrf_field()}} {{method_field('delete')}}
                                @if(auth()->user()->isAdmin() || auth()->user()->groups()->whereType(App\Group::KGS_ADMIN)->exists())
                                    <a class="btn btn-sm btn-primary btn-outlined"
                                       href="{{route('kgs.document.edit', compact('folder','document'))}}"><i
                                                class="fa fa-edit"></i> </a>
                                    <button data-toggle="modal" data-target="#MoveForm" type="button"
                                            class="btn btn-sm btn-success btn-outlined " title="Move"
                                            onclick="changeDocumentId({{$document->id}})">
                                        <i class="fa fa-mail-forward"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger"><i class="fa fa-trash-o"></i>
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


        <form id="issueForm">
            @csrf
            <div class="modal fade" id="issueModal" tabindex="-1" role="dialog" aria-labelledby="issueModal"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Issue New Ticket</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Are you sure to create a new ticket to issue a new document?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>


            <form id="cancelForm">
                @csrf
                <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModal"
                     aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Cancel Document</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Are you sure to create a new ticket to cancel a document?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Create</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
    </section>
    <section id="documentArea">
        @include('kgs::business-documents.documents._modal._move')
    </section>
    @include('kgs::business-documents.documents._modal._remarks')

@endsection

@section('javascript')
    <script>
        var document_id;

        function changeDocumentId(id) {
            $('#document_id').val(id)
        }

        $(".showRemarks").on('click', function () {
            $('p#remarks').text($(this).data("remarks"));
        });

        var business_unit = '{{Form::getValueAttribute('business_unit') ?? $folder->business_unit->id}}';
        var folder = '{{Form::getValueAttribute('folder') ?? $folder}}';

        $('#issueModal').on('show.bs.modal', function (e) {
            var documentId = $(e.relatedTarget).data('document-id');
            var documentName = $(e.relatedTarget).data('document-name');
            console.log(documentName)
            $('.modal-title').text(`Issue new document > ${documentName}`);
            $('#issueForm').attr('action',`/kgs/business-document/${documentId}/issue`)
            $('#issueForm').attr('method','post')
        });

        $('#cancelModal').on('show.bs.modal', function (e) {
            var documentId = $(e.relatedTarget).data('document-id');
            var documentName = $(e.relatedTarget).data('document-name');
            console.log(documentName)
            $('.modal-title').text(`Cancel document > ${documentName}`);
            $('#cancelForm').attr('action', `/kgs/business-document/${documentId}/cancel`)
            $('#cancelForm').attr('method', 'post')
        });

    </script>

    <script src="{{asset('/js/document-form.js')}}"></script>
@endsection
