@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Header')}}</h4>
    <div class="heading-actions pull-right">
        <form action="" class="form-inline" method="get">
            <div class="input-group">
                <input class="form-control input-sm" type="search" name="search" id="searchTerm" placeholder="Search"
                       value="{{Request::query('search', '')}}">
                <span class="input-group-btn">
                    <button class="btn btn-default btn-sm"><i class="fa fa-search"></i></button>
                </span>
            </div>
            <a href="{{ route('letters.header.create') }} " class="btn btn-sm btn-primary"><i
                        class="fa fa-plus"></i></a>
            {{--<a title="Import from active directory" href="#ImportModal" data-toggle="modal" class="btn btn-sm btn-primary"><i class="fa fa-download"></i></a>--}}
        </form>
    </div>
@stop

@section('body')

    <div class="flex w-full">
        <div class="w-1/4">
            @include('letters_admin._partial._sidebar')
        </div>

        <section class="w-3/4 mx-5 ">
            @if ($headers->total())
                <div class="bg-white shadow-md rounded my-5 ">
                    <table class="min-w-max w-full table-auto">
                        <thead class="bg-gray-300 text-gray-800  uppercase  leading-normal">
                        <tr>
                            <th class="py-5  px-6 text-left">{{t('Business Unit')}}</th>
                            <th class="py-5  px-6 text-left">{{t('Actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($headers as $header)
                            <tr class="border-b border-gray-200 hover:bg-yellow-100">
                                <td class="py-5  px-6 text-left whitespace-nowrap">{{ $header->business_unit->name }}
                                </td>
                                <td class="py-5  px-6 text-left whitespace-nowrap">
                                    <form action="{{ route('letters.header.destroy', $header) }}" method="post">
                                        {{csrf_field()}} {{method_field('delete')}}
                                        <a class="btn btn-sm btn-primary"
                                           href="{{ route('letters.header.edit', $header) }} "><i
                                                    class="fa fa-edit"></i> Edit</a>
                                        <button class="btn btn-sm btn-warning"><i class="fa fa-trash-o"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-center">
                    {!! $headers->links() !!}
                </div>
            @else
                <div class="flex bg-yellow-100 p-5 rounded-xl  shadow-sm m-5">
                    <i class="fa fa-exclamation-circle px-2    "></i> <strong>No headers found</strong></div>
            @endif
        </section>
@stop
