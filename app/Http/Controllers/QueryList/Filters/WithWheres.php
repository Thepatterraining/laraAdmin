<?php

namespace App\Http\Controllers\QueryList\Filters;

interface WithWheres
{
    /**
     * @return array
     */
    public function getWheres($search): array;
}
