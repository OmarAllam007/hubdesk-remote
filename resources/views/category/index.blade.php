@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Categories')}}</h4>
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
@stop


@section('body')
    <section class="col-sm-9">
    @if ($categories->total())
        <table class="listing-table">
          
            <tbody>
            @foreach($categories as $category)
                <tr>
                    <td class="col-md-9"><a href="{{route('category.show', $category)}}">{{$category->name}}</a></td>
                    <td class="col-md-3">
                        
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        @include('partials._pagination', ['items' => $categories])
    @else
        <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> <strong>{{t('No categories found')}}</strong></div>
    @endif
    </section>
@stop


