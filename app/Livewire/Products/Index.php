<?php

namespace App\Livewire\Products;

use App\Models\Product;
use App\Models\Favorit;
use App\Models\SubSection;
use App\Models\OrderProduct;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads; // Import WithFileUploads for file handling

class Index extends Component
{
    use WithPagination, WithFileUploads; // Add WithFileUploads to handle file uploads

    protected $paginationTheme = 'bootstrap';
    public $selectedProducts = []; // Store selected product IDs
    public $newSectionId; // New section ID for bulk update
    public $perPage = 20;
    public $myProducts = [];
    public $viewLinks = true;
    public $photo;  // Store the uploaded photo
    public $productIdToUpload;  // Store the product ID for photo upload
    public $search = '';  //  a property for search
    public $showZeroStock = false; // Property to toggle between showing all products or only those with 0 stock
    public $showBulkActions = false;
    // Delete selected products
    public function toggleBulkActions()
    {
        $this->showBulkActions = !$this->showBulkActions;
    }
    public function deleteSelected()
    {
        if (empty($this->selectedProducts)) {
            session()->flash('error', 'No products selected!');
            return;
        }

        // Check if any of the selected products are in the OrderProduct table
        $productsInOrders = OrderProduct::whereIn('product_id', $this->selectedProducts)->exists();

        if ($productsInOrders) {
            session()->flash('error', 'لا يمكن حذف المنتجات المحددة بسبب وجودها في الطلبات');
            return;
        }

        // Delete related Favorit for selected products
        Favorit::whereIn('product_id', $this->selectedProducts)->delete();

        // Delete the selected products
        Product::whereIn('id', $this->selectedProducts)->delete();

        // Clear the selected products after deletion
        $this->selectedProducts = [];

        session()->flash('success', 'Selected products deleted successfully!');
    }


    public function filterZeroStock()
    {
        $this->showZeroStock = !$this->showZeroStock; // Toggle the filter
    }

    // Update section for selected products
    public function updateSection()
    {
        if (empty($this->selectedProducts) || !$this->newSectionId) {
            session()->flash('error', 'Please select products and a new section!');
            return;
        }

        // Update the section_id for the selected products
        Product::whereIn('id', $this->selectedProducts)
            ->update(['section_id' => $this->newSectionId]);

        // Clear selected products after update
        $this->selectedProducts = [];
        $this->newSectionId = null; // Reset the new section ID

        session()->flash('success', __('lan.products_moved_to_section'));
    }

    // Toggle select/deselect all products
    public function toggleSelectAll()
    {
        if (count($this->selectedProducts) === Product::count()) {
            $this->selectedProducts = []; // Deselect all if all are selected
        } else {
            $this->selectedProducts = Product::pluck('id')->toArray(); // Select all
        }
    }

    public function searchProduct()
    {
        $this->myProducts = Product::where('name', 'like', '%' . $this->search . '%')  // Filter products by name
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function resetViewLinks()
    {
        $this->viewLinks = false;
        $this->search    = '';
        // optional QoL: go back to page 1
        $this->resetPage();
    }

    public function changeAvailability($productId, $quantity)
    {
        // Find the product by ID
        $product = Product::find($productId);

        if ($product) {
            // Update the product quantity
            $product->qnt = $quantity;
            $product->save();

            // Provide success feedback
            session()->flash('success', 'تم تحديث حالة المنتج بنجاح!');
        } else {
            session()->flash('error', 'المنتج غير موجود!');
        }
    }

    // Handle file upload when user selects a photo
    public function uploadPhoto($productId)
    {
        $this->validate([
            'photo' => 'required|image|max:10240', // Ensure it's an image and size < 10MB
        ]);

        $product = Product::find($productId);

        if ($product) {
            // Save the uploaded file in the 'products' folder in public disk
            $photoPath = $this->photo->store('products', 'public');

            // Update the product's photo field
            $product->photo = $photoPath;
            $product->save();

            // Reset photo input after uploading
            $this->photo = null;

            session()->flash('success', 'Product photo uploaded successfully!');
        } else {
            session()->flash('error', 'Product not found!');
        }
    }

    public function render()
    {
        $sections = SubSection::all(); // Get all sections for the select dropdown

        if ($this->search === '') {
            $products = Product::orderBy('created_at', 'desc')
                ->paginate($this->perPage);
            $this->viewLinks = true;
        } else {
            $products = $this->myProducts;
            $this->viewLinks = false;
        };

        if ($this->showZeroStock) {
            $products = Product::where('qnt', 0)->orderBy('created_at', 'desc')->paginate($this->perPage);
            $this->viewLinks = false;
        } else {
            if ($this->search === '') {
                $products = Product::orderBy('created_at', 'desc')->paginate($this->perPage);
                $this->viewLinks = true;
            } else {
                $products = $this->myProducts;
                $this->viewLinks = false;
            }
        }

        return view('livewire.products.Index', [
            'products' => $products,
            'sections' => $sections,

        ]);
    }
}
