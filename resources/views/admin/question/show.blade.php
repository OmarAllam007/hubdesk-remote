@extends('layouts.app')
@section('header')
    <h4 class="pull-left">Questions - {{$question->description}}</h4>

    <div class="pull-right">
        <a href="{{route('admin.answer.edit', compact('question'))}}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
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
            <a href="{{route('admin.answer.create',compact('question'))}}" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus-circle"></i> Add Answer</a>
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
                        <td>{{$answer->description}}</td>
                        <td>{{$answer->degree}}</td>
                        <td>

                            <form action="{{route('admin.answer.destroy', compact('answer','question'))}}" method="post"  class="inline-block">
                                {{csrf_field()}} {{method_field('delete')}}
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
                No Answers found
            </div>
        @endif

    </section>
@endsection