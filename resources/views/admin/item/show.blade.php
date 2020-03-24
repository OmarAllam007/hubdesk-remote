@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{$item->canonicalName()}}</h4>

    <div class="pull-right">
        <a href="{{route('admin.item.edit', $item)}}" class="btn btn-sm btn-primary"><i
                    class="fa fa-edit"></i></a>
        <a href="{{route('admin.item.show', $item->subcategory_id)}}" class="btn btn-sm btn-default"><i
                    class="fa fa-chevron-left"></i></a>
    </div>
@endsection

@section('sidebar')
    @include('admin.partials._sidebar')
@stop

@section('body')
    <section class="col-sm-9">
    @if ($item->description)
        <p>{!! nl2br(e($item->description)) !!}</p>
    @endif

    <h4>SubItems</h4>

    <p class="clearfix">
        <a href="{{route('admin.subItem.create')}}?item={{$item->id}}"
           class="pull-right btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> Add SubItem</a>
    </p>

    @if ($item->subItems->count())
    <table class="listing-table">
        <thead>
        <tr>
            <th>Item</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($item->subItems as $subItem)
            <tr>
                <td>{{$subItem->name}}</td>
                <td>
                    {{Form::open(['route' => ['admin.subItem.destroy', $subItem], 'method' => 'delete'])}}
                    <a class="btn btn-xs btn-primary" href="{{route('admin.subItem.edit', $subItem)}}"><i
                                class="fa fa-edit"></i> Edit</a>
                    <button type="submit" class="btn btn-xs btn-warning"><i class="fa fa-trash"></i> Delete</button>
                    {{Form::close()}}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @else
        <div class="alert alert-warning">
            <i class="fa fa-exclamation-circle"></i>
            No subItems found
        </div>
    @endif
    </section>
@endsection