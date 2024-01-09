<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;

trait Paginatable
{

    private static $defaultLimit = 10;
    private static $defaultPage = 1;

    protected function applyPaginate(Request $request)
    {
        $page = $request->input('page', self::$defaultPage);
        $limit = $request->input('limit', self::$defaultLimit);

        return (object) [
            'page' => $page,
            'limit' => $limit
        ];
    }
}

