@extends('layouts.app')

@section('header')
    <h4 class="pull-left">  {{$category->name}} > {{$subcategory->name ?? ''}} > {{$item->name ?? ''}} - {{t('Check for Requirements')}} </h4>
@endsection


@section('body')
    <div id="TicketRequirements">
        @php
            $data = [];
            $data['category_id'] = $category->id;
            $data['subcategory_id'] = $subcategory->id;
            $data['item_id'] = $item->id ?? '';

        @endphp
        <ticket-requirements :requirements="{{$requirements}}" :data="{{json_encode($data)}}"></ticket-requirements>
    </div>
@endsection

@section('javascript')
    <script src="{{asset('/js/ticket-requirements.js')}}"></script>
@append
