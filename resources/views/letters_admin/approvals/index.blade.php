@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Group')}}</h4>
    <div class="heading-actions pull-right">
        <form action="" class="form-inline" method="get">
            <div class="input-group">
                <input class="form-control input-sm" type="search" name="search" id="searchTerm" placeholder="Search"
                       value="{{Request::query('search', '')}}">
                <span class="input-group-btn">
                    <button class="btn btn-default btn-sm"><i class="fa fa-search"></i></button>
                </span>
            </div>

        </form>
    </div>
@stop


@section('body')

    <div class="flex w-full">
        <div class="w-1/4">
            @include('letters_admin._partial._sidebar')
        </div>

{{--        <div class="overflow-x-auto">--}}
{{--            <div class="min-w-screen min-h-screen bg-gray-100 flex items-center justify-center bg-gray-100 font-sans overflow-hidden">--}}

                <section class="w-3/4 mx-5 ">
                    @if ($business_units->total())
                        <div class="bg-white shadow-md rounded my-5 ">
                            <table class="min-w-max w-full table-auto">
                                <thead>
                                <tr class="bg-gray-300 text-gray-800  uppercase  leading-normal">
                                    <th class="py-5  px-6 text-left">{{t('Name')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($business_units as $business_unit)
                                    <tr class="border-b border-gray-200 hover:bg-yellow-100">
                                        <td class="py-5  px-6 text-left whitespace-nowrap"><a
                                                    href="{{ route('letters.business-unit.approval.show', $business_unit) }}">{{ $business_unit->name }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="flex justify-center">
                            {!! $business_units->links() !!}
                        </div>
                    @else
                        <div class="flex bg-yellow-100 p-5 rounded-xl  shadow-sm m-5">
                            <i class="fa fa-exclamation-circle px-2    "></i> <strong>No Business Units found</strong>
                        </div>
                    @endif
                </section>
{{--            </div>--}}
{{--        </div>--}}
@stop
