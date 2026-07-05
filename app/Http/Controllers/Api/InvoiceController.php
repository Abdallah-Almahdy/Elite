<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;
use App\Models\InviceConfig;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Product;
use App\Models\ProductUnits;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseProduct;
use App\Services\API\InvoiceService;
use App\Services\API\ShiftService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    protected InvoiceService $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    public function index()
    {

        return  InvoiceResource::collection(Invoice::all());
    }

    public function store(Request $request)
    {
        $data =  $request->validate([
            'address' => 'string',
            'payment_methods.*.key' => 'string|in:cash,credit_card,instapay,wallet,remaining',
            'payment_methods.*.amount' => 'required_with:payment_methods|numeric|min:0',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|integer|exists:products,id',
            'products.*.unit_conversion_factor' => 'required|numeric|min:0.0001',
            'products.*.quantity' => 'required|numeric|min:1',

        ]);

         return $this->invoiceService->create($data);

    }



    public function invoiceConfig()
    {
        $config = InviceConfig::where('type', 'system')->get();

        if (!$config) {
            return response()->json([
                'message' => 'No config found for this user.'
            ], 404);
        }

        return response()->json([
            'config' => $config,
            'mainWarehouse' => Warehouse::where('is_default', true)->first()->name
        ]);
    }

    public function userInvioceConfig(Request $request)
    {

        $config = User::findOrFail($request->get('user_id') ?? auth()->user()->id)->inviceConfig;

        if (!$config) {
            return response()->json([
                'message' => 'No config found for this user.'
            ], 404);
        }

        return response()->json([
            'config' => $config,
            'mainWarehouse' => Warehouse::where('is_default', true)->first()->name
        ]);
    }

    public function editInviceConfig(Request $request)
    {
        $request->validate([
            "type" => "string|in:user,system|required",
            'printerName' => 'string|nullable',
            'password' => 'string|nullable',
            'taxValue' => 'numeric|nullable',
            'defaultPaymentMethod' => 'string|in:cash,credit_card,instapay,wallet,remaining|nullable',
            'defaultInvoiceType' => 'string|in:take_away,hall,delvery|nullable',
            'applyTax' => 'boolean|nullable',
            'taxTypes' => 'string|in:%,pound|nullable',
            'user_id' => 'nullable|integer|exists:users,id',
            'allowedPaymentMethods' => 'array|nullable',
            'allowedPaymentMethods.*' => 'string|in:cash,credit_card,instapay,wallet,remaining',
            'allowedInvoiceTypes' => 'array|nullable',
            'allowedInvoiceTypes.*' => 'string|in:take_away,hall,delvery',
        ]);

        $user = User::findOrFail($request->user_id );



        $allowedPaymentMethods = collect($request->allowedPaymentMethods ?? []);

        if ($request->defaultPaymentMethod) {
            $allowedPaymentMethods->push($request->defaultPaymentMethod);
        }

        $allowedPaymentMethods = $allowedPaymentMethods
            ->unique()
            ->values()
            ->toArray();


        $allowedInvoiceTypes = collect($request->allowedInvoiceTypes ?? []);

        if ($request->defaultInvoiceType) {
            $allowedInvoiceTypes->push($request->defaultInvoiceType);
        }

        $allowedInvoiceTypes = $allowedInvoiceTypes
            ->unique()
            ->values()
            ->toArray();



        $data = [
            'printerName' => $request->printerName,
            'password' => $request->password,
            'taxValue' => $request->taxValue,
            'defaultPaymentMethod' => $request->defaultPaymentMethod,
            'defaultInvoiceType' => $request->defaultInvoiceType,
            'applyTax' => $request->applyTax,
            'taxTypes' => $request->taxTypes,
            'allowedPaymentMethods' => $allowedPaymentMethods,
            'allowedInvoiceTypes' => $allowedInvoiceTypes,
        ];

        if ($request->type === 'system') {
            $data['type'] = 'system';
            $config = InviceConfig::where('type', 'system')->first();
        } else {
            $data['type'] = 'user';
            $config = $user->inviceConfig;
        }

        if (!$config) {
            $config = $user->inviceConfig()->create($data);
        } else {
            $config->update(
            $data
            );


            $config->save();
        }

        return response()->json([
            'message' => 'Config updated successfully.',
            'config' => $config,
            'mainWarehouse' => optional(
                Warehouse::where('is_default', true)->first()
            )->name,
        ]);
    }

    public function checkPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = User::find(1);

        if ($user->inviceConfig && $user->inviceConfig->password === $request->password) {
            return response()->json([
                'message' => 'Password is correct.'
            ]);
        } else {
            return response()->json([
                'message' => 'Password is incorrect.'
            ], 400);
        }
    }
}
