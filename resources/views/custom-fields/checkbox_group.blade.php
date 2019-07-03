<?php
$options = collect(json_decode($field['options'], true));
$options = $options->combine($options);
?>

<div class="form-group {{$errors->first($name = "cf[{$field['id']}]", 'has-error')}}">
    <label>{{$field['name']}}</label>
    <div class="checkbox">
        {{--            {{ Form::hidden($name, 0) }}--}}
        @foreach($options as $key=>$value)
            <label>
                <input name="{{$name}}[]" type="checkbox" value="{{$value}}" class="custom-radio"> {{ t($value) }}
            </label>
        @endforeach


    </div>
    {!! $errors->first($name, '<div class="help-block">:message</div>') !!}
</div>