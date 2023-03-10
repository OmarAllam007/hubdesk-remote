@extends('layouts.app')

@section('header')
    <h4 class="pull-left">Surveys</h4>
    <a href="{{route('admin.survey.create')}}" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus"></i></a>
@stop


@section('body')
    <div class="flex w-full">
        <div class="w-1/4">
            @include('admin.partials._sidebar')
        </div>

        <section class="w-3/4">
            @if ($surveys->total())
                <table class="listing-table">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($surveys as $survey)
                        <tr>
                            <td class="col-md-6"><a href="{{route('admin.survey.show', $survey)}}">{{$survey->name}}</a>
                            </td>
                            <td class="col-md-3">
                                <a class="btn btn-sm btn-primary" href="{{route('admin.survey.edit', $survey)}}"><i
                                            class="fa fa-edit"></i> Edit</a>
                                <form action="{{route('admin.survey.destroy', $survey)}}" method="post"
                                      class="inline-block">
                                    {{csrf_field()}} {{method_field('delete')}}
                                    <button class="btn btn-sm btn-warning"><i class="fa fa-trash-o"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                @include('partials._pagination', ['items' => $surveys])
            @else
                <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> <strong>No Surveys found</strong>
                </div>
            @endif
        </section>
@stop