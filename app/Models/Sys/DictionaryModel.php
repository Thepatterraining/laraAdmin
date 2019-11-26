<?php

namespace App\Models\Sys;

use App\Models\BaseModel;

class DictionaryModel extends BaseModel
{
    protected $table = 'sys_dictionary';

    /**
     * 获取字典详细列表
     */
    static function getDetailsByCode(string $type) : array {
        $details = self::where('type', $type)->get();
        foreach($details as $detail) {
            $temp = [];
            $temp['code'] = strval($detail->code);
            $temp['name'] = $detail->name;
            $res[strval($detail->code)] = $temp;
        }
        return $res;
    }

}
