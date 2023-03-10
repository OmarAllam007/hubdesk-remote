<?php

use League\OAuth1\Client\Server\User;
use function foo\func;

$options = collect();


$data = json_decode($field['options'], true);

if (isset($data["model"])) {
    $options = app($data['model'])
        ->select('id', DB::raw(" CONCAT(" . implode(" ,", $data["keys"]) . ") as 'option'"))
        ->pluck('option', 'option')->filter();
} else {
    $options = collect(json_decode($field['options']), true);
    $options = $options->combine($options);
}

$options = $options->prepend(t('Select Value'), '');
?>

<div class="form-group {{$errors->first("cf.{$field['id']}", 'has-error')}}">
    {{ Form::label($name = "cf[{$field['id']}]", t($field['name']), ['class' => 'control-label']) }}
    @if($field['required'])<strong class="text-danger">*</strong> @endif
{{--<!-- to be configured->--}}
    @if($options->count() >= 20)
        {{ Form::select($name, $options , null, ['class' => 'form-control select2 cf', 'id' => "cf-{$field['id']}"]) }}
    @else
        {{ Form::select($name, t($options) , null, ['class' => 'form-control select2 cf', 'id' => "cf-{$field['id']}"]) }}
    @endif
    {{--    {!! $errors->first("cf.{$field['id']}", '<div class="error-message">:message</div>') !!}--}}
    {{--    @if ($errors->has('cf.'.$field['id'].''))--}}
    <div class="error-message">{{$errors->first('cf.'.$field['id'].'')}}</div>
    {{--@endif--}}
</div>