@extends('layouts.app')

@section('header')
    <h4 class="pull-left">  {{t($category->name)}} > {{t($subcategory->name ?? '')}} > {{t($item->name ?? '')}}
        > {{t('Check for Requirements')}} </h4>
@endsection

@section('stylesheets')
    <style>
        @keyframes slideInFromUp {
            0% {
                transform: translateX(-20%);
            }
            100% {
                transform: translateX(0);
            }
        }


        .card-section {
            animation: .5s ease-out 0s 1 slideInFromUp;
            /*padding: 30px;*/
        }

    </style>
@endsection
@section('body')
    <div id="TicketRequirements" class="card-section">
        @php
            $data = [];
            $data['category_id'] = $category->id;
            $data['subcategory_id'] = $subcategory->id;
            $data['item_id'] = $item->id ?? '';

        @endphp
        <form method="post"
              action="{{route('kgs.document.create-ticket',compact('business_unit','category','subcategory','item'))}}"
              enctype="multipart/form-data" class="form-group-lg">
            {{method_field('post')}} {{csrf_field()}}
            <div id="CustomFields">
                @include('custom-fields.render')
            </div>
            <ticket-requirements :requirements="{{$requirements}}" :data="{{json_encode($data)}}"></ticket-requirements>


        </form>
    </div>
@endsection

@section('javascript')
    <script src="{{asset('/js/ticket-requirements.js')}}"></script>
@append

