@extends('layouts.app')

@section('header')
<h4 class="pull-left">{{$subcategory->canonicalName()}}</h4>
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

    <a href="{{route('category.show', $subcategory->category_id)}}" class="btn btn-sm btn-default"><i
        class="fa fa-chevron-left"></i></a>
    </div>
    @endsection


    @section('body')
    <section class="col-sm-12">
        @if ($subcategory->description)
        @endif

        <p class="clearfix">

        </p>

        @if ($subcategory->items->count())

        <div class="container">

           <div class=form-group></div>
           <h3 class=text-center>Items</h3>
           <hr />

           @foreach($subcategory->items as $item)

           <p><a href="{{ route('ticket.create') }}" class="btn btn-outlined btn-block btn-primary">{{$item->name}}</a></p>

           @endforeach
           <hr />

       </div>
   </section>
   @else
   {{ Form::open(['route' => 'ticket.store', 'files' => true, 'class' => 'col-sm-12']) }}

   @include('ticket._form')

   {{ Form::close() }}
   @endif
</section>
@endsection


