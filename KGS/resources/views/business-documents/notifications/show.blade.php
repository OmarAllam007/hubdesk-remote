@extends('layouts.app')
@section('header')
    <h4 class="pull-left"> {{$business_unit->name}} > {{t('Manage Notifications')}}</h4>
    <div class="btn-group">
        {{--        <a class="btn  btn-success" href="{{route('kgs.document.index',compact('business_unit'))}}"><i class="fa fa-file" ></i> {{t('Documents')}}</a>--}}
        <a class="btn  btn-default" href="{{route('kgs.document.select_category',compact('business_unit'))}}"><i
                    class="fa fa-1x fa-arrow-right"></i></a>
    </div>
@endsection
@section('body')
    {{ Form::open(['route' => ['kgs.document.manage_notifications','business_unit'=>$business_unit], 'files' => true, 'class' => 'col-sm-6'
    ,"id"=>"Notifications"]) }}
    {{ csrf_field() }}

    <div>
        <div class="row">
            <div class="col-md-12">
                <notifications
                        :users="{{json_encode(\App\User::orderBy('name')->get(['name','id']))}}" :db_notifications="{{$business_unit->document_notifications}}"></notifications>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <button class="btn btn-success"><i class="fa fa-check"></i> {{t('Submit')}}</button>
                </div>
            </div>
        </div>
    </div>
    {{ Form::close() }}
@endsection
@section('javascript')
    <script type="text/javascript" src="{{asset('/js/kgs/notifications/notifications.js')}}"></script>
@append