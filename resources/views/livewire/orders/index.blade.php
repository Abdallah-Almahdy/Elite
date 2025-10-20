<div class="container-fluid py-3">
    <!-- طلبات جديدة -->
    <div class="card shadow-sm mb-4 border-primary">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-shopping-cart me-2"></i>
                طلبات جديدة
            </h5>
            <span class="badge bg-light text-primary">{{ count($orders) }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="10%">رقم الطلب</th>
                            <th width="20%">اسم العميل</th>
                            <th width="15%">المبلغ الكلي</th>
                            <th width="15%">الحالة</th>
                            <th width="15%">رقم الهاتف</th>
                            <th width="25%" class="text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td class="fw-bold text-primary">#{{ $order->id }}</td>
                                <td>
                                    {{ optional($order->user_info)->firstName ?? '' }}
                                    {{ optional($order->user_info)->lastName ?? '' }}
                                </td>
                                <td class="fw-bold">{{ number_format($order->totalPrice, 2) }} ر.س</td>
                                <td>
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-clock me-1"></i>طلب جديد
                                    </span>
                                </td>
                                <td>
                                    <a href="tel:{{ $order->phoneNumber }}" class="text-decoration-none">
                                        <i class="fas fa-phone me-1"></i>{{ $order->phoneNumber }}
                                    </a>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('orders.details', $order->id) }}" class="btn btn-outline-info">
                                            <i class="fas fa-eye me-1"></i>معاينة
                                        </a>
                                        <button wire:click="prep({{ $order->id }})" class="btn btn-outline-primary">
                                            <i class="fas fa-play me-1"></i>بدء التحضير
                                        </button>
                                        <button wire:click="cancel({{ $order->id }})" class="btn btn-outline-danger">
                                            <i class="fas fa-times me-1"></i>إلغاء
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fas fa-info-circle fa-2x mb-2 d-block"></i>
                                    لا توجد طلبات جديدة
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- طلبات يتم تحضيرها -->
    <div class="card shadow-sm mb-4 border-warning">
        <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-utensils me-2"></i>
                طلبات يتم تحضيرها
            </h5>
            <span class="badge bg-light text-dark">{{ count($inPreperOrders) }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="10%">رقم الطلب</th>
                            <th width="20%">اسم العميل</th>
                            <th width="15%">المبلغ الكلي</th>
                            <th width="15%">الحالة</th>
                            <th width="15%">رقم الهاتف</th>
                            <th width="25%" class="text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($inPreperOrders as $order)
                            <tr>
                                <td class="fw-bold text-primary">#{{ $order->id }}</td>
                                <td>
                                    {{ optional($order->user_info)->firstName ?? '' }}
                                    {{ optional($order->user_info)->lastName ?? '' }}
                                </td>
                                <td class="fw-bold">{{ number_format($order->totalPrice, 2) }} ر.س</td>
                                <td>
                                    <span class="badge bg-info text-white">
                                        <i class="fas fa-cog me-1"></i>يتم التحضير
                                    </span>
                                </td>
                                <td>
                                    <a href="tel:{{ $order->phoneNumber }}" class="text-decoration-none">
                                        <i class="fas fa-phone me-1"></i>{{ $order->phoneNumber }}
                                    </a>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('orders.details', $order->id) }}" class="btn btn-outline-info">
                                            <i class="fas fa-eye me-1"></i>معاينة
                                        </a>
                                        <button wire:click="delivery({{ $order->id }})" class="btn btn-outline-primary">
                                            <i class="fas fa-truck me-1"></i>شحن
                                        </button>
                                        <button wire:click="cancel({{ $order->id }})" class="btn btn-outline-danger">
                                            <i class="fas fa-times me-1"></i>إلغاء
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fas fa-info-circle fa-2x mb-2 d-block"></i>
                                    لا توجد طلبات يتم تحضيرها
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- طلبات في الشحن -->
    <div class="card shadow-sm mb-4 border-info">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-truck me-2"></i>
                طلبات في الشحن
            </h5>
            <span class="badge bg-light text-dark">{{ count($inDeliveryOrders) }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="10%">رقم الطلب</th>
                            <th width="20%">اسم العميل</th>
                            <th width="15%">المبلغ الكلي</th>
                            <th width="15%">الحالة</th>
                            <th width="15%">رقم الهاتف</th>
                            <th width="25%" class="text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($inDeliveryOrders as $order)
                            <tr>
                                <td class="fw-bold text-primary">#{{ $order->id }}</td>
                                <td>
                                    {{ optional($order->user_info)->firstName ?? '' }}
                                    {{ optional($order->user_info)->lastName ?? '' }}
                                </td>
                                <td class="fw-bold">{{ number_format($order->totalPrice, 2) }} ر.س</td>
                                <td>
                                    <span class="badge bg-primary text-white">
                                        <i class="fas fa-shipping-fast me-1"></i>يتم التوصيل
                                    </span>
                                </td>
                                <td>
                                    <a href="tel:{{ $order->phoneNumber }}" class="text-decoration-none">
                                        <i class="fas fa-phone me-1"></i>{{ $order->phoneNumber }}
                                    </a>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('orders.details', $order->id) }}" class="btn btn-outline-info">
                                            <i class="fas fa-eye me-1"></i>معاينة
                                        </a>
                                        <button wire:click="done({{ $order->id }})" class="btn btn-outline-success">
                                            <i class="fas fa-check me-1"></i>إتمام
                                        </button>
                                        <button wire:click="cancel({{ $order->id }})" class="btn btn-outline-danger">
                                            <i class="fas fa-times me-1"></i>إلغاء
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fas fa-info-circle fa-2x mb-2 d-block"></i>
                                    لا توجد طلبات في الشحن
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- طلبات ناجحة -->
    <div class="card shadow-sm mb-4 border-success">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-check-circle me-2"></i>
                طلبات ناجحة
            </h5>
            <span class="badge bg-light text-dark">{{ count($successedOrders) }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="10%">رقم الطلب</th>
                            <th width="20%">اسم العميل</th>
                            <th width="15%">المبلغ الكلي</th>
                            <th width="15%">الحالة</th>
                            <th width="15%">رقم الهاتف</th>
                            <th width="25%" class="text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($successedOrders as $order)
                            <tr>
                                <td class="fw-bold text-primary">#{{ $order->id }}</td>
                                <td>
                                    {{ optional($order->user_info)->firstName ?? '' }}
                                    {{ optional($order->user_info)->lastName ?? '' }}
                                </td>
                                <td class="fw-bold">{{ number_format($order->totalPrice, 2) }} ر.س</td>
                                <td>
                                    <span class="badge bg-success text-white">
                                        <i class="fas fa-check me-1"></i>منتهية بنجاح
                                    </span>
                                </td>
                                <td>
                                    <a href="tel:{{ $order->phoneNumber }}" class="text-decoration-none">
                                        <i class="fas fa-phone me-1"></i>{{ $order->phoneNumber }}
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('orders.details', $order->id) }}" class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-eye me-1"></i>معاينة
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fas fa-info-circle fa-2x mb-2 d-block"></i>
                                    لا توجد طلبات ناجحة اليوم
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- طلبات فاشلة -->
    <div class="card shadow-sm mb-4 border-danger">
        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-times-circle me-2"></i>
                طلبات فاشلة
            </h5>
            <span class="badge bg-light text-dark">{{ count($faildOrders) }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="10%">رقم الطلب</th>
                            <th width="20%">اسم العميل</th>
                            <th width="15%">المبلغ الكلي</th>
                            <th width="15%">الحالة</th>
                            <th width="15%">رقم الهاتف</th>
                            <th width="25%" class="text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($faildOrders as $order)
                            <tr>
                                <td class="fw-bold text-primary">#{{ $order->id }}</td>
                                <td>
                                    {{ optional($order->user_info)->firstName ?? '' }}
                                    {{ optional($order->user_info)->lastName ?? '' }}
                                </td>
                                <td class="fw-bold">{{ number_format($order->totalPrice, 2) }} ر.س</td>
                                <td>
                                    <span class="badge bg-danger text-white">
                                        <i class="fas fa-times me-1"></i>طلبية فاشلة
                                    </span>
                                </td>
                                <td>
                                    <a href="tel:{{ $order->phoneNumber }}" class="text-decoration-none">
                                        <i class="fas fa-phone me-1"></i>{{ $order->phoneNumber }}
                                    </a>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('orders.details', $order->id) }}" class="btn btn-outline-info">
                                            <i class="fas fa-eye me-1"></i>معاينة
                                        </a>
                                        <button wire:click="done({{ $order->id }})" class="btn btn-outline-success">
                                            <i class="fas fa-check me-1"></i>إتمام
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fas fa-info-circle fa-2x mb-2 d-block"></i>
                                    لا توجد طلبات فاشلة اليوم
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
