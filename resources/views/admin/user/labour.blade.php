@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Upload Users')}}</h4>

    <a href="{{ route('admin.user.index') }}" class="btn btn-sm btn-default pull-right"><i
                class="fa fa-chevron-left"></i></a>
@stop

@section('body')
    <div class="col-md-12">

        <form action="{{route('admin.user.labour.submit.upload')}}" method="post" enctype="multipart/form-data" id="form">
            {{csrf_field()}} {{method_field('post')}}
            <div class="form-group ">
                <label for="upload-form">{{t('Select File')}}</label>
                <input class="form-control" type="file" name="users" id="users"
                       accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
            </div>

            <div class="form-group">
                <button class="btn btn-success upload">
                    <i class="fa fa-upload"></i> {{t('Upload')}}
                </button>
            </div>

        </form>
        <div id="preview" style="display: none">
            <p>Loading</p>
        </div>
    </div>
@endsection

@section('javascript')
@endsection