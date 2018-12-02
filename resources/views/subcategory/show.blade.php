@extends('layouts.app')

@section('header')
<h4 class="pull-left">{{$subcategory->canonicalName()}}</h4>

<div class="pull-right">

    <a href="{{route('category.show', $subcategory->category_id)}}" class="btn btn-sm btn-default"><i
        class="fa fa-chevron-left"></i></a>
    </div>
    @endsection


    @section('body')
    <section class="col-sm-9">
        @if ($subcategory->description)
        @endif

        <p class="clearfix">

        </p>

        @if ($subcategory->items->count())
        <table class="listing-table">

            <tbody>
                @foreach($subcategory->items as $item)
                <tr>
                    <td><a href="{{ route('ticket.create') }}">{{$item->name}}</a></td>

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


