<?php

namespace App\Http\Controllers\Back\Statics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SalesDuringPeriodController extends Controller
{
    public function index()
    {
        return view('pages.reports.sales-during-perid');
    }
}
