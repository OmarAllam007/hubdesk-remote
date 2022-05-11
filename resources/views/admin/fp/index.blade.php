@extends('layouts.app')

@section('header')
    <h4 class="pull-left">Add FP</h4>

    {{--    <a href="{{ route('admin.group.index') }}" class="btn btn-sm btn-default pull-right"><i class="fa fa-chevron-left"></i></a>--}}
@stop

@section('sidebar')
    {{--    @include('admin.partials._sidebar')--}}
@stop

@section('body')

    <p class="p-5">Current Time: {{$time}}</p>

    <form action="{{route('admin.fp.post')}}" method="post">
        @csrf
        <div class="w-1/2 p-5 ">
            <div class="form-group {{$errors->has('name')? 'has-error' : ''}}">
                {{ Form::label('date', 'Date', ['class' => 'control-label']) }}
                {{ Form::datetimeLocal('date', null, ['class' => 'form-control']) }}
                @if ($errors->has('date'))
                    <div class="error-message">{{$errors->first('date')}}</div>
                @endif
            </div>

            <div class="form-group">
                <button class="btn btn-success"><i class="fa fa-check"></i> Submit</button>
            </div>
        </div>
    </form>

    <div class="p-5">
        <table class="table bg-white w-1/2 p-5" >
            <tbody>
            <tr>
                <td>
                    SAP ID
                </td>
                <td>
                    Date time
                </td>
            </tr>
            </tbody>
            @foreach($attendance as $row)
                <tr @if($row['id'] == Auth::user()->employee_id) class="bg-green-400" @endif>
                    <td>
                        {{$row['id']}}
                    </td>
                    <td>
                        {{$row['timestamp']}}
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

@stop
