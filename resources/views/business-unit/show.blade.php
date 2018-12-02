@extends('layouts.app')

@section('header')

<h4 class="pull-left">{{t('businessUnits')}} - {{$business_unit->name}}</h4>
<form action="" class="form-inline" method="get">
    <div class="input-group">
        <input class="form-control input-sm" type="search" name="search" id="searchTerm" placeholder="Search"
        value="{{Request::query('search', '')}}">
        <span class="input-group-btn">
            <button class="btn btn-default btn-sm"><i class="fa fa-search"></i></button>
        </span>
    </div>
    {{--<a title="Import from active directory" href="#ImportModal" data-toggle="modal" class="btn btn-sm btn-primary"><i class="fa fa-download"></i></a>--}}
    <div class="pull-right">

        <a href="{{route('business-unit.index')}}" class="btn btn-sm btn-default"><i
            class="fa fa-chevron-left"></i></a>
        </div>
    </form>
    @endsection


    @section('body')
    <section class="col-sm-12">
        @if ($business_unit->name)
        @endif



        @if ($business_unit->categories->count())

        <div class="container">

           <div class=form-group></div>
           <h3 class=text-center>Categories</h3>
           <hr />
           @foreach($business_unit->categories as $category)

           <p><a href="{{route('category.show', $category)}}" class="btn btn-outlined btn-block btn-primary">{{$category->name}}</a></p>

           @endforeach
           <hr />

       </div>
   </div>
   @else
   {{ Form::model(['route' => 'ticket.edit', 'files' => true, 'class' => 'col-sm-12']) }}

   @include('ticket._form')

   {{ Form::close() }}
   @endif
</section>
@endsection