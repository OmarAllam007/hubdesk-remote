<?php

namespace App;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Session\TokenMismatchException;
use Throwable;
class ErrorLog extends Model
{
    static function log(Throwable $e)
    {

        $log = new ErrorLog();

//        $log->message = $e->getMessage();
        $log->message = self::getMessageBasedOnExceptionType($e);
        $log->file = $e->getFile();
        $log->line = $e->getLine();
        $log->code = $e->getCode();
        $log->trace = $e->getTraceAsString();
        $log->user_id = \Auth::id() ?: 0;

        $log->save();

        return $log;
    }

    function user()
    {
        return $this->belongsTo(User::class);
    }

    static function getMessageBasedOnExceptionType($e)
    {
        if ($e instanceof AuthorizationException) {
            return "You are not authorized to display this area.";
        } elseif ($e instanceof TokenMismatchException) {
            return "Your session time has been expired. Kindly login and try again.";
        }elseif($e instanceof  ModelNotFoundException){
            return "Model Not Found";
        }
        else {
            return $e->getMessage();
        }
    }
}
