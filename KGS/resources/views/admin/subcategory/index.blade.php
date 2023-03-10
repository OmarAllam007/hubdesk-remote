@extends('layouts.app')

@section('header')
    <h4 class="pull-left">Subcategories</h4>
    <a href="{{route('kgs.admin.subcategory.create')}}" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus"></i></a>
@stop

@section('sidebar')
    @include('kgs::admin.partials._sidebar')
@stop

@section('body')
    <section class="col-sm-9">
    @if ($subcategories->total())
        <table class="listing-table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($subcategories as $subcategory)
                <tr>
                    <td class="col-md-3"><a href="{{route('kgs.admin.subcategory.show', $subcategory)}}">{{$subcategory->name}}</a></td>
                    <td class="col-md-4">{{$subcategory->category->name}}</td>
                    <td class="col-md-2">{{App\Subcategory::$BUSINESS_TYPES[$subcategory->business_service_type] ?? 'Not Assigned'}}</td>

                    <td class="col-md-3">
                        <form action="{{route('kgs.admin.subcategory.destroy', $subcategory)}}" method="post">
                            {{csrf_field()}} {{method_field('delete')}}
                            <a class="btn btn-sm btn-primary" href="{{route('kgs.admin.subcategory.edit', $subcategory)}}"><i class="fa fa-edit"></i> Edit</a>
                            <button class="btn btn-sm btn-warning"><i class="fa fa-trash-o"></i> Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        @include('partials._pagination', ['items' => $subcategories])
    @else
        <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> <strong>No subcategories found</strong></div>
    @endif
    </section>
@stop
