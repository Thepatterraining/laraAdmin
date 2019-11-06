<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Exceptions\ValidaterException;
use App\Http\Utils\Errors;
use App\Http\Utils\Success;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Errors, Success;

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * 验证器
     */
    protected function valid(array $rules = [], array $messages = []) {
        $rules = count($rules) > 0 ? $rules : $this->rules;
        $messages = count($messages) > 0 ? $messages : $this->messages;
        $validator = Validator::make($this->request->all(),$rules,$messages);
        if($validator->fails()){
            $arr = [
                'code' => config('error.valid_code'),
                'msg' => $validator->errors()->first(),
            ];
            throw new ValidaterException(json_encode($arr));
        }
    }
}
