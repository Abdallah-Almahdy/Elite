<?php

namespace App\Livewire\Statices;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Rating;
use App\Models\Section;
use App\Models\SubSection;
use Livewire\Component;
use Livewire\Attributes\Layout;

class StaticesController extends Component
{
    public $orders;
    public $users;
    public $successOrders;
    public $successOrdersTotal = 0;
    public $faildOrders;
    public $productsCount;
    public $sectionsCount;
    public $subSections;
    public $subSectionsCount;
    public $ratingsCount;
    public $filter = 'all'; // Default filter type
    public $filteredSubSections = [];
    public $startDate;
    public $endDate;

    #[Layout('admin.livewireLayout')]
    public function mount()
    {
        // Set default date range to the last 30 days
        $this->startDate = Carbon::now()->subYears(5)->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
    }

    public function applyFilter($value)
    {
        if ($value === 'most') {
            $this->filter = 'most'; // Highest total
        } elseif ($value === 'least') {
            $this->filter = 'least'; // Lowest total
        } else {
            $this->filter = 'all'; // No sorting
        }
    }

    public function updateMetricsByDate()
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        // Orders and users within the date range
        $this->orders = Order::whereBetween('created_at', [$start, $end])->count();
        $this->users = User::whereBetween('created_at', [$start, $end])->count();

        // Successful orders and their total value
        $orders = Order::where('status', 1)
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $this->successOrders = $orders->count();
        $this->successOrdersTotal = $orders->sum('totalPrice');

        // Failed orders count
        $this->faildOrders = Order::where('status', 2)
            ->whereBetween('created_at', [$start, $end])
            ->count();

        // Products and sections counts
        $this->productsCount = Product::whereBetween('created_at', [$start, $end])->count();
        $this->sectionsCount = Section::whereBetween('created_at', [$start, $end])->count();
        $this->subSectionsCount = SubSection::whereBetween('created_at', [$start, $end])->count();
        $this->ratingsCount = Rating::whereBetween('created_at', [$start, $end])->count();

        // Subsections metrics
        $this->subSections = SubSection::all();
        foreach ($this->subSections as $subSection) {
            $products = Product::where('section_id', $subSection->id)
                ->whereBetween('created_at', [$start, $end])
                ->get();

            $productsCount = $products->count();
            $orderCount = 0;
            $orderTotal = 0;

            if ($products->isNotEmpty()) {
                $orders = Order::whereHas('orderProducts', function ($query) use ($products) {
                    $query->whereIn('product_id', $products->pluck('id'));
                })->whereBetween('created_at', [$start, $end])
                    ->get();

                $orderCount = $orders->count();
                $orderTotal = $orders->sum('totalPrice');
            }

            $subSection->productsCount = $productsCount;
            $subSection->orderCount = $orderCount;
            $subSection->orderTotal = $orderTotal;
        }

        // Filter subsections
        if ($this->filter === 'most') {
            $this->subSections = $this->subSections->sortByDesc('orderTotal');
        } elseif ($this->filter === 'least') {
            $this->subSections = $this->subSections->sortBy('orderTotal');
        }

        $this->filteredSubSections = $this->subSections;
    }

    public function render()
    {
        $this->updateMetricsByDate();

        return view('livewire.statices.statices-controller');
    }
}

// namespace App\Livewire\Statices;

// use Carbon\Carbon;
// use App\Models\User;
// use App\Models\order;
// use App\Models\product;
// use Livewire\Component;
// use App\Models\subSection;
// use Livewire\Attributes\Layout;

// class StaticesController extends Component
// {
//     public $orders;
//     public $users;
//     public $successOrders;
//     public $successOrdersTotal = 0;
//     public $faildOrders;
//     public $productsCount;
//     public $sectionsCount;
//     public $subSections;
//     public $subSectionsCount;
//     public $filter = 'all'; // Default filter type
//     public $filteredSubSections = [];
//     public $startDate;
//     public $endDate;
//     #[Layout('admin.livewireLayout')]
//     public function mount()
//     {
//         // Set default date range to the last 30 days
//         $this->startDate = Carbon::now()->subDays(30)->format('Y-m-d');
//         $this->endDate = Carbon::now()->format('Y-m-d');
//     }


//     public function applyFilter($value)
//     {
//         if ($value === 'most') {
//             $this->filter = 'most'; // اعلي
//         } elseif ($value === 'least') {
//             $this->filter = 'least'; // اعلي
//         } else {
//             // No sorting, show all
//             $this->filter = 'all'; // اعلي
//         }
//     }


//     public function render()
//     {
//         // dd($this->startDate);
//         $start = Carbon::parse($this->startDate)->startOfDay();
//         $end = Carbon::parse($this->endDate)->endOfDay();
//         $this->successOrdersTotal = 0;
//         // Count total orders and users
//         // $this->orders = Order::count();
//         // if ($this->startDate & $this->endDate) {
//         $this->orders = Order::whereBetween('created_at', [$start, $end])->count();
//         // }
//         $this->users = User::count() - 70;

//         // Get successful orders and calculate total
//         $orders = Order::where('status', 1)->get();
//         if ($this->startDate & $this->endDate) {
//             $orders = Order::where('status', 1)
//                 ->whereBetween('created_at', [$start, $end])
//                 ->get();
//         }

//         $this->successOrders = $orders->count();
//         foreach ($orders as $order) {
//             $this->successOrdersTotal += $order->totalPrice;
//         }

//         // Count failed orders
//         $this->faildOrders = Order::where('status', 2)->count();

//         // Count products and sections
//         $this->productsCount = Product::count();
//         $this->sectionsCount = Section::count();
//         $this->$this->subSectionsCount = $this->subSections->count(); = SubSection::all();
//         $this->subSectionsCount = $this->subSections->count();

//         // Update subsection product and order counts
//         foreach ($this->subSections as $subSection) {
//             // Get products related to the subsection
//             $products = Product::where('section_id', $subSection->id)->get();
//             $productsCount = $products->count();

//             // Initialize order count and total order value
//             $orderCount = 0;
//             $orderTotal = 0;

//             if ($products->isNotEmpty()) {
//                 // Count the number of orders that contain these products
//                 $orders = Order::whereHas('orderProducts', function ($query) use ($products) {
//                     $query->whereIn('product_id', $products->pluck('id'));
//                 })->get();
//                 if ($this->startDate & $this->endDate) {
//                     $orders = Order::whereHas('orderProducts', function ($query) use ($products) {
//                         $query->whereIn('product_id', $products->pluck('id'));
//                     })->whereBetween('created_at', [$start, $end])->get();
//                 }


//                 // Calculate the total value of the orders
//                 $orderCount = $orders->count();

//                 foreach ($orders as $order) {
//                     $orderTotal += $order->totalPrice; // Assuming `totalPrice` is the total amount for each order
//                 }
//             }

//             // Assign the counts and total order value to the subsection
//             $subSection->productsCount = $productsCount;
//             $subSection->orderCount = $orderCount;
//             $subSection->orderTotal = $orderTotal; // Add the total price of orders to the subsection
//         }


//         if ($this->filter === 'most') {
//             $this->subSections = $this->subSections->sortByDesc('orderTotal'); // اقل
//         } elseif ($this->filter === 'least') {
//             $this->subSections = $this->subSections->sortBy('orderTotal'); // اعلي
//         } else {
//             // No sorting, show all
//             $this->filteredSubSections = $this->subSections;
//         }

//         return view('livewire.statices.statices-controller');
//     }
// }
