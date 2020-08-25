@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Create Ticket')}}</h4>

    {{--<a href="{{ route('ticket.index')}}" class="btn btn-sm btn-default pull-right"><i--}}
    {{--class="fa fa-chevron-left"></i></a>--}}
@stop

@section('body')
    @php
        $label = $category->name . ' > ' .$subcategory->name .  ($item ? ' > ' . $item->description : '');
    @endphp
    <div class="col-md-6">
        <form method="post"
              action="{{route('kgs.document.create-ticket',compact('business_unit','category','subcategory','item'))}}"
              enctype="multipart/form-data">
            {{method_field('post')}} {{csrf_field()}}
            <div class="form-group  col-md-12 form-group-sm {{$errors->has('subject')? 'has-error' : ''}}">
                {{ Form::label('subject', t('Subject'), ['class' => 'control-label']) }}
                {{ Form::text('subject', $label , ['class' => 'form-control']) }}
                @if ($errors->has('subject'))
                    <div class="error-message">{{$errors->first('subject')}}</div>
                @endif
            </div>
            <div class="form-group col-md-12 form-group-sm {{$errors->has('description')? 'has-error' : ''}}">
                {{ Form::label('description', t('Subject'), ['class' => 'control-label']) }}
                {{ Form::textarea('description', $label , ['class' => 'form-control']) }}
                @if ($errors->has('description'))
                    <div class="error-message">{{$errors->first('description')}}</div>
                @endif
            </div>

            <div >

                <div id="CustomFields">
                    @include('custom-fields.render', [
                        'category' => App\Category::find($category->id),
                        'subcategory' => isset($subcategory) ? App\Subcategory::find($subcategory->id) : null,
                        'item' => isset($item) ? App\Item::find($item->id) : null
                    ])
                </div>
            </div>
            <div class="form-group col-md-12">
                <input type="file" class="form-control-file" name="ticket-attachments[]" multiple>
            </div>
            <div class="form-group col-md-12">
                <button class="btn btn-sm btn-success" :disabled="!allHaveChecked">{{t('Submit')}}</button>
            </div>
        </form>
    </div>


@stop
