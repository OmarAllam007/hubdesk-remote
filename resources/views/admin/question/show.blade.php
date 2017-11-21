@extends('layouts.app')
@section('header')
    <h4 class="pull-left">Questions - {{$question->description}}</h4>

    <div class="pull-right">
        <a href="{{route('admin.question.edit', compact('question'))}}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
        <a href="{{url()->previous()}}" class="btn btn-sm btn-default"><i class="fa fa-chevron-left"></i></a>
    </div>
@endsection

@section('sidebar')
    @include('admin.partials._sidebar')
@stop

@section('body')
    <section class="col-sm-9">
        <h4>Answers</h4>

        <p class="clearfix">
            <a href="{{route('admin.question.create',compact('survey'))}}" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus-circle"></i> Add Answer</a>
        </p>

        @if ($question->answers->count())
            <table class="listing-table">
                <thead>
                <tr>
                    <th>Description</th>
                    <th>Degree</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($question->answers as $answer)
                    <tr>
                        <td><a href="{{route('admin.answer.show', compact('answer'))}}">{{$answer->description}}</a></td>
                        <td>{{$answer->degree}}</td>
                        <td>

                            <form>
                                <a class="btn btn-xs btn-primary"
                                   href="{{route('admin.answer.edit', compact('answer'))}}"><i class="fa fa-edit"></i> Edit</a>
                                <button type="submit" class="btn btn-xs btn-warning"><i class="fa fa-trash"></i> Delete
                                </button>
                            </form>

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-warning">
                <i class="fa fa-exclamation-circle"></i>
                No Questions found
            </div>
        @endif

    </section>
@endsection