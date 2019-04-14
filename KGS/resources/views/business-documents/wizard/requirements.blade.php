@extends('layouts.app')

@section('header')
    <h4 class="pull-left">  {{$category->name}} > {{$subcategory->name ?? ''}} > {{$item->name ?? ''}}
        - {{t('Check for Requirements')}} </h4>
@endsection


@section('body')
    <div id="TicketRequirements">
        @php
            $data = [];
            $data['category_id'] = $category->id;
            $data['subcategory_id'] = $subcategory->id;
            $data['item_id'] = $item->id ?? '';

        @endphp
        <form method="post" action="{{route('kgs.document.create-ticket',compact('business_unit','category','subcategory','item'))}}" enctype="multipart/form-data" class="form-group-lg">
            {{method_field('post')}} {{csrf_field()}}
            <ticket-requirements :requirements="{{$requirements}}" :data="{{json_encode($data)}}"></ticket-requirements>
        </form>
    </div>
@endsection

@section('javascript')
    <script src="{{asset('/js/ticket-requirements.js')}}"></script>
@append
