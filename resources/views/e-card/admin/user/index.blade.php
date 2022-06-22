@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Users')}}</h4>
    <div class="heading-actions pull-right">
        <form action="" class="form-inline" method="get">
            <div class="input-group">
                <input class="form-control input-sm" type="search" name="search" id="searchTerm" placeholder="Search" value="{{Request::query('search', '')}}">
                <span class="input-group-btn">
                    <button class="btn btn-default btn-sm"><i class="fa fa-search"></i></button>
                </span>
            </div>

            <a href="{{ route('e-card.admin.create') }} " class="btn btn-sm btn-primary"><i class="fa fa-plus"></i></a>


        </form>
    </div>
@stop


@section('body')
    <div class="flex w-full p-5 ">
        <section class="bg-white shadow-md rounded my-5 w-full">
        @if ($users->total())
            <table class="min-w-max w-full table-auto">
                <thead class="bg-gray-300 text-gray-800  uppercase  leading-normal">
                <tr>
                    <th class="py-5  px-6 text-left">{{t('Name')}}</th>
                    <th class="py-5  px-6 text-left">{{t('Employee ID')}}</th>
                    <th class="py-5  px-6 text-left">{{t('Business Unit')}}</th>
                    <th class="py-5  px-6 text-left">{{t('Position')}}</th>
                    <th class="py-5  px-6 text-left">{{t('Department')}}</th>
                    <th class="py-5  px-6 text-left">{{t('Actions')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr class="border-b border-gray-200 hover:bg-yellow-100">
                        <td class="py-5  px-6 text-left whitespace-nowrap"><a href="{{ route('admin.user.edit', $user) }}">{{ $user->name }}</a></td>
                        <td class="py-5  px-6 text-left whitespace-nowrap"><a href="{{ route('admin.user.edit', $user) }}">{{ $user->employee_id ?? 'Not Assigned' }}</a></td>
                        <td class="py-5  px-6 text-left whitespace-nowrap">{{ $user->business_unit ?? 'Not Assigned' }}</td>
                        <td class="py-5  px-6 text-left whitespace-nowrap">{{ $user->position ?? 'Not Assigned' }}</td>
                        <td class="py-5  px-6 text-left whitespace-nowrap">{{ $user->department ?? 'Not Assigned' }}</td>

                        <td class="py-5  px-6 text-left whitespace-nowrap">
                            <form action="{{ route('admin.user.destroy', $user) }}" method="post">
                                {{csrf_field()}} {{method_field('delete')}}
                                <a class="btn btn-sm btn-primary" href="{{ route('admin.user.edit', $user) }} "><i
                                            class="fa fa-edit"></i> Edit</a>
                                <button class="btn btn-sm btn-warning"><i class="fa fa-remove"></i> Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            @include('partials._pagination', ['items' => $users])
        @else
            <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> <strong>No users found</strong></div>
        @endif

    </section>
@stop
