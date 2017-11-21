@extends('layouts.app')

@section('header')
    <h4 class="pull-left">Questions</h4>
    <a href="{{route('admin.question.create',compact('survey'))}}" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus"></i></a>
@stop

@section('sidebar')
    @include('admin.partials._sidebar')
@stop
@section('body')
    <section class="col-sm-9">
    @if ($questions->count())
        <table class="listing-table">
            <thead>
            <tr>
                <th>Description</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($questions as $question)
                <tr>
                    <td class="col-md-9"><a href="{{route('admin.question.show', compact('survey','question'))}}">{{$question->description}}</a></td>
                    <td class="col-md-3">
                        <form action="{{route('admin.question.destroy', compact('survey','question'))}}" method="post">
                            {{csrf_field()}} {{method_field('delete')}}
                            <a class="btn btn-sm btn-primary" href="{{route('admin.question.edit',  compact('question','survey'))}}"><i class="fa fa-edit"></i> Edit</a>
                            <button class="btn btn-sm btn-warning"><i class="fa fa-trash-o"></i> Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    @else
        <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> <strong>No Questions found</strong></div>
    @endif
    </section>
@stop
