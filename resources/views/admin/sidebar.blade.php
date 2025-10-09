<aside class="main-sidebar sidebar-dark-primary elevation-4" dir="rtl">
    <!-- Brand Logo -->
    <div class="brand-link bg-dark border-bottom border-secondary">
        <div class="brand-text d-flex align-items-center justify-content-center py-3">
            <span class="font-weight-bold text-success h5 mb-0">AGAS SYSTEMS</span>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center border-bottom border-secondary">
            <div class="image">
                <img src="{{ asset('admin/photo/sidebarAdminPhoto.png') }}"
                     class="img-circle elevation-2 border border-success"
                     alt="User Image"
                     style="width:45px; height:45px; object-fit:cover;">
            </div>
            <div class="info mr-2">
                <a href="#" class="d-block text-success fw-bold">{{ Auth::user()->name }}</a>
                <small class="text-muted">Administrator</small>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- كل القائمة تبقى كما هي -->
                @can('showProductsSidebar')
                <li class="nav-item mb-1">
                    <a href="{{ route('products.index') }}" class="nav-link {{ Request::is('dashboard/productds*') ? 'bg-success' : '' }}">
                        <i class="nav-icon fas fa-home ml-2"></i>
                        <p>الرئيسية</p>
                    </a>
                </li>
                @endcan

                <!-- الإعدادات -->
                @can('showProductsSidebar')
                <li class="nav-item mb-1">
                    <a href="{{ route('products.index') }}" class="nav-link {{ Request::is('dashboard/prodducts*') ? 'bg-success' : '' }}">
                        <i class="nav-icon fas fa-cog ml-2"></i>
                        <p>الإعدادات</p>
                    </a>
                </li>
                @endcan

                <!-- المنتجات -->
                @can('showProductsSidebar')
                <li class="nav-item mb-1">
                    <a href="{{ route('products.index') }}" class="nav-link {{ Request::is('dashboard/products*') ? 'bg-success' : '' }}">
                        <i class="nav-icon fas fa-box ml-2"></i>
                        <p>المنتجات</p>
                    </a>
                </li>
                @endcan

                <!-- الأقسام -->
                @can('showSectionsSidebar')
                <li class="nav-item mb-1">
                    <a href="{{ route('sections.index') }}" class="nav-link {{ Request::is('dashboard/sections*') ? 'bg-success' : '' }}">
                        <i class="nav-icon fas fa-th-large ml-2"></i>
                        <p>الأقسام</p>
                    </a>
                </li>
                @endcan

                <!-- الطلبيات -->
                @can('showOrdersSidebar')
                <li class="nav-item mb-1">
                    <a href="{{ route('orders.index') }}" class="nav-link {{ Request::is('dashboard/orders*') ? 'bg-success' : '' }}">
                        <i class="nav-icon fas fa-shopping-cart ml-2"></i>
                        <p class="ml-1">الطلبيات</p>
                        <livewire:Notifications.NotificationBadge />
                    </a>
                </li>
                @endcan

                <!-- الصلاحيات -->
                @can('showPermessionsSidebar')
                <li class="nav-item mb-1">
                    <a href="{{ route('Permissions.index') }}" class="nav-link {{ Request::is('dashboard/Permissions*') ? 'bg-success' : '' }}">
                        <i class="nav-icon fas fa-user-shield ml-2"></i>
                        <p>الصلاحيات</p>
                    </a>
                </li>

                <!-- Special Admin Features -->
                @if (in_array(auth()->user()->id, [7, 48, 50]))
                <!-- الإحصائيات -->
                <li class="nav-item mb-1">
                    <a href="{{ route('statices.index') }}" class="nav-link {{ Request::is('dashboard/statices') ? 'bg-success' : '' }}">
                        <i class="nav-icon fas fa-chart-bar ml-2"></i>
                        <p>الإحصائيات</p>
                    </a>
                </li>

                <!-- الدليفري -->
                <li class="nav-item mb-1">
                    <a href="{{ route('delivery.index') }}" class="nav-link {{ Request::is('dashboard/delivery*') ? 'bg-success' : '' }}">
                        <i class="nav-icon fas fa-truck ml-2"></i>
                        <p>الدليفري</p>
                    </a>
                </li>

                <!-- الإعلانات -->
                <li class="nav-item mb-1">
                    <a href="{{ route('banares.index') }}" class="nav-link {{ Request::is('dashboard/banares*') ? 'bg-success' : '' }}">
                        <i class="nav-icon fas fa-bullhorn ml-2"></i>
                        <p>الإعلانات</p>
                    </a>
                </li>

                <!-- إشعارات التطبيق -->
                <li class="nav-item mb-1">
                    <a href="{{ route('notifications.index') }}" class="nav-link {{ Request::is('dashboard/notifications*') ? 'bg-success' : '' }}">
                        <i class="nav-icon fas fa-bell ml-2"></i>
                        <p>إشعارات التطبيق</p>
                    </a>
                </li>

                <!-- البرومو كود -->
                <li class="nav-item mb-1">
                    <a href="{{ route('promocodes.index') }}" class="nav-link {{ Request::is('dashboard/promocodes*') ? 'bg-success' : '' }}">
                        <i class="nav-icon fas fa-tag ml-2"></i>
                        <p>البرومو كود</p>
                    </a>
                </li>

                <!-- المطابخ -->
                <li class="nav-item mb-1">
                    <a href="{{ route('kitchens.index') }}" class="nav-link {{ Request::is('dashboard/kitchens*') ? 'bg-success' : '' }}">
                        <i class="nav-icon fas fa-utensils ml-2"></i>
                        <p>المطابخ</p>
                    </a>
                </li>

                <!-- الطابعات -->
                <li class="nav-item mb-1">
                    <a href="{{ route('printers.index') }}" class="nav-link {{ Request::is('dashboard/printers*') ? 'bg-success' : '' }}">
                        <i class="nav-icon fas fa-print ml-2"></i>
                        <p>الطابعات</p>
                    </a>
                </li>
                @endif
                @endcan

                <!-- المخازن -->
                @can('showProductsSidebar')
                <li class="nav-item mb-1">
                    <a href="{{ route('warehouses.index') }}" class="nav-link {{ Request::is('dashboard/warehouses*') ? 'bg-success' : '' }}">
                        <i class="nav-icon fas fa-warehouse ml-2"></i>
                        <p>المخازن</p>
                    </a>
                </li>
                @endcan

                <!-- الكاشير -->
                @can('showProductsSidebar')
                <li class="nav-item mb-1">
                    <a href="http://localhost:5174/" target="_blank" class="nav-link">
                        <i class="nav-icon fas fa-cash-register ml-2"></i>
                        <p>الكاشير</p>
                    </a>
                </li>
                @endcan

                <!-- الوصفات -->
                @can('showProductsSidebar')
                <li class="nav-item mb-1">
                    <a href="{{ route('recipes.index') }}" class="nav-link {{ Request::is('recipes*') ? 'bg-success' : '' }}">
                        <i class="nav-icon fas fa-book ml-2"></i>
                        <p>الوصفات</p>
                    </a>
                </li>
                @endcan

                <!-- الوحدات -->
                @can('showProductsSidebar')
                <li class="nav-item mb-1">
                    <a href="{{ route('units.index') }}" class="nav-link {{ Request::is('dashboard/units*') ? 'bg-success' : '' }}">
                        <i class="nav-icon fas fa-ruler-combined ml-2"></i>
                        <p>الوحدات</p>
                    </a>
                </li>
                @endcan

                <!-- المكونات -->
                @can('showProductsSidebar')
                <li class="nav-item mb-1">
                    <a href="{{ route('ingredients.index') }}" class="nav-link {{ Request::is('dashboard/ingredients*') ? 'bg-success' : '' }}">
                        <i class="nav-icon fas fa-lemon ml-2"></i>
                        <p>المكونات</p>
                    </a>
                </li>
                @endcan

                <!-- الموردين -->
                @can('showProductsSidebar')
                <li class="nav-item mb-1">
                    <a href="{{ route('suppliers.index') }}" class="nav-link {{ Request::is('dashboard/suppliers*') ? 'bg-success' : '' }}">
                        <i class="nav-icon fas fa-truck-loading ml-2"></i>
                        <p>الموردين</p>
                    </a>
                </li>
                @endcan

                <!-- العملاء -->
                @can('showProductsSidebar')
                <li class="nav-item mb-1">
                    <a href="{{ route('customers.index') }}" class="nav-link {{ Request::is('dashboard/customers*') ? 'bg-success' : '' }}">
                        <i class="nav-icon fas fa-users ml-2"></i>
                        <p>العملاء</p>
                    </a>
                </li>
                @endcan

                <!-- عننا -->
                @can('showProductsSidebar')
                <li class="nav-item mb-1">
                    <a href="{{ route('about.index') }}" class="nav-link {{ Request::is('dashboard/about*') ? 'bg-success' : '' }}">
                        <i class="nav-icon fas fa-info-circle ml-2"></i>
                        <p>عننا</p>
                    </a>
                </li>
                @endcan

                <!-- تقييمات العملاء -->
                @can('showProductsSidebar')
                <li class="nav-item mb-1">
                    <a href="{{ route('rates.index') }}" class="nav-link {{ Request::is('dashboard/rates*') ? 'bg-success' : '' }}">
                        <i class="nav-icon fas fa-star ml-2"></i>
                        <p>تقييمات العملاء</p>
                    </a>
                </li>
                @endcan

                <!-- شكاوي العملاء -->
                @can('showProductsSidebar')
                <li class="nav-item mb-1">
                    <a href="{{ route('contactus.index') }}" class="nav-link {{ Request::is('dashboard/ContactUs*') ? 'bg-success' : '' }}">
                        <i class="nav-icon fas fa-comment-dots ml-2"></i>
                        <p>شكاوي العملاء</p>
                    </a>
                </li>
                @endcan

            </ul>
        </nav>
    </div>
