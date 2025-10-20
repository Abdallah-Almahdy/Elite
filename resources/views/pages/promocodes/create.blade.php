@extends('admin.app')

@section('content')
    <div class="card card-primary shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0 text-center">
                <i class="fas fa-tag me-2"></i>إضافة كود خصم جديد
            </h4>
        </div>
        <form method="POST" action="{{ route('promocodes.store') }}" role="form">
            @csrf
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>{{ session('success') }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- الصف الأول: الكود وفئة الترويج -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="code" class="form-label fw-bold">
                                <i class="fas fa-code me-1"></i>كود الخصم
                            </label>
                            <input type="text" class="form-control form-control-lg" id="code" name="code"
                                value="{{ old('code') }}" placeholder="أدخل كود الخصم" required>
                            @error('code')
                                <div class="text-danger small mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="promo_cat" class="form-label fw-bold">
                                <i class="fas fa-users me-1"></i>فئة الترويج
                            </label>
                            <select class="form-select form-select-lg" id="promo_cat" name="promo_cat" required>
                                <option value="0">اختر فئة الترويج...</option>
                                <option value="user" {{ old('promo_cat') == 'user' ? 'selected' : '' }}>مستخدم محدد</option>
                                <option value="all" {{ old('promo_cat') == 'all' ? 'selected' : '' }}>جميع المستخدمين</option>
                            </select>
                            @error('promo_cat')
                                <div class="text-danger small mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- الصف الثاني: النوع وحالة الكود مع العروض -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group" id="type_group">
                            <label for="type" class="form-label fw-bold">
                                <i class="fas fa-infinity me-1"></i>نوع الكود
                            </label>
                            <select class="form-select form-select-lg" id="type" name="type">
                                <option value="">اختر نوع الكود...</option>
                                <option value="limited" {{ old('type') == 'limited' ? 'selected' : '' }}>محدود الاستخدام</option>
                                <option value="unlimited" {{ old('type') == 'unlimited' ? 'selected' : '' }}>غير محدود الاستخدام</option>
                            </select>
                            @error('type')
                                <div class="text-danger small mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="check_offer_rate" class="form-label fw-bold">
                                <i class="fas fa-percentage me-1"></i>التطبيق مع العروض
                            </label>
                            <select class="form-select form-select-lg" id="check_offer_rate" name="check_offer_rate">
                                <option value="9">اختر حالة التطبيق...</option>
                                <option value="1" {{ old('check_offer_rate') == '1' ? 'selected' : '' }}>مفعل مع العروض</option>
                                <option value="0" {{ old('check_offer_rate') == '0' ? 'selected' : '' }}>غير مفعل مع العروض</option>
                            </select>
                            @error('check_offer_rate')
                                <div class="text-danger small mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- حد المستخدمين (يظهر عند الاختيار المناسب) -->
                <div class="row mb-3" id="users_limit_group">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="users_limit" class="form-label fw-bold">
                                <i class="fas fa-user-friends me-1"></i>الحد الأقصى للمستخدمين
                            </label>
                            <input type="number" class="form-control form-control-lg" id="users_limit" name="users_limit"
                                value="{{ old('users_limit') }}" placeholder="أدخل عدد المستخدمين المسموح به">
                            @error('users_limit')
                                <div class="text-danger small mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- اختيار العميل (يظهر عند اختيار "مستخدم") -->
                <div class="row mb-3" id="user_group">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="user_id" class="form-label fw-bold">
                                <i class="fas fa-user me-1"></i>اختيار العميل
                            </label>
                            <select name="user_id" id="user_id" class="form-select form-select-lg">
                                <option value="">اختر العميل...</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->conc_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="text-danger small mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i>الرجاء اختيار المستخدم
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- الحد الأدنى للطلب -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="min_order_value" class="form-label fw-bold">
                                <i class="fas fa-shopping-cart me-1"></i>الحد الأدنى للطلب
                            </label>
                            <input type="number" class="form-control form-control-lg" id="min_order_value" name="min_order_value"
                                value="{{ old('min_order_value') }}" placeholder="أدخل الحد الأدنى لقيمة الطلب">
                            @error('min_order_value')
                                <div class="text-danger small mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- الصف الثالث: نوع الخصم وتاريخ الانتهاء -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="discount_type" class="form-label fw-bold">
                                <i class="fas fa-percent me-1"></i>نوع الخصم
                            </label>
                            <select class="form-select form-select-lg" id="discount_type" name="discount_type" required>
                                <option value="0">اختر نوع الخصم...</option>
                                <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>نسبة مئوية %</option>
                                <option value="cash" {{ old('discount_type') == 'cash' ? 'selected' : '' }}>قيمة نقدية</option>
                            </select>
                            @error('discount_type')
                                <div class="text-danger small mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="expiry_date" class="form-label fw-bold">
                                <i class="fas fa-calendar-alt me-1"></i>تاريخ الانتهاء
                            </label>
                            <input type="date" class="form-control form-control-lg" id="expiry_date" name="expiry_date"
                                value="{{ old('expiry_date') }}">
                            @error('expiry_date')
                                <div class="text-danger small mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- قيمة الخصم (تظهر حسب النوع) -->
                <div class="row mb-3" id="discount_cash_value_group">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="discount_cash_value" class="form-label fw-bold">
                                <i class="fas fa-money-bill-wave me-1"></i>قيمة الخصم النقدي
                            </label>
                            <input type="number" step="0.01" class="form-control form-control-lg" id="discount_cash_value"
                                name="discount_cash_value" value="{{ old('discount_cash_value') }}" placeholder="أدخل قيمة الخصم">
                            @error('discount_cash_value')
                                <div class="text-danger small mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3" id="discount_percentage_value_group">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="discount_percentage_value" class="form-label fw-bold">
                                <i class="fas fa-percentage me-1"></i>نسبة الخصم
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control form-control-lg" id="discount_percentage_value"
                                    name="discount_percentage_value" value="{{ old('discount_percentage_value') }}"
                                    min="1" max="100" placeholder="أدخل نسبة الخصم">
                                <span class="input-group-text">%</span>
                            </div>
                            @error('discount_percentage_value')
                                <div class="text-danger small mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- قسم اختيار المنتجات - مخفي افتراضياً -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="card border-secondary">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    <i class="fas fa-boxes me-2"></i>تطبيق الكود على منتجات محددة (اختياري)
                                </h6>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="toggleProductsSection">
                                    <i class="fas fa-eye me-1"></i>إظهار المنتجات
                                </button>
                            </div>
                            <div class="card-body" id="productsSection" style="display: none;">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-lg mb-2" id="product_search"
                                           placeholder="ابحث عن المنتجات...">

                                    <div class="selected-products mb-3" id="selected_products_container">
                                        <label class="form-label fw-bold text-success">
                                            <i class="fas fa-check-circle me-1"></i>المنتجات المختارة:
                                        </label>
                                        <div id="selected_products_list" class="d-flex flex-wrap gap-2 mt-2">
                                            <!-- سيتم عرض المنتجات المختارة هنا -->
                                        </div>
                                    </div>

                                    <select name="product_ids[]" id="product_ids" class="form-select" multiple size="8" style="display: none;">
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}"
                                                    data-name="{{ $product->name }}"
                                                    {{ is_array(old('product_ids')) && in_array($product->id, old('product_ids')) ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <div id="products_list" class="border rounded p-3 bg-light" style="max-height: 200px; overflow-y: auto;">
                                        @foreach ($products as $product)
                                            <div class="form-check product-item" data-id="{{ $product->id }}" data-name="{{ $product->name }}">
                                                <input class="form-check-input product-checkbox" type="checkbox" value="{{ $product->id }}"
                                                       id="product_{{ $product->id }}"
                                                       name="product_checkboxes[]"
                                                       {{ is_array(old('product_ids')) && in_array($product->id, old('product_ids')) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="product_{{ $product->id }}">
                                                    {{ $product->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer bg-light">
                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-plus-circle me-2"></i>إضافة كود الخصم
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize elements to be hidden
        $('#type_group').hide();
        $('#users_limit_group').hide();
        $('#user_group').hide();
        $('#discount_cash_value_group').hide();
        $('#discount_percentage_value_group').hide();
        $('#selected_products_container').hide();

        // Toggle Products Section
        $('#toggleProductsSection').on('click', function() {
            const productsSection = $('#productsSection');
            const toggleButton = $(this);

            if (productsSection.is(':visible')) {
                productsSection.slideUp(300);
                toggleButton.html('<i class="fas fa-eye me-1"></i>إظهار المنتجات');
                toggleButton.removeClass('btn-primary').addClass('btn-outline-primary');
            } else {
                productsSection.slideDown(300);
                toggleButton.html('<i class="fas fa-eye-slash me-1"></i>إخفاء المنتجات');
                toggleButton.removeClass('btn-outline-primary').addClass('btn-primary');
            }
        });

        // إذا كان هناك منتجات مختارة مسبقاً، نظهر القسم تلقائياً
        @if(is_array(old('product_ids')) && count(old('product_ids')) > 0)
            $('#productsSection').show();
            $('#toggleProductsSection').html('<i class="fas fa-eye-slash me-1"></i>إخفاء المنتجات')
                .removeClass('btn-outline-primary').addClass('btn-primary');
        @endif

        // Handle promo category change
        $('#promo_cat').on('change', function() {
            const promoVal = $(this).val();

            if (promoVal === "user") {
                $('#user_group').show();
                $('#type_group').hide();
                $('#users_limit_group').hide();
            } else if (promoVal === "all") {
                $('#user_group').hide();
                $('#type_group').show();
                $('#type').trigger('change');
            } else {
                $('#user_group').hide();
                $('#type_group').hide();
                $('#users_limit_group').hide();
            }
        });

        // Handle type change
        $('#type').on('change', function() {
            const typeVal = $(this).val();
            if (typeVal === "limited") {
                $('#users_limit_group').show();
            } else {
                $('#users_limit_group').hide();
            }
        });

        // Handle discount type change
        $('#discount_type').on('change', function() {
            const discType = $(this).val();
            if (discType === "percentage") {
                $('#discount_percentage_value_group').show();
                $('#discount_cash_value_group').hide();
            } else if (discType === "cash") {
                $('#discount_cash_value_group').show();
                $('#discount_percentage_value_group').hide();
            } else {
                $('#discount_cash_value_group').hide();
                $('#discount_percentage_value_group').hide();
            }
        });

        // Product search functionality
        $('#product_search').on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            $('.product-item').each(function() {
                const productName = $(this).data('name').toLowerCase();
                if (productName.includes(searchTerm)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Handle product selection
        $(document).on('change', '.product-checkbox', function() {
            updateSelectedProducts();
        });

        // Function to update selected products display
        function updateSelectedProducts() {
            const selectedProducts = [];
            const selectedOptions = [];

            $('.product-checkbox:checked').each(function() {
                const productId = $(this).val();
                const productName = $(this).closest('.product-item').data('name');

                selectedProducts.push({
                    id: productId,
                    name: productName
                });

                selectedOptions.push(productId);
            });

            // Update the hidden select element
            $('#product_ids').val(selectedOptions);

            // Update the display of selected products
            const selectedList = $('#selected_products_list');
            selectedList.empty();

            if (selectedProducts.length > 0) {
                $('#selected_products_container').show();

                selectedProducts.forEach(product => {
                    selectedList.append(`
                        <span class="badge bg-success p-2 d-flex align-items-center selected-product-badge" data-product-id="${product.id}">
                            ${product.name}
                            <button type="button" class="btn-close btn-close-white ms-2 remove-product-btn"
                                    data-product-id="${product.id}"
                                    style="font-size: 0.7rem;"></button>
                        </span>
                    `);
                });
            } else {
                $('#selected_products_container').hide();
            }
        }

        // Handle product removal from selected list
        $(document).on('click', '.remove-product-btn', function(e) {
            e.preventDefault();
            const productId = $(this).data('product-id');

            // Uncheck the corresponding checkbox
            $(`#product_${productId}`).prop('checked', false);

            // Remove the badge
            $(this).closest('.selected-product-badge').remove();

            // Update the selected products
            updateSelectedProducts();
        });

        // Initialize selected products on page load
        updateSelectedProducts();

        // Trigger initial states
        $('#promo_cat').trigger('change');
        $('#discount_type').trigger('change');
    });
</script>

<style>
    .form-label {
        margin-bottom: 0.5rem;
    }
    .card {
        border: none;
        border-radius: 15px;
    }
    .card-header {
        border-radius: 15px 15px 0 0 !important;
        padding: 1.5rem;
    }
    .card-body {
        padding: 2rem;
    }
    .form-control, .form-select {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        transition: all 0.3s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
    }
    .form-control-lg {
        padding: 0.75rem 1rem;
    }
    .product-item {
        padding: 0.5rem;
        border-bottom: 1px solid #eee;
    }
    .product-item:last-child {
        border-bottom: none;
    }
    .product-item:hover {
        background-color: #f8f9fa;
    }
    .selected-products .badge {
        font-size: 0.9rem;
        border-radius: 20px;
    }
    #products_list {
        border: 2px solid #e9ecef;
        border-radius: 10px;
    }
    #toggleProductsSection {
        transition: all 0.3s ease;
    }
    .border-secondary {
        border: 1px solid #6c757d !important;
    }
</style>
@endsection
