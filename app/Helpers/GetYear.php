<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class GetYear
{
    public static function getYear()
    {
        $year = request()->cookie('selected_year');

        $data = DB::table('financial_years')
            ->where('id', $year)
            ->select('id', 'from_date', 'to_date', 'display_year')
            ->first();

        if ($data) {
            return $data;
        } else {
            return null;
        }
    }
}
