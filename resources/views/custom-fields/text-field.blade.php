 <div class="form-group {{$errors->first("cf.{$field['id']}", 'has-error')}}">
    {{ Form::label($name = "cf[{$field['id']}]", t(trim($field['name'])), ['class' => 'control-label']) }}
    @if($field['required'])<strong class="text-danger">*</strong> @endif
    {{ Form::text($name, null, ['class' => 'form-control cf', 'id' => "cf-{$field['id']}"]) }}
    {!! $errors->first("cf.{$field['id']}", '<div class="help-block">:message</div>') !!}
</div>