</aside>
<!-- Responsive Sidebar Overlay -->



<style>
/* نحتاج CSS بسيط للتحكم في الـ transitions والـ z-index */
.main-sidebar {
    transition: transform 0.3s ease-in-out;
}

.sidebar-overlay {
    transition: opacity 0.3s ease-in-out;
}

/* نحافظ على الـ styles الأساسية للـ sidebar */
.sidebar-dark-primary {
    background: linear-gradient(180deg, #1a202c 0%, #2d3748 100%) !important;
}

.nav-sidebar .nav-link {
    border-radius: 8px;
    margin: 2px 8px;
    transition: all 0.3s ease;
    color: #cbd5e1;
    border: 1px solid transparent;
}

.nav-sidebar .nav-link:hover {
    background-color: #2d3748 !important;
    border-color: #38a169;
    transform: translateX(-5px);
}

.nav-sidebar .nav-link.bg-success {
    background-color: #38a169 !important;
    border-color: #38a169;
    color: white;
}
/* نضيف هذا الـ CSS فقط */
.sidebar {
    height: calc(100vh - 130px); /* نحدد ارتفاع ثابت */
    overflow-y: auto; /* نضيف scroll عند الحاجة */
}

/* تخصيص مظهر الـ scrollbar */
.sidebar::-webkit-scrollbar {
    width: 6px;
}

.sidebar::-webkit-scrollbar-track {
    background: #2d3748;
}

.sidebar::-webkit-scrollbar-thumb {
    background: #4a5568;
    border-radius: 3px;
}

.sidebar::-webkit-scrollbar-thumb:hover {
    background: #718096;
}
</style>




<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.main-sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    const toggleBtn = document.getElementById('sidebarToggle');

    // دالة إغلاق الـ sidebar
    function closeSidebar() {
        if (window.innerWidth < 992) {
            sidebar.classList.add('d-none', 'translateX-full');
            sidebar.classList.remove('translateX-zero');
            overlay.classList.add('d-none', 'opacity-0');
            overlay.classList.remove('opacity-50');
        }
    }

    // دالة فتح الـ sidebar
    function openSidebar() {
        if (window.innerWidth < 992) {
            sidebar.classList.remove('d-none', 'translateX-full');
            sidebar.classList.add('translateX-zero');
            overlay.classList.remove('d-none', 'opacity-0');
            overlay.classList.add('opacity-50');
        }
    }

    // دالة toggle
    function toggleSidebar() {
        if (window.innerWidth < 992) {
            if (sidebar.classList.contains('d-none')) {
                openSidebar();
            } else {
                closeSidebar();
            }
        }
    }

    // setup أولي للعناصر
    function setupMobileSidebar() {
        if (window.innerWidth < 992) {
            // إعداد الـ sidebar للموبايل
            sidebar.classList.add('position-fixed', 'end-0', 'top-0', 'h-100', 'd-none', 'translateX-full');
            sidebar.style.zIndex = '1035';
            sidebar.style.width = '280px';
            sidebar.style.marginRight = '-20px';

            // إعداد الـ overlay
            overlay.classList.add('position-fixed', 'top-0', 'start-0', 'w-100', 'h-100', 'bg-dark', 'd-none', 'opacity-0');
            overlay.style.zIndex = '1034';
        } else {
            // في الديسكتوب: نرجع كل حاجة لطبيعتها
            sidebar.classList.remove('position-fixed', 'end-0', 'top-0', 'h-100', 'd-none', 'translateX-full', 'translateX-zero');
            overlay.classList.add('d-none');
        }
    }

    // event listeners
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function(e) {
            e.stopPropagation(); // نمنع انتشار الحدث
            toggleSidebar();
        });
    }

    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }

    // إغلاق الـ sidebar عند الضغط على أي رابط فيه
    const sidebarLinks = document.querySelectorAll('.nav-sidebar .nav-link');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', closeSidebar);
    });

    // إغلاق الـ sidebar عند الضغط في أي مكان في الصفحة
    document.addEventListener('click', function(e) {
        if (window.innerWidth < 992 && !sidebar.classList.contains('d-none')) {
            // إذا كان الـ sidebar مفتوح والضغط خارج الـ sidebar والزر
            if (!sidebar.contains(e.target) && e.target !== toggleBtn) {
                closeSidebar();
            }
        }
    });

    // إغلاق الـ sidebar عند الضغط على زر Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && window.innerWidth < 992 && !sidebar.classList.contains('d-none')) {
            closeSidebar();
        }
    });

    // setup أولي وتحديث عند resize
    setupMobileSidebar();
    window.addEventListener('resize', setupMobileSidebar);
});
</script>
