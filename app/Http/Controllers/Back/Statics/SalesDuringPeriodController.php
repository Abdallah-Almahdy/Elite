<?php

namespace App\Http\Controllers\Back\Statics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SalesDuringPeriodController extends Controller
{
    public function index()
    {
        Gate::authorize('reports.show');
        return view('pages.reports.sales-during-perid');
    }
}
