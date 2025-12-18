<?php

namespace App\Http\Controllers\Back;

//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//use App\Models\Warehouse;
use App\Models\WarehouseTransaction;
//use App\Models\WarehouseTransactionType;

class WarehouseTransactionsController extends Controller
{
    public function index()
    {
        $transactions = WarehouseTransaction::all();
        return view('pages.warehouseTransactions.modIndex', ['transactions' => $transactions]);
        
    }
}
