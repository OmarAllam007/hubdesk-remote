@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Users')}}</h4>
    <div class="heading-actions pull-right">
        <form action="" class="form-inline" method="get">
            <div class="input-group">
                <input class="form-control input-sm" type="search" name="search" id="searchTerm" placeholder="Search"
                       value="{{Request::query('search', '')}}">
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
        @if ($users->total())
            <div class="bg-white shadow-md rounded my-5 w-full overflow-x-scroll">
                <table class="min-w-max w-full table-auto ">
                    <thead class="bg-gray-300 text-gray-800  uppercase  leading-normal">
                    <tr>
                        <th class="py-5  px-6 text-left">{{t('Name')}}</th>
                        <th class="py-5  px-6 text-left">{{t('Employee ID')}}</th>
                        <th class="py-5  px-6 text-left">{{t('Business Unit')}}</th>
                        <th class="py-5  px-6 text-left">{{t('Position')}}</th>
                        <th class="py-5  px-6 text-left">{{t('Department')}}</th>
                        <th class="py-5  px-6 text-left">{{t('Image')}}</th>
                        <th class="py-5  px-6 text-left">{{t('Generated code')}}</th>
                        <th class="py-5  px-6 text-left">{{t('Actions')}}</th>
                    </tr>
                    </thead>
                    <tbody class="overflow-x-scroll">
                    @foreach($users as $user)
                        <tr class="border-b border-gray-200 hover:bg-yellow-100">
                            <td class="py-5  px-6 text-left whitespace-nowrap"><a
                                        href="{{ route('e-card.admin.edit', $user) }}">{{ $user->name }}</a></td>
                            <td class="py-5  px-6 text-left whitespace-nowrap"><a
                                        href="{{ route('e-card.admin.edit', $user) }}">{{ $user->employee_id ?? 'Not Assigned' }}</a>
                            </td>
                            <td class="py-5  px-6 text-left whitespace-nowrap">{{ $user->business_unit->name ?? 'Not Assigned' }}</td>
                            <td class="py-5  px-6 text-left whitespace-nowrap">{{ $user->position ?? 'Not Assigned' }}</td>
                            <td class="py-5  px-6 text-left whitespace-nowrap">{{ $user->department ?? 'Not Assigned' }}</td>
                            <td class="py-5  px-6 text-left whitespace-nowrap">
{{--                                    @if($user->image_url)--}}
                                <img src="{{$user->image}}" width="50" height="50" class="rounded-2xl">
{{--                                        @endif--}}
                            </td>

                            <td class="py-5  px-6 text-left whitespace-nowrap">
                                <a target="_blank" href="{{ route('e-card.admin.user.show', $user->url_code) }}">
                                    {{ env('APP_URL').'/'.$user->url_code ?? 'Not Assigned' }}
                                </a>
                            </td>

                            <td class="py-5  px-6 text-left whitespace-nowrap">
                                <form action="{{ route('e-card.admin.user.destroy', $user) }}" method="post">
                                    {{csrf_field()}} {{method_field('delete')}}
                                    <a class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded" href="{{ route('e-card.admin.edit', $user) }} "><i
                                                class="fa fa-edit"></i></a>
                                    <button class="bg-transparent hover:bg-red-500 text-red-700 font-semibold hover:text-white py-2 px-4 border border-red-500 hover:border-transparent rounded"><i class="fa fa-remove"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                @include('partials._pagination', ['items' => $users])
            </div>
        @else
            <div class="w-full p-5">
                <div class="p-5 bg-blue-300 shadow rounded-xl"><i class="fa fa-exclamation-circle"></i> <strong>No users found</strong></div>
            </div>
    @endif

@stop
