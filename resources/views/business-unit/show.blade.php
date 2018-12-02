@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('businessUnits')}} - {{$business_unit->name}}</h4>

    <div class="pull-right">
        <a href="{{route('business-unit.index')}}" class="btn btn-sm btn-default"><i class="fa fa-chevron-left"></i></a>
    </div>
@endsection


@section('body')
    <section class="col-sm-9">
        @if ($business_unit->name)
        @endif

        <p class="clearfix">

        </p>

        @if ($business_unit->categories->count())
        <table class="listing-table">
            
            <tbody>
            @foreach($business_unit->categories as $category)
            <tr>
                <td><a href="{{route('category.show', $category)}}">{{$category->name}}</a></td>
                <td>
                    
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        @else
           {{ Form::model(['route' => 'ticket.edit', 'files' => true, 'class' => 'col-sm-12']) }}

        @include('ticket._form')

    {{ Form::close() }}
        @endif
    </section>
@endsection