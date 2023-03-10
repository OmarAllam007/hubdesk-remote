@extends('layouts.app')

@section('header')
    <h4 class="pull-left">Subcategories</h4>
    <a href="{{route('admin.subcategory.create')}}" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus"></i></a>
@stop

@section('body')
    <div class="flex w-full">
        <div class="w-1/4">
            @include('admin.partials._sidebar')
        </div>

        <section class="w-3/4">
            @if ($subcategories->total())
                <table class="listing-table">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>{{t('Type')}}</th>
                        <th>{{t('Is Active?')}}</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($subcategories as $subcategory)
                        <tr>
                            <td class="col-md-3"><a
                                        href="{{route('admin.subcategory.edit', $subcategory)}}">{{$subcategory->name}}</a>
                            </td>
                            <td class="col-md-2">{{$subcategory->category->name}}</td>
                            <td class="col-md-2">{{App\Subcategory::$BUSINESS_TYPES[$subcategory->business_service_type] ?? 'Not Assigned'}}</td>
                            <td class="col-md-2">{{$subcategory->is_disabled ? 'No' : 'Yes'}}</td>
                            <td class="col-md-3">
                                <form action="{{route('admin.subcategory.destroy', $subcategory)}}" method="post">
                                    {{csrf_field()}} {{method_field('delete')}}
                                    <a class="btn btn-sm btn-primary"
                                       href="{{route('admin.subcategory.edit', $subcategory)}}"><i
                                                class="fa fa-edit"></i> Edit</a>
                                    <button class="btn btn-sm btn-warning"><i class="fa fa-trash-o"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                @include('partials._pagination', ['items' => $subcategories])
            @else
                <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> <strong>No subcategories
                        found</strong></div>
            @endif
        </section>
@stop
