<div class="form-group col-md-6">
    {{ Form::label("filters[$key]", t($param["name"]), ['class' => 'control-label']) }}
    {{--@if($field['required'])<strong class="text-danger">*</strong> @endif--}}

    {{ Form::datetimeLocal("filters[$key]", null, ['class' => 'form-control cf', 'id' => $param["name"]]) }}
    {!! $errors->first($param["name"], '<div class="help-block">:message</div>') !!}
</div>