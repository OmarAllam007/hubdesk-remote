@extends('layouts.app')
@section('header')
<h4 class="pull-left">{{t('Categories')}} - {{$category->name}}</h4>
<form action="" class="form-inline" method="get">
        <div class="input-group">
            <input class="form-control input-sm" type="search" name="search" id="searchTerm" placeholder="Search"
                   value="{{Request::query('search', '')}}">
            <span class="input-group-btn">
                    <button class="btn btn-default btn-sm"><i class="fa fa-search"></i></button>
                </span>
        </div>
    {{--<a title="Import from active directory" href="#ImportModal" data-toggle="modal" class="btn btn-sm btn-primary"><i class="fa fa-download"></i></a>--}}
</form>
<div class="pull-right">

    <a href="{{route('business-unit.show', $category->business_unit_id)}}" class="btn btn-sm btn-default"><i
        class="fa fa-chevron-left"></i></a>
    </div>
    @stop


    @section('body')
    <section class="col-sm-12">
        @if ($categories->total())
        <div class="container">


           <div class=form-group></div>
           <h3 class=text-center>{{t('Categories') }}</h3>
           <hr />

           @foreach($categories as $category)

           <p><a href="{{route('category.show', $category)}}" class="btn btn-outlined btn-block btn-primary">{{$category->name}}</a></p>

           @endforeach
           <hr />

       </div>
   </div>
   @include('partials._pagination', ['subcategories' => $categories])
   @else
 {{ Form::open(['route' => 'ticket.store', 'files' => true, 'class' => 'col-sm-12']) }}

@if (isset($category->service_cost))
 <h4>{{t('Service Cost: ' . $category->service_cost . ' SR')}}</h4>
   <hr />
@endif

  @include('ticket._form')
 
{{ Form::close() }}
@endif
</section>
@stop
