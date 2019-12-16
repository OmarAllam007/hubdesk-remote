{{ csrf_field() }}
<div id="TicketForm">
    <div class="form-group form-group-sm {{$errors->has('subject')? 'has-error' : ''}}">
        {{ Form::label('subject', t('Subject'), ['class' => 'control-label']) }}
        {{ Form::text('subject', null, ['class' => 'form-control']) }}
        @if ($errors->has('subject'))
            <div class="error-message">{{$errors->first('subject')}}</div>
        @endif
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group form-group-sm {{$errors->has('description')? 'has-error' : ''}}">
                {{ Form::label('description', t('Description'), ['class' => 'control-label']) }}
                {{ Form::textarea('description', null, ['class' => 'form-control richeditor']) }}
                @if ($errors->has('description'))
                    <div class="error-message">{{$errors->first('description')}}</div>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{$errors->first('group_id', 'has-error')}}">
                {{Form::label('group_id', t('Group'), ['class' => 'control-label'])}}
                {{Form::select('group_id', App\Group::selection('Select Group'),isset($task) ? $task->group_id : null ,
                 ['class' => 'form-control','v-model'=>'group'])}}
                {!! $errors->first('group_id', '<div class="help-block">:message</div>') !!}
            </div>

            <div class="form-group ">
                {{ Form::label('technician_id', t('Technician'), ['class' => 'control-label']) }}
                <select class="form-control" name="technician_id" id="technician_id" v-model="technician_id">
                    <option value="">Select Technician</option>
                    <option v-for="tech in technicians" :value="tech.id"> @{{tech.name}}</option>
                </select>
                @if ($errors->has('technician_id'))
                    <div class="error-message">{{$errors->first('technician_id')}}</div>
                @endif
            </div>
        </div>
        @php
            $list = new \App\Http\Controllers\ListController();
            $task_categories = $list->category(\App\KModel::TASK_TYPE);
        @endphp

        <div class="col-md-6">
            <div class="form-group {{$errors->has('category_id')? 'has-error' : ''}}">
                {{ Form::label('category_id', t('Category'), ['class' => 'control-label']) }}
                <select name="category_id" id="category_id" class="form-control" v-model="category"
                        v-on:change="loadSubcategory">
                    <option value="">{{t('Select Category')}}</option>
                    @foreach($task_categories as $category)
                        <option value="{{$category->id}}">{{$category->name}}</option>
                    @endforeach
                </select>
                @if ($errors->has('category_id'))
                    <div class="error-message">{{$errors->first('category_id')}}</div>
                @endif
            </div>

            <div class="form-group {{$errors->first('subcategory', 'has-error')}}">
                {{ Form::label('subcategory_id', t('Subcategory'), ['class' => 'control-label']) }}

                <select class="form-control" name="subcategory_id" id="subcategory_id" v-model="subcategory">
                    <option value="">{{t('Select Subcategory')}}</option>
                    <option v-for="subcat in subcategories" :value="subcat.id" v-text="subcat.name"></option>
                </select>

                @if ($errors->has('subcategory_id'))
                    <div class="error-message">{{$errors->first('subcategory_id')}}</div>
                @endif
            </div>

            <div class="form-group  {{$errors->has('item_id')? 'has-error' : ''}}">
                {{ Form::label('item_id', t('Item'), ['class' => 'control-label']) }}
                <select class="form-control" name="item_id" id="item_id" v-model="item">
                    <option value="">{{t('Select Item')}}</option>
                    <option v-for="(item, id) in items" :value="item.id" v-text="item.name"></option>
                </select>
                @if ($errors->has('item_id'))
                    <div class="error-message">{{$errors->first('item_id')}}</div>
                @endif
            </div>
        </div>
    </div>

{{--    <div class="row">--}}
        <div class="col-md-6">
            <div class="form-group">
                <label for="attachments">{{t('Attachments')}}</label>
                <input type="file" class="form-control input-xs"  name="attachments[]" id="attachments"
                       multiple>
            </div>
        </div>
        <div class="col-md-6">
            <div id="CustomFields" style="padding: 0">
                @include('custom-fields.render')
            </div>
        </div>
{{--    </div>--}}

</div>

<div class="form-group">
    <button class="btn btn-success"><i class="fa fa-check"></i> {{t('Submit')}}</button>
</div>
</div>
