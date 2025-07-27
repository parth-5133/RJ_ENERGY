<?php

namespace App\Helpers;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class FinancialYearService
{
    public static function getCurrentFinancialYear()
    {
        $companiesId = GetCompanyId::GetCompanyId();

        return Cache::remember('current_financial_year', 10, function () use ($companiesId) {
            return DB::table('financial_years')
                ->where('is_currentYear', '=', value: 1)
                ->where('is_active', '=', value: 1)
                ->where('financial_years.company_id', $companiesId)
                ->first();
        });
    }
}
