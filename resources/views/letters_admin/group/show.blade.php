@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{$letter_group->name}}</h4>

    <div class="heading-actions pull-right">
        <a href="{{route('letters.letter-group.edit', $letter_group)}}" class="btn btn-sm btn-primary"><i
                    class="fa fa-edit"></i></a>
        <a href="{{route('letters.letter-group.index')}}" class="btn btn-sm btn-default"><i
                    class="fa fa-chevron-left"></i></a>
    </div>
@endsection

@section('sidebar')
    @include('letters_admin._partial._sidebar')
@stop

@section('body')
    <h4 class="p-5">Letters</h4>
    @if ($letter_group->letters->count())
        <table class="listing-table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($letter_group->letters as $letter)
                <tr>
                    <td>{{$letter->name}}</td>
                    <td>
                        {{--                        {{Form::open(['route' => ['letters.letter.destroy', $letter_group, $letter], 'method' => 'delete'])}}--}}
                        {{--                        <a href="{{route('letters.letter.edit', $letter)}}" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>--}}
                        {{--                        <button type="submit" class="btn btn-xs btn-warning"><i class="fa fa-remove"></i> Remove</button>--}}
                        {{--                        {{Form::close()}}--}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <div class="flex  bg-yellow-100 p-5 rounded-xl  shadow-sm m-5">
            <i class="fa fa-exclamation-circle px-2"></i> <strong>No letters found</strong></div>
    @endif

@endsection