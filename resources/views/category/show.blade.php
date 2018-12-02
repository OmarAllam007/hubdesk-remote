@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Categories')}} - {{$category->name}}</h4>

    <div class="pull-right">
        <a href="{{route('category.index')}}" class="btn btn-sm btn-default"><i class="fa fa-chevron-left"></i></a>
    </div>
@endsection


@section('body')
    <section class="col-sm-9">
        @if ($category->description)
        @endif

        <p class="clearfix">
        </p>

        @if ($category->subcategories->count())
        <table class="listing-table">
            <thead>
       
            </thead>
            <tbody>
            @foreach($category->subcategories as $subcategory)
            <tr>
                <td><a href="{{route('subcategory.show', $subcategory)}}">{{$subcategory->name}}</a></td>
                <td>
                    
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        @else
          {{ Form::open(['route' => 'ticket.store', 'files' => true, 'class' => 'col-sm-12']) }}

        @include('ticket._form')

    {{ Form::close() }}
        @endif
    </section>
@endsection