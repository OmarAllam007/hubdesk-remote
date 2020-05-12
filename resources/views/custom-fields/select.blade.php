<?php

use League\OAuth1\Client\Server\User;
use function foo\func;

$options = collect();

if (isset($field['options']['model'])) {
    $options = app($field['options']['model'])
        ->select('id',DB::raw(" CONCAT(" . implode(" ,", $field['options']['keys']) . ") as 'option'"))
        ->pluck('option','option')->filter();

} else {
    $options = collect($field['options'], true);
    $options = $options->combine($options);
}

$options = $options->prepend(t('Select Value'), '');
?>

<div class="form-group {{$errors->first("cf.{$field['id']}", 'has-error')}}">
    {{ Form::label($name = "cf[{$field['id']}]", t($field['name']), ['class' => 'control-label']) }}
    @if($field['required'])<strong class="text-danger">*</strong> @endif

    {{ Form::select($name, t($options) , null, ['class' => 'form-control select2 cf', 'id' => "cf-{$field['id']}"]) }}
    {{--    {!! $errors->first("cf.{$field['id']}", '<div class="error-message">:message</div>') !!}--}}
    {{--    @if ($errors->has('cf.'.$field['id'].''))--}}
    <div class="error-message">{{$errors->first('cf.'.$field['id'].'')}}</div>
    {{--@endif--}}
</div>