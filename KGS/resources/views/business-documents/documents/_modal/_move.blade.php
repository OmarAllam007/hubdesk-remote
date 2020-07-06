{{Form::model(null, ['route' => ['kgs.document.assign', $folder], 'class' => 'modal fade', 'id' => 'MoveForm'])}}
<div class="modal-dialog ">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">{{t('Move Document')}}</h4>
        </div>
        <div class="modal-body" id="TicketForm">
            <div class="row">
                <div class="col-md-12">
                    <input type="hidden" id="document_id"  name="document_id">

                    <div class="form-group {{$errors->first('business_unit', 'has-error')}}">
                        {{Form::label('business_unit', t('Business Unit'), ['class' => 'control-label'])}}
                        {{Form::select('business_unit', App\BusinessUnit::kgs()->pluck('name','id'),null, ['class' => 'form-control','v-model'=>'business_unit'])}}
                        {!! $errors->first('business_unit', '<div class="help-block">:message</div>') !!}
                    </div>

                    <div class="form-group ">
                        {{ Form::label('folder_id', t('Folder'), ['class' => 'control-label']) }}
                        <select  class="form-control" name="folder_id" id="folder" v-model="folder">
                            <option value="">Select Folder</option>
                            <option v-for="folder in folders" :value="folder.id"> @{{folder.name}}</option>
                        </select>
                        @if ($errors->has('folder'))
                            <div class="error-message">{{$errors->first('folder')}}</div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-mail-forward"></i> {{t('Move')}}</button>
        </div>
    </div>
</div>
{{Form::close()}}