<div x-data="{ activeTab: $wire.entangle('activeTab') }" class="container-fluid py-3">
    <!-- Tabs Navigation (Bootstrap 4 compatible) -->
    <ul class="nav nav-tabs nav-fill mb-4" role="tablist">
        <li class="nav-item">
            <button class="nav-link" :class="{ active: activeTab === 'new' }" @click="activeTab = 'new'" type="button">
                <i class="fas fa-shopping-cart me-2"></i>جديد
                <span class="badge bg-primary text-white ms-2">{{ $newOrders->count() }}</span>
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" :class="{ active: activeTab === 'preparing' }" @click="activeTab = 'preparing'" type="button">
                <i class="fas fa-utensils me-2"></i>تحضير
                <span class="badge bg-warning text-dark ms-2">{{ $preparingOrders->count() }}</span>
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" :class="{ active: activeTab === 'delivery' }" @click="activeTab = 'delivery'" type="button">
                <i class="fas fa-truck me-2"></i>شحن
                <span class="badge bg-info text-white ms-2">{{ $deliveryOrders->count() }}</span>
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" :class="{ active: activeTab === 'completed' }" @click="activeTab = 'completed'" type="button">
                <i class="fas fa-check-circle me-2"></i>منتهية
                <span class="badge bg-success text-white ms-2">{{ $completedOrders->count() }}</span>
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" :class="{ active: activeTab === 'failed' }" @click="activeTab = 'failed'" type="button">
                <i class="fas fa-times-circle me-2"></i>فاشلة
                <span class="badge bg-danger text-white ms-2">{{ $failedOrders->count() }}</span>
            </button>
        </li>
    </ul>

    <!-- Tab Panels -->
    <div>
        <div x-show="activeTab === 'new'" x-cloak>
            @include('livewire.orders._order_table', [
                'orders' => $newOrders,
                'status' => 'new',
                'badge' => 'bg-warning text-dark',
                'icon' => 'fa-clock',
                'label' => 'طلب جديد'
            ])
        </div>
        <div x-show="activeTab === 'preparing'" x-cloak>
            @include('livewire.orders._order_table', [
                'orders' => $preparingOrders,
                'status' => 'preparing',
                'badge' => 'bg-info text-white',
                'icon' => 'fa-cog',
                'label' => 'يتم التحضير'
            ])
        </div>
        <div x-show="activeTab === 'delivery'" x-cloak>
            @include('livewire.orders._order_table', [
                'orders' => $deliveryOrders,
                'status' => 'delivery',
                'badge' => 'bg-primary text-white',
                'icon' => 'fa-shipping-fast',
                'label' => 'يتم التوصيل'
            ])
        </div>
        <div x-show="activeTab === 'completed'" x-cloak>
            @include('livewire.orders._order_table', [
                'orders' => $completedOrders,
                'status' => 'completed',
                'badge' => 'bg-success text-white',
                'icon' => 'fa-check',
                'label' => 'منتهية بنجاح'
            ])
        </div>
        <div x-show="activeTab === 'failed'" x-cloak>
            @include('livewire.orders._order_table', [
                'orders' => $failedOrders,
                'status' => 'failed',
                'badge' => 'bg-danger text-white',
                'icon' => 'fa-times',
                'label' => 'طلبية فاشلة'
            ])
        </div>
    </div>
</div>
