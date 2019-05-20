{{ csrf_field() }}
<div id="TicketForm">
    <div class="row">
        <div class="col-md-6">
            @if($errors->count())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
    <div class="row">

        <div class="col-sm-6">

            @if (!isset($ticket) && Auth::user()->isSupport())
                <div class="form-group form-group-sm {{$errors->has('requester_id')? 'has-error' : ''}}">
                    {{ Form::label('requester_id', t('Requester'), ['class' => 'control-label']) }}
                    <select name="requester_id" id="requester_id" class="form-control select2" >
                        <option value="{{Auth::user()->id}}">{{t('Create for me')}}</option>
                        @foreach(App\User::orderBy('name')->where('employee_id','<>',0)->get() as $requester)
                            <option value="{{$requester->id}}"> {{$requester->employee_id }}
                                - {{$requester->name}}</option>
                        @endforeach
                    </select>
                    <br>
                    <div v-show="loading">
                        <i class="fa fa-2x fa-spinner fa-spin"></i>
                    </div>

                    <div v-show="!loading">
                            <span style="padding-right: 10px" v-if="requester.business_unit_name">
                            <small><strong>{{t('Business Unit')}}</strong></small> : <small
                                        v-text="requester.business_unit_name"> </small>
                        </span>

                        <span style="padding-right: 10px" v-if="requester.department_name">
                            <small><strong>{{t('Department')}}</strong></small> : <small
                                    v-text="requester.department_name"> </small>
                        </span>

                        <span style="padding-right: 10px" v-if="requester.job">
                            <small><strong>{{t('Position')}}</strong></small> : <small
                                    v-text="requester.job"> </small>
                        </span>

                        <span style="padding-right: 10px" v-if="requester.email">
                            <small><strong>{{t('Email')}}</strong></small> : <small v-text="requester.email"> </small>
                    </span>
                    </div>
                    {{--                    {{ Form::select('requester_id', App\User::requesterList()->prepend('Create for me', ''), null, ['class' => 'form-control select2']) }}--}}
                    {!! $errors->first('requester_id', '<div class="error-message">:message</div>') !!}


                </div>



            @endif
            <br>

            <div class="form-group form-group-sm {{$errors->has('subject')? 'has-error' : ''}}">
                {{ Form::label('subject', t('Subject'), ['class' => 'control-label']) }}
                {{ Form::text('subject', $category->name.(isset($subcategory->name) ? '  -  '.  $subcategory->name:'') , ['class' => 'form-control']) }}
                @if ($errors->has('subject'))
                    <div class="error-message">{{$errors->first('subject')}}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div id="CustomFields">
            @include('custom-fields.render', [
                'category' => App\Category::find(old('category_id')),
                'subcategory' => App\Category::find(old('subcategory_id')),
                'item' => App\Item::find(old('item_id'))
            ])
        </div>

    </div>

    <div class="row">
        <div class="col-sm-7">
            <div class="form-group form-group-sm {{$errors->has('description')? 'has-error' : ''}}">
                {{ Form::label('description', t('Description'), ['class' => 'control-label']) }}<strong
                        class="text-danger">*</strong>
                {{ Form::textarea('description', null, ['class' => 'form-control richeditor']) }}

                @if ($errors->has('description'))
                    <div class="error-message">{{$errors->first('description')}}</div>
                @endif
            </div>
        </div>
        <div class="col-sm-5">
            {{Form::hidden('category_id',$category->id)}}
            {{Form::hidden('subcategory_id',$subcategory->id ?? null)}}
            {{Form::hidden('item_id',$item->id ?? null)}}
            {{--<div class="form-group form-group-sm {{$errors->has('category_id')? 'has-error' : ''}}">--}}
                {{--{{ Form::label('category_id', t('Category'), ['class' => 'control-label']) }}--}}
                {{--{{ Form::select('category_id', App\Category::selection('Select Category'), request('category_id'), ['class' => 'form-control', 'v-model' => 'category','readonly'=>'readonly']) }}--}}
                {{--@if ($errors->has('category_id'))--}}
                    {{--<div class="error-message">{{$errors->first('category_id')}}</div>--}}
                {{--@endif--}}
            {{--</div>--}}

            {{--<div class="form-group form-group-sm {{$errors->has('subcategory')? 'has-error' : ''}}">--}}
                {{--{{ Form::label('subcategory_id', t('Subcategory'), ['class' => 'control-label']) }}--}}

                {{--<select class="form-control" name="subcategory_id" id="subcategory_id" v-model="subcategory"--}}
                        {{--readonly>--}}
                    {{--<option value="">Select Subcategory</option>--}}
                    {{--<option v-for="(subcategory, id) in subcategories" :value="subcategory.id">@{{subcategory.name}}--}}
                    {{--</option>--}}
                {{--</select>--}}
                {{--@if ($errors->has('subcategory_id'))--}}
                    {{--<div class="error-message">{{$errors->first('subcategory_id')}}</div>--}}
                {{--@endif--}}
            {{--</div>--}}

            {{--<div class="form-group form-group-sm {{$errors->has('item_id')? 'has-error' : ''}}">--}}
                {{--{{ Form::label('item_id', t('Item'), ['class' => 'control-label']) }}--}}
                {{--<select class="form-control" name="item_id" id="item_id" v-model="item" readonly>--}}
                    {{--<option value="">Select Item</option>--}}
                    {{--<option v-for="(item, id) in items" :value="item.id" v-text="item.name"></option>--}}
                {{--</select>--}}
                {{--@if ($errors->has('item_id'))--}}
                    {{--<div class="error-message">{{$errors->first('item_id')}}</div>--}}
                {{--@endif--}}
            {{--</div>--}}

        </div>
    </div>


    <div class="row">
        <div class="col-md-6">
            <attachments limit="5"></attachments>
        </div>
    </div>

    <hr class="form-divider">

    {{--<div class="col-sm-6">
        <div class="form-group form-group-sm {{$errors->has('impact_id')? 'has-error' : ''}}">
            {{ Form::label('impact_id', 'Impact', ['class' => 'control-label']) }}
            {{ Form::select('impact_id', App\Impact::selection('Select Impact'), null, ['class' => 'form-control']) }}
            @if ($errors->has('impact_id'))
                <div class="error-message">{{$errors->first('impact_id')}}</div>
            @endif
        </div>
        <div class="form-group form-group-sm {{$errors->has('priority_id')? 'has-error' : ''}}">
            {{ Form::label('priority_id', 'Priority', ['class' => 'control-label']) }}
            {{ Form::select('priority_id', App\Priority::selection('Select Priority'), null, ['class' => 'form-control']) }}
            @if ($errors->has('priority_id'))
                <div class="error-message">{{$errors->first('priority_id')}}</div>
            @endif
        </div>
    </div>--}}


    {{--@if (Auth::user()->isSupport())--}}
    {{--<div class="col-sm-6">--}}
    {{--<div class="form-group form-group-sm {{$errors->has('group_id')? 'has-error' : ''}}">--}}
    {{--{{ Form::label('group_id', t('Group'), ['class' => 'control-label']) }}--}}
    {{--{{ Form::select('group_id', App\Group::support()->selection('Select Group'), null, ['class' => 'form-control']) }}--}}
    {{--@if ($errors->has('group_id'))--}}
    {{--<div class="error-message">{{$errors->first('group')}}</div>--}}
    {{--@endif--}}
    {{--</div>--}}

    {{--<div class="form-group form-group-sm {{$errors->has('technician_id')? 'has-error' : ''}}">--}}
    {{--{{ Form::label('technician_id', t('Technician'), ['class' => 'control-label']) }}--}}
    {{--{{ Form::select('technician_id', App\User::technicians()->selection('Select Technician'), null, ['class' => 'form-control']) }}--}}
    {{--@if ($errors->has('technician_id'))--}}
    {{--<div class="error-message">{{$errors->first('technician_id')}}</div>--}}
    {{--@endif--}}
    {{--</div>--}}

    {{--<div class="form-group form-group-sm {{$errors->has('urgency_id')? 'has-error' : ''}}">--}}
    {{--{{ Form::label('urgency_id', t('Urgency'), ['class' => 'control-label']) }}--}}
    {{--{{ Form::select('urgency_id', App\Urgency::selection('Select Urgency'), null, ['class' => 'form-control']) }}--}}
    {{--@if ($errors->has('urgency_id'))--}}
    {{--<div class="error-message">{{$errors->first('urgency_id')}}</div>--}}
    {{--@endif--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--@endif--}}


    {{--<ul class="list-unstyled">--}}
    {{--<li class="text-danger">{{$error}}</li>--}}
    {{--</ul>--}}
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <button class="btn btn-success"><i class="fa fa-check"></i> {{t('Submit')}}</button>
        </div>
    </div>
</div>

