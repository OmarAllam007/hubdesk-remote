@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Create Ticket')}}</h4>

    <a href="{{ route('ticket.index')}}" class="btn btn-sm btn-default pull-right"><i
                class="fa fa-chevron-left"></i></a>
@stop

@section('body')

    <div class="w-full p-5">
        @php
            $language =\Session::get('personalized-language' . \Auth::user()->id, \Config::get('app.locale'));
        @endphp

        <div id="ticketForm">
            @php
                $showBalance = (env('BALANCE_SERVICES') && in_array($category->id , explode(',',env('BALANCE_SERVICES')))) ? 1 : 0;
                $createForOthers = !isset($ticket) && Auth::user()->isSupport() && $category->id != 56  &&
                 (isset($item) && !in_array( $item->id,[296,297]));

                $notes = '';
                if ($category->notes || (isset($subcategory) && $subcategory->notes) || (isset($item) && $item->notes)){
                    $notes = $category->notes.' '. $subcategory->notes .' '. $item->notes;
                }

                $ticketObj = new \App\Ticket();
                $sla = $ticketObj->getSla($category,$subcategory ?? null ,$item ?? null,$subItem ?? '');

                $ticket_attr = [
                                'category_id'=> $category->id,'subcategory_id'=> $subcategory->id,
                                'item_id'=>$item->id ?? 0,'subitem_id' => $subItem->id ?? 0, 'notes'=>$notes,'sla'=> $sla,
                                ];
                $subject = $subject = (auth()->user()->employee_id ? auth()->user()->employee_id.' - ' : '').
                     $category->name.(isset($subcategory->name) ? '  -  '.  $subcategory->name:'').(isset($item->name) ? '  -  '.  $item->name:'').(isset($subItem->name) ? '  -  '.  $subItem->name:'');

                $createForOthers = $createForOthers ? 1 : 0;
                $priorities = \App\Priority::all();


                    $translations = \App\Translation::where('language',$language)
                ->where('view',0)
                ->get(['word','translation'])
                ->map(function ($item){
                    return ['word'=> strtolower($item->word),'translation'=> $item->translation];
                });



            @endphp

            {{--            @dd($translations)--}}
            <ticket-form
                    :show_balance="{{$showBalance}}"
                    :auth_user="{{json_encode(Auth::user()->employee_id)}}"
                    :create_for_others="{{$createForOthers}}"
                    :ticket_attr="{{json_encode($ticket_attr)}}"
                    :subject_text="{{json_encode($subject)}}"
                    :notes="{{json_encode($notes)}}"
                    :priorities="{{json_encode($priorities)}}"
                    :translations="{{json_encode($translations)}}"
            >

            </ticket-form>
        </div>
    </div>
    {{--    @include('ticket._form')--}}

    {{--    {{ Form::close() }}--}}
@endsection
@section('javascript')
    {{--    <script>--}}
    {{--        var category = '{{Form::getValueAttribute('category_id') ? Form::getValueAttribute('category_id') : isset($category) ? $category->id : null}}';--}}
    {{--        var subcategory = '{{request('subcategory_id') ? Form::getValueAttribute('subcategory_id', request('subcategory_id')) :  isset($subcategory) ? $subcategory->id : null}}';--}}
    {{--        var item = '{{request('item_id') ? Form::getValueAttribute('item_id',request('item_id')) :  isset($item) ? $item->id : null}}';--}}
    {{--    </script>--}}
    <script src="{{asset('/js/ticket_form/index.js')}}"></script>
    {{--    <script src="{{asset('/js/tinymce/tinymce.min.js')}}"></script>--}}
@append