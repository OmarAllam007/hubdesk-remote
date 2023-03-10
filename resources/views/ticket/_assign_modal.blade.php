{{Form::model($ticket, ['route' => ['ticket.reassign', $ticket], 'class' => 'modal fade', 'id' => 'AssignForm'])}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">{{t('Assign Ticket')}}</h4>
        </div>

        <div class="modal-body" id="TicketForm">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group {{$errors->first('group_id', 'has-error')}}">
                        {{Form::label('group_id', t('Group'), ['class' => 'control-label'])}}
                        {{Form::select('group_id', App\Group::active()->technician()->selection('Select Group'),null, ['class' => 'form-control','v-model'=>'group'])}}
                        {!! $errors->first('group_id', '<div class="help-block">:message</div>') !!}
                    </div>

                    <div class="form-group ">
                        {{ Form::label('technician_id', t('Technician'), ['class' => 'control-label']) }}
                        <select  class="form-control" name="technician_id" id="technician_id" v-model="technician_id">
                            <option value="">Select Technician</option>
                            <option v-for="tech in technicians" :value="tech.id"> @{{tech.name}}</option>
                        </select>
                        @if ($errors->has('technician_id'))
                            <div class="error-message">{{$errors->first('technician_id')}}</div>
                        @endif
                    </div>
                </div>

                @php
                    $categories = App\Category::active()->individual()->ticketType()->selection('Select Category');
                @endphp

                <div class="col-md-6">
                    <div class="form-group {{$errors->has('category_id')? 'has-error' : ''}}">
                        {{ Form::label('category_id', t('Category'), ['class' => 'control-label']) }}
                        {{ Form::select('category_id', $categories ,null, ['class' => 'form-control',  'v-model' => 'category']) }}
                        @if ($errors->has('category_id'))
                            <div class="error-message">{{$errors->first('category_id')}}</div>
                        @endif
                    </div>

                    <div class="form-group {{$errors->first('subcategory', 'has-error')}}">
                        {{ Form::label('subcategory_id', t('Subcategory'), ['class' => 'control-label']) }}

                        <select class="form-control" name="subcategory_id" id="subcategory_id" v-model="subcategory">
                            <option value="">Select Subcategory</option>
                            <option v-for="subcat in subcategories" :value="subcat.id" v-text="subcat.name"></option>
                        </select>

                        @if ($errors->has('subcategory_id'))
                            <div class="error-message">{{$errors->first('subcategory_id')}}</div>
                        @endif
                    </div>

                    <div class="form-group  {{$errors->has('item_id')? 'has-error' : ''}}" v-if="items.length">
                        {{ Form::label('item_id', t('Item'), ['class' => 'control-label']) }}
                        <select class="form-control" name="item_id" id="item_id" v-model="item">
                            <option value="">Select Item</option>
                            <option v-for="(item, id) in items" :value="item.id" v-text="item.name"></option>
                        </select>
                        @if ($errors->has('item_id'))
                            <div class="error-message">{{$errors->first('item_id')}}</div>
                        @endif
                    </div>

                    <div class="form-group  {{$errors->has('item_id')? 'has-error' : ''}}" v-if="subitems.length">
                        {{ Form::label('item_id', t('SubItem'), ['class' => 'control-label']) }}
                        <select class="form-control" name="subitem_id" id="subitem_id" v-model="subitem">
                            <option value="">Select SubItem</option>
                            <option v-for="(subitem, id) in subitems" :value="subitem.id" v-text="subitem.name"></option>
                        </select>
                        @if ($errors->has('subitem_id'))
                            <div class="error-message">{{$errors->first('subitem_id')}}</div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-mail-forward"></i> {{t('Assign')}}</button>
        </div>
    </div>
</div>
{{Form::close()}}