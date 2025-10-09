<?php

namespace App\Http\Controllers\front\react\pos;

use App\Models\User;
use Inertia\Inertia;
use App\Models\order;
use App\Models\product;
use App\Models\subSection;
use App\Models\orderProduct;
use Illuminate\Http\Request;
use App\Jobs\CreatePrintJobs;
use App\Models\orderTracking;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CustomerInfo;

class IndexPosController extends Controller
{
    public function check(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);

        $customer = CustomerInfo::where('phonenum', $request->phone)->first();

        if ($customer == null) {
            return response()->json([
                'exists' => false,
            ]);
        }

        return response()->json([
            'exists' => true,
            'customer' => $customer,
        ]);
    }

    public function createCustomer(Request $request)
    {



        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:customer_infos,phonenum',
            'address' => 'nullable|string',
        ]);

        try {
            // Check if customer already exists (double check)
            $existingCustomer = CustomerInfo::where('phonenum', $request->phone)->first();
            if ($existingCustomer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer already exists',
                ], 400);
            }



            // Create user first
            $user = User::create([
                'name' => $request->name,
                'email' => $request->phone, // Use phone as unique email
                'password' => $request->phone . bcrypt('new123'), // Default password
            ]);

         

            // Create customer info
            $customer = CustomerInfo::create([
                'firstName' =>  $request->name,
                'lastName' => $request->name,
                'email' => $request->phone,
                'phonenum' => $request->phone,
                'user_id' => $user->id,
                'addressCountry' => $request->address ?? '',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Customer created successfully',
                'customer' => $customer,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating customer: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function index()
    {
        try {
            $sections = subSection::where('active', 1)
                ->select('id', 'name', 'photo', 'active')
                ->limit(18)
                ->get();

            // جيب المنتجات مع العلاقات Many-to-Many
            $products = product::with([
                'options.values', // لو في جدول values للـ options
                'addsOn', // العلاقة many-to-many
                'section' // العلاقة مع الـ section
            ])
                ->where('qnt', '>', 0)
                ->where('active', 1)
                ->select('id', 'name', 'price', 'photo', 'qnt', 'active', 'section_id') // غير section_id بدل category_id
                ->limit(200)
                ->get();

            // Format data for frontend
            $formattedProducts = $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'photo' => $product->photo,
                    'qnt' => $product->qnt,
                    'active' => $product->active,
                    'category_id' => $product->section_id, // استخدم section_id
                    'options' => $product->options->map(function ($option) {
                        return [
                            'id' => $option->id,
                            'name' => $option->name,
                            'values' => $option->values ?? [], // لو في جدول values
                        ];
                    }),
                    'adds_on' => $product->addsOn->map(function ($addon) {
                        return [
                            'id' => $addon->id,
                            'name' => $addon->name,
                            'price' => $addon->price,
                        ];
                    }),
                ];
            });

            return Inertia::render('POSInterface', [
                'categories' => $sections,
                'products' => $formattedProducts,
                'currency' => 'ج.م',
                'tax' => 14,
                'delivery_charge' => 0,
                'service_charge' => 0,
            ]);
        } catch (\Exception $e) {


            return Inertia::render('POSInterface', [
                'categories' => [],
                'products' => [],
                'currency' => 'ج.م',
                'tax' => 14,
                'delivery_charge' => 0,
                'service_charge' => 0,
            ]);
        }
    }


    public function createOrder(Request $request)
    {

        $request->validate([
            'customer_phone' => 'required|string',
            'customer_name' => 'required|string',
            'customer_address' => 'nullable|string',
            'order_type' => 'required|in:delivery,dine-in,takeaway',
            'payment_method' => 'required|in:cash,card',
            'cart_items' => 'required|array|min:1',
            'cart_items.' => 'required|exists:products,id',
            'cart_items.*.quantity' => 'required|integer|min:1',
            'cart_items.*.price' => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0'
        ]);
        dd($request->all());

        try {
            DB::beginTransaction();

            // 1. Get or create customer
            $customer = CustomerInfo::where('phonenum', $request->customer_phone)->first();

            if (!$customer) {
                // Create user first
                $user = User::create([
                    'name' => $request->customer_name,
                    'email' => $request->customer_phone,
                    'password' => bcrypt('default123')
                ]);

                // Create customer info
                $customer = CustomerInfo::create([
                    'firstName' => $request->customer_name,
                    'lastName' => '',
                    'email' => $request->customer_phone,
                    'phonenum' => $request->customer_phone,
                    'user_id' => $user->id,
                    'addressCountry' => $request->customer_address ?? ''
                ]);
            }

            // 2. Create order
            $order = order::create([
                'user_id' => $customer->user_id,
                'totalPrice' => $request->total_price,
                'address' => 1,
                'phoneNumber' => $request->customer_phone,
                'status' => 0, // pending
                'payment_method' => 1
            ]);

            // 3. Create order tracking
            orderTracking::create([
                'user_id' => $customer->user_id,
                'order_id' => $order->id,
                'status' => 1
            ]);

            // 4. Create order products
            foreach ($request->cart_items as $item) {
                orderProduct::create([
                    'product_id' => $item['id'],
                    'order_id' => $order->id,
                    'totalCount' => $item['quantity'],
                    'totalPrice' => $item['price'] * $item['quantity']
                ]);
            }

            // 5. Dispatch print jobs (if you have this)
            // CreatePrintJobs::dispatch($order);
            CreatePrintJobs::dispatch($order);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء الطلب بنجاح',
                'order_id' => $order->id
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في إنشاء الطلب:fffs ' . $e->getMessage()
            ], 500);
        }
    }
}
