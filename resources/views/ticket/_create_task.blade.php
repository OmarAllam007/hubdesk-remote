<form method="POST"  class="modal fade" id="TaskForm" v-on:submit.prevent="changeOnSubmit" enctype="multipart/form-data" >
    {{csrf_field()}}{{method_field('POST')}}
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{t('Create Task')}}</h4>
            </div>


            <div class="modal-body" id="TaskForm">
                <div class="row">
                    <div class="col-md-6">
                        <div :class="{'form-group': true , 'has-error':errors.subject}">
                            {{Form::label('subject', t('Subject'), ['class' => 'control-label'])}}
                            {{Form::text('subject', null, ['class' => 'form-control','v-model'=>'subject'])}}
                            <span class="help-block" v-for="error in errors.subject">@{{error}}</span>
                        </div>

                        @if(auth()->user()->isSupport() && auth()->user()->reply_templates->count())
                                <div class="form-group">
                                    {{Form::label('template', t('Reply Template') , ['class' => 'control-label'])}}
                                    {{Form::select('template', auth()->user()->reply_templates->pluck('title','id')->prepend('Select Template',""), null, ['class' => 'form-control' , 'v-model'=>'template'])}}
                                    @if ($errors->has('template'))
                                        <div class="error-message">{{$errors->first('template')}}</div>
                                    @endif
                                </div>

                        @endif

                        <div :class="{'form-group': true , 'has-error':errors.description}">
                            {{Form::label('description', t('Description'), ['class' => 'control-label'])}}
                            {{Form::textarea('description', null, ['class' => 'form-control richeditor','v-model'=>'description'])}}
                            <span class="help-block" v-for="error in errors.description">@{{error}}</span>
                        </div>
                    </div>
                    <div class="col-md-6">

                        <div :class="{'form-group': true , 'has-error':errors.group}">
                            {{Form::label('group', t('Group'), ['class' => 'control-label'])}}
                            {{ Form::select('group_id', App\Group::active()->technician()->tasks()->selection('Select Group'),null, ['class' => 'form-control',  'v-model' => 'group','v-on:change'=>'loadTechnicians']) }}
                            <span class="help-block" v-for="error in errors.group">@{{error}}</span>
                        </div>

                        <div :class="{'form-group': true , 'has-error':errors.technician}">
                            {{Form::label('technician_id', t('Technician'), ['class' => 'control-label'])}}
                            <select class="form-control" name="technician_id" id="technician_id" v-model="technician">
                                <option value="">Select Technician</option>
                                <option v-for="tech in technicians" :value="tech.id"> @{{tech.name}}</option>
                            </select>
                        </div>
                        @php
                            $list = new \App\Http\Controllers\ListController();
                            $task_categories = $list->category(\App\KModel::TASK_TYPE);
                        @endphp

                        <div :class="{'form-group': true , 'has-error':errors.category}">
                            {{ Form::label('category_id', t('Category'), ['class' => 'control-label']) }}
                            <select name="category_id" id="category_id" class="form-control" v-model="category" @change = "loadSubcategory">
                                <option value="">{{t('Select Category')}}</option>
                                @foreach($task_categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            </select>

{{--                            {{ Form::select('category_id', $task_categories[0]->toArray() ,null, ['class' => 'form-control',  'v-model' => 'category','v-on:change'=>'loadSubcategory']) }}--}}
                            <span class="help-block" v-for="error in errors.category">@{{error}}</span>
                        </div>

                        <div class="form-group">
                            {{ Form::label('subcategory_id', t('Subcategory'), ['class' => 'control-label']) }}
                            <select class="form-control" name="subcategory_id" id="subcategory_id" v-model="subcategory"
                                    @change="loadItems">
                                <option value="">Select Subcategory</option>
                                <option v-for="subcat in subcategories" :value="subcat.id"> @{{subcat.name}}</option>
                            </select>
                        </div>

                        <div class="form-group">
                            {{ Form::label('item_id', t('Item'), ['class' => 'control-label']) }}
                            <select class="form-control" name="item_id" id="item_id" v-model="item" @change="loadCustomFields">
                                <option value="">Select Item</option>
                                <option v-for="(item, id) in items" :value="item.id" v-text="item.name"></option>
                            </select>
                        </div>

                        <div :class="{'form-group': true , 'has-error':errors.status}" v-show="edit">
                            {{ Form::label('status_id', t('Status'), ['class' => 'control-label']) }}
                            {{ Form::select('status_id', App\Status::selection('Select Status'),null, ['class' => 'form-control',  'v-model' => 'status']) }}
                            <span class="help-block" v-for="error in errors.status">@{{error}}</span>
                        </div>

                    </div>

                    <div>
                        <div id="CustomFields">
                            @include('custom-fields.render', [
                                'category' => App\Category::find(old('category_id')),
                                'subcategory' => App\Category::find(old('subcategory_id')),
                                'item' => App\Item::find(old('item_id'))
                            ])
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <table class="listing-table table-condensed">
                            <thead>
                            <tr>
                                <th>{{t('Attachments')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="col-md-10">
                                    <input type="file" class="form-control input-xs" ref="attachments" name="attachments[]" id="attachments" @change="handleFiles" multiple>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>



            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" :disabled="!can_submit" >
                    <i class="fa fa-save" v-show="!saving"></i>
                    <i class="fa fa-spinner fa-spin" v-show="saving"></i>
                    {{t('Save')}}
                </button>
            </div>
        </div>
    </div>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
</form>

