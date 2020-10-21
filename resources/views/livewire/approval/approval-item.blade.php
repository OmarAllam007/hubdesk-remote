<div>
    <div class="panel approval-panel " wire:key="{{$index}}">
        <div class="panel-heading">
            <div style="display: flex; justify-content: space-between">
                <div class="form-group form-group-sm {{$errors->has('approver_id')? 'has-error' : ''}}">
                    {{t('Send Approval to')}}
                    <label>
                        <select class="form-control "
                                wire:model="level.approver_id">
                            <option value="">{{t('Select Approver')}}</option>
                            @foreach($users as $user)
                                <option value="{{$user->id}}">
                                    {{$user->name}} ( {{$user->email}} )
                                </option>
                            @endforeach
                        </select>
                    </label>

                    @if ($errors->has('approver_id'))
                        <div class="error-message">{{$errors->first('approver_id')}}</div>
                    @endif
                </div>

                <a href="#" class="text-danger" type="button" wire:click.prevent="$emit('remove_level',{{$index}})">
                    <i class="fa fa-2x  fa-times"></i>
                </a>

            </div>
        </div>
        <hr>
        <div class="panel-body">
            <section class="table-container col-md-6">
                <table class="listing-table table-bordered">
                    <thead class="question-header">
                    <tr>
                        <th class="col-md-10">{{t('Questions')}}</th>
                        <th>
                            <a class="btn btn-sm btn-primary btn-xs" wire:click="addQuestion({{$index}})"
                               type="button"><i
                                        class="fa fa-plus-circle"></i></a>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($level['questions'] as $qKey=>$question)
                        <tr wire:key="{{$qKey}}">
                            <td class="col-md-10">
                                <input type="text" class="form-control" placeholder="{{t('Description')}}">
                            </td>
                            <td>
                                <a href="#" type="button" class="btn btn-danger btn-xs"
                                   wire:click="removeQuestion(level.id,{{$qKey}})">
                                    <i
                                            class="fa fa-times"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </section>
            <div class="form-group  {{$errors->has('content')? 'has-error' : ''}} col-md-6">
                <label>
                    {{t('Description')}}
                </label>
                <textarea class="form-control" wire:model="level.id.description"
                          name="content" cols="30" rows="8"></textarea>
                @if ($errors->has('content'))
                    <div class="error-message">{{$errors->first('content')}}</div>
                @endif
            </div>
        </div>
        <hr>
        <div class="panel-footer">
            <div style="display: flex; justify-content: space-between">
                <div class="form-group">
                    <input type="file" class="form-control input-xs" name="attachments[]" multiple>
                </div>

                @if ($index + 1 > 1)
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" wire:model="level.new_stage">
                            {{Form::checkbox('add_stage')}} {{t('Add as a new stage')}}
                        </label>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
