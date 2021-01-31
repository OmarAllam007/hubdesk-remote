<?php

use App\Translation;

function flash($title = "", $message, $type)
{

    alert($title, $message, $type);
//    Session::flash('flash-message', $message);
//    Session::flash('flash-type', $type);
}


function can($ability, $object = null)
{
    return \Gate::allows($ability, $object);
}

function cannot($ability, $object)
{
    return \Gate::denies($ability, $object);
}

function ife($condition, $true, $false = null)
{
    if ($condition) {
        return $true;
    }

    return $false;
}

function t($word, $language = '')
{

    if (Auth::check()) {
        $language = $language ?: \Session::get('personalized-language' . \Auth::user()->id, \Config::get('app.locale'));
        $data = file_get_contents(public_path("json/$language.json"));
        $fileCollection = collect(json_decode($data, true));

        if ($word instanceof \Illuminate\Support\Collection) {
            $translate_array = collect();
            foreach ($word as $key => $item) {

                $word_exist = $fileCollection
                    ->where('word', $word)->where('language', $language)->first();
                if ($word_exist) {
                    if ($word_exist->translation != '') {
                        $translate_array->put($key, $word_exist->translation);
                    }

                } else {
                    $translate_array->put($key, $item);
                }
            }
            return $translate_array;
        }

        $word_exist = $fileCollection
            ->where('word',$word)->where('language', $language)->first();

        if ($word_exist) {
            if (isset($word_exist['translation']) && $word_exist['translation'] != '') {
                return $word_exist['translation'];
            }
            return $word_exist['word'];
        }
        return $word;

    }

    return $word;
}