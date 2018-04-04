@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Customers')}}</h4>
    <form action="" class="form-inline" method="get">
        <div class="input-group">
            <input class="form-control input-sm" type="search" name="search" id="searchTerm" placeholder="Search"
                   value="{{Request::query('search', '')}}">
            <span class="input-group-btn">
                    <button class="btn btn-default btn-sm"><i class="fa fa-search"></i></button>
                </span>
        </div>
        <a href="{{route('admin.customer.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i></a>
        {{--<a title="Import from active directory" href="#ImportModal" data-toggle="modal" class="btn btn-sm btn-primary"><i class="fa fa-download"></i></a>--}}
    </form>
@stop

@section('sidebar')
    @include('admin.partials._sidebar')
@stop

@section('body')
    <section class="col-sm-9">
    @if ($customers->total())
        <table class="listing-table">
            <thead>
            <tr>
                <th>{{t('Name')}}</th>
                <th>{{t('Mobile')}}</th>
                <th>{{t('Phone')}}</th>
                <th>{{t('Email')}}</th>
                <th>{{t('City')}}</th>
                <th>{{t('Business Unit')}}</th>
                <th>{{t('Branch')}}</th>
                <th>{{t('National ID')}}</th>
                <th>{{t('Type')}}</th>
                <th>{{t('Actions')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($customers as $customer)
                <tr>
                    <td class="col-md-2"><a href="{{route('admin.customer.edit', $customer)}}">{{$customer->name}}</a></td>
                    <td class="col-md-2">{{$customer->mobile}}</td>
                    <td class="col-md-2">{{$customer->phone}}</td>
                    <td class="col-md-2">{{$customer->email}}</td>
                    <td class="col-md-1">{{$customer->city}}</td>
                    <td class="col-md-2">{{$customer->businessunit->name}}</td>
                    <td class="col-md-2">{{$customer->branch->name}}</td>
                    <td class="col-md-2">{{$customer->national_id}}</td>
                    <td class="col-md-1">{{\App\Customer::$Types[$customer->type]}}</td>
                    <td class="col-md-3">
                        <a class="btn btn-sm btn-primary" href="{{route('admin.customer.edit', $customer)}}"><i class="fa fa-edit"></i> Edit</a>
                        <form action="{{route('admin.customer.destroy', $customer)}}" method="post" class="inline-block">
                            {{csrf_field()}} {{method_field('delete')}}
                            {{--<button class="btn btn-sm btn-warning"><i class="fa fa-trash-o"></i> Delete</button>--}}
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        @include('partials._pagination', ['items' => $customers])
    @else
        <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> <strong>No Customers found</strong></div>
    @endif
    </section>
@stop