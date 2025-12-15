<?php

namespace App\Livewire\WarehouseTransactions;

use App\Models\Barcode;
use App\Models\product;
use App\Models\ProductUnits;
use Livewire\Component;
use App\Models\Warehouse;
use App\Models\WarehouseTransactionType;
use Livewire\Attributes\Layout;
//use App\Models\warehouseTransactionType;
use App\Services\Dashboard\WarehouseTransactionsService;

class Create extends Component
{




    /*
    |--------------------------------------------------------------------------
    | Main Logic
    |--------------------------------------------------------------------------
    */

    public $products = [];
    public $bar_code;
    public $warehouse_id;
    public $new_warehouse_visability = false;
    public $transaction_type_id;
    public $new_warehouse_id;

    public function changeNewWarehouseVisibility()
    {
        if ($this->transaction_type_id == 4) {
            $this->new_warehouse_visability = true;
        } else {
            $this->new_warehouse_visability = false;
        }
    }

    public function searchByBarcode()
    {
        $this->validate([
            'bar_code' => 'required|string',
        ]);

        // Search for product by barcode
        $barcode = Barcode::where('code', $this->bar_code)->first();
        if (!$barcode) {
            session()->flash('error', 'لم يتم إيجاد المنتج');
            return;
        }
        $product = $barcode->productUnit->product;


        if ($product) {
            // Check if product already exists in the list
            $exists = collect($this->products)->firstWhere('id', $product->id);

            if (!$exists) {
                // Add product to the list with initial quantity of 1
                $this->products[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'bar_code' => $product->bar_code,
                    'current_stock' => $product->qnt, // Assuming this is warehouse stock
                    'quantity' => 1, // Default quantity
                ];
            }

            // Clear the barcode input
            $this->bar_code = '';
        } else {
            session()->flash('error', 'لم يتم إيجاد المنتج');
        }
    }

    public function removeProduct($index)
    {
        if (isset($this->products[$index])) {
            unset($this->products[$index]);
            // Reindex array
            $this->products = array_values($this->products);
        }
    }

    public function updatedBarCode()
    {
        // Automatically search when barcode is entered (scanned)
        if (!empty($this->bar_code)) {
            $this->searchByBarcode();
        }
    }

    public function updatedTransactionTypeId()
    {
        // Reset new_warehouse_id when transaction type changes
        if ($this->transaction_type_id != 4) {
            $this->new_warehouse_id = null;
        }

        // This will force Livewire to update the view
        $this->dispatch('transactionTypeChanged');
    }


    public function save(
        WarehouseTransactionsService $warehouseTransactionsService,
    ) {
       

        try {
            $warehouseTransactionsService->create(
                $this->products,
                $this->warehouse_id,
                $this->transaction_type_id,
                $this->new_warehouse_id
            );

            return redirect()
                ->route('warehouses_trasactions.index')
                ->with('success', 'تم إنشاء المستودع بنجاح');
        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'فشل حفظ الفاتورة: ' . $e->getMessage());
        }
    }



    #[Layout('admin.livewireLayout')]
    public function render()
    {
        $transactions_types = WarehouseTransactionType::all();
        $warehouses = Warehouse::where('is_active', 1)->get();

        return view('livewire.warehouse-transactions.create', [
            'transactions_types' => $transactions_types,
            'warehouses' => $warehouses,
        ]);
    }
}
