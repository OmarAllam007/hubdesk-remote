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
    <div class="flex">
        <div class="flex">

            <a href="{{URL::previous()}}"
               class=" text-center pt-2 pb-2 pl-5 pr-5  m-5 bg-gray-400 shadow-md  rounded-2xl text-viola "><i
                        class="fa fa-arrow-left"></i>
                {{--                {{t('Select Subcategory')}}--}}
            </a>
        </div>
    </div>

    {{--    @if($sla)--}}
    @if($category->notes)
        <div class="flex p-5 ">
            <div class="w-1/2">
                <div class="flex rounded-3xl p-5 mb-5 justify-start shadow-md"
                     style="background-color: rgba(26, 29, 80, 0.9)">
                    <div class="w-25 text-white pt-1  flex-col ">
                        <i class="fa fa-info-circle fa-lg "></i>
                    </div>
                    <div class=" pl-2 pr-2 text-white text-2xl ">
                        {!! $category->notes  !!}
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{--    @endif--}}
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

