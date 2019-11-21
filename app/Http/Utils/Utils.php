<?php

namespace App\Http\Utils;

class Utils {

    static function getUuid($name = '') {
        $timeHash = hash('sha256', config('jwt.WL_UUID_KEY') . microtime());
        $hash = hash('sha256', $name . $timeHash . config('jwt.WL_UUID_KEY') . microtime());
        $uid =  \Uuid::generate(5, $hash . microtime(), \Uuid::NS_DNS);
        return implode('', explode('-', $uid->string));
    }
}
