@extends('layouts.app')

@section('header')
    <h4 class="pull-left">Survey - {{$survey->name}}</h4>

    <div class="pull-right">
        <a href="{{route('admin.survey.edit', $survey)}}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
        <a href="{{route('admin.survey.index')}}" class="btn btn-sm btn-default"><i class="fa fa-chevron-left"></i></a>
    </div>
@endsection

@section('sidebar')
    @include('admin.partials._sidebar')
@stop

@section('body')
    <section class="col-sm-9">
        @if ($survey->description)
            {!! nl2br(e($survey->description)) !!}
        @endif

        <h4>Questions</h4>
        <p class="clearfix">
            <a href="{{route('admin.question.create',compact('survey'))}}" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus-circle">
                </i> Add Question</a>
        </p>
        @if ($survey->questions->count())
            <table class="listing-table">
                <thead>
                <tr>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($survey->questions as $question)
                    <tr>
                        <td><a href="{{route('admin.question.show', compact('question'))}}">{{$question->description}}</a></td>
                        <td>

                            <form action="{{route('admin.question.destroy', compact('question','survey'))}}" method="post"  class="inline-block">
                                {{csrf_field()}} {{method_field('delete')}}
                                <a class="btn btn-xs btn-primary"
                                   href="{{route('admin.question.edit', compact('question'))}}"><i class="fa fa-edit"></i> Edit</a>
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