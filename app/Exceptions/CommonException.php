<?php

namespace App\Exceptions;

use App\Http\Utils\Errors;
use Exception;


class CommonException extends Exception
{

    use Errors;

    //
    protected $code = '';

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {

        $this->code = $exception->getMessage();
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render()
    {
        return $this->error($this->code);
    }
}
