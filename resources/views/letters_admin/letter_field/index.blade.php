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
            <a href="{{ route('letters.letter-field.create') }} " class="btn btn-sm btn-primary"><i
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

        <section class="w-3/4">
            @if ($fields->total())
                <table class="listing-table">
                    <thead>
                    <tr>
                        <th>{{t('Name')}}</th>
                        <th>{{t('Actions')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($fields as $field)
                        <tr>
                            <td class="col-md-5"><a
                                        href="{{ route('letters.letter-field.show', $field) }}">{{ $field->name }}</a>
                            </td>
                            {{--                        <td>{{$field->is_disabled ? 'No' : 'Yes'}}</td>--}}
                            <td class="col-md-3">
                                <form action="{{ route('letters.letter-field.destroy', $field) }}" method="post">
                                    {{csrf_field()}} {{method_field('delete')}}
                                    <a class="btn btn-sm btn-primary"
                                       href="{{ route('letters.letter-field.edit', $field) }} "><i
                                                class="fa fa-edit"></i> Edit</a>
                                    <button class="btn btn-sm btn-warning"><i class="fa fa-trash-o"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {!! $fields->links() !!}
                {{--            @include('partials._pagination', ['items' => $fields])--}}
            @else
                <div class="flex bg-yellow-100 p-5 rounded-xl  shadow-sm m-5">
                    <i class="fa fa-exclamation-circle px-2    "></i> <strong>No letters found</strong></div>
            @endif
        </section>
@stop
