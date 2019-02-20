<div class="form-group {{$errors->first("cf.{$field['id']}", 'has-error')}}">
    {{ Form::label($name = "cf[{$field['id']}]", $field['name'], ['class' => 'control-label']) }}
    {{ Form::date("cf[{$field['name']}]", null, ['class' => 'form-control cf', 'id' => "cf-{$field['id']}"]) }}
    {!! $errors->first("cf.{$field['id']}", '<div class="help-block">:message</div>') !!}
</div>