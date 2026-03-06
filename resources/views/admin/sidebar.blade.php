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
                    class="img-circle elevation-2 border border-success" alt="User Image"
                    style="width:45px; height:45px; object-fit:cover;">
            </div>
            <div class="info mr-2">
                <a href="#" class="d-block text-success fw-bold">{{ Auth::user()->name }}</a>
                <small class="text-muted">Administrator</small>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- كل القائمة تبقى كما هي -->
                @can('showProductsSidebar')
                    <li class="nav-item mb-1">
                        <a href="{{ route('products.index') }}"
                            class="nav-link {{ Request::is('dashboard/productds*') ? 'bg-success' : '' }}">
                            <i class="nav-icon fas fa-home ml-2"></i>
                            <p>الرئيسية</p>
                        </a>
                    </li>
                @endcan

                <!-- الإعدادات -->
                @can('config.update')
                    <li class="nav-item mb-1">
                        <a href="{{ route('config.update') }}"
                            class="nav-link {{ Request::is('dashboard/prodducts*') ? 'bg-success' : '' }}">
                            <i class="nav-icon fas fa-cog ml-2"></i>
                            <p>الإعدادات</p>
                        </a>
                    </li>
                @endcan


                @can('showPermessionsSidebar')
                    <li class="nav-item mb-1">
                        <a href="{{ route('Permissions.index') }}"
                            class="nav-link {{ Request::is('dashboard/Permissions*') ? 'bg-success' : '' }}">
                            <i class="nav-icon fas fa-user-shield ml-2"></i>
                            <p>الصلاحيات</p>
                        </a>
                    </li>
                @endcan


                @can('user.create')
                    <li class="nav-item mb-1">
                        <a href="{{ route('register') }}"
                            class="nav-link {{ Request::is('dashboard/register*') ? 'bg-success' : '' }}">
                            <i class="nav-icon fas fa-user-shield ml-2"></i>
                            <p>انشاء مستخدم</p>
                        </a>
                    </li>
                @endcan

                <!-- المنتجات -->
                @can('showProductsSidebar')
                    <li class="nav-item mb-1">
                        <a href="{{ route('products.index') }}"
                            class="nav-link {{ Request::is('dashboard/products*') ? 'bg-success' : '' }}">
                            <i class="nav-icon fas fa-box ml-2"></i>
                            <p>المنتجات</p>
                        </a>
                    </li>
                @endcan

                <!-- الأقسام -->
                @can('showSectionsSidebar')
                    <li class="nav-item mb-1">
                        <a href="{{ route('sections.index') }}"
                            class="nav-link {{ Request::is('dashboard/sections*') ? 'bg-success' : '' }}">
                            <i class="nav-icon fas fa-th-large ml-2"></i>
                            <p>الأقسام</p>
                        </a>
                    </li>
                @endcan

                <!-- الطلبيات -->
                @can('showOrdersSidebar')
                    <li class="nav-item mb-1">
                        <a href="{{ route('orders.index') }}"
                            class="nav-link {{ Request::is('dashboard/orders*') ? 'bg-success' : '' }}">
                            <i class="nav-icon fas fa-shopping-cart ml-2"></i>
                            <p class="ml-1">الطلبيات</p>
                            <livewire:Notifications.NotificationBadge />
                        </a>
                    </li>
                @endcan
                <li class="nav-item has-treeview">

                    <a href="#" class="nav-link text-white">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>
                            التقارير
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        <!-- المبيعات -->
                        <li class="nav-item has-treeview">

                            <a href="#" class="nav-link text-white">
                                <i class="nav-icon fas fa-shopping-cart"></i>
                                <p>
                                    المبيعات
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>

                            <ul class="nav nav-treeview">

                                <li class="nav-item">
                                    <a href="{{ route('invoices.index') }}"
                                        class="nav-link {{ Request::is('dashboard/invoices*') ? 'bg-success' : '' }}">
                                        <i class="far fa-file-alt nav-icon"></i>
                                        <p>كشف مستندات البيع</p>
                                    </a>
                                </li>

                            </ul>
                        </li>


                        <!-- الورديات -->
                        <li class="nav-item has-treeview">

                            <a href="#" class="nav-link text-white">
                                <i class="nav-icon fas fa-user-clock"></i>
                                <p>
                                    الورديات
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>

                            <ul class="nav nav-treeview">

                                <li class="nav-item">
                                    <a href="{{ route('shifts.index') }}"
                                        class="nav-link {{ Request::is('dashboard/shifts*') ? 'bg-success' : '' }}">
                                        <i class="far fa-file-alt nav-icon"></i>
                                        <p>تقرير الورديات</p>
                                    </a>
                                </li>

                            </ul>
                        </li>


                        <!-- المخازن -->
                        <li class="nav-item has-treeview">

                            <a href="#" class="nav-link text-white">
                                <i class="nav-icon fas fa-warehouse"></i>
                                <p>
                                    المخازن
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>

                            <ul class="nav nav-treeview">

                                <li class="nav-item">
                                    <a" class="nav-link">
                                        <i class="far fa-file-alt nav-icon"></i>
                                        <p>كشف المخازن</p>
                                    </a>
                                </li>

                            </ul>
                        </li>

                    </ul>

                </li>

                <li class="nav-item mb-1">
                    <a href="{{ route('pos.index') }}"
                        class="nav-link {{ Request::is('dashboard/POS*') ? 'bg-success' : '' }}">
                        <i class="nav-icon fas fa-file-invoice ml-2"></i>
                        <p class="ml-1">شاشه البيع</p>


                    </a>
                </li>

                <!-- الصلاحيات -->


                <!-- Special Admin Features -->

                @can('showStatics')
                    <!-- الإحصائيات -->
                    <li class="nav-item mb-1">
                        <a href="{{ route('statices.index') }}"
                            class="nav-link {{ Request::is('dashboard/statices') ? 'bg-success' : '' }}">
                            <i class="nav-icon fas fa-chart-bar ml-2"></i>
                            <p>الإحصائيات</p>
                        </a>
                    </li>
                @endcan

                @can('showDelevary')
                    <!-- الدليفري -->
                    <li class="nav-item mb-1">
                        <a href="{{ route('delivery.index') }}"
                            class="nav-link {{ Request::is('dashboard/delivery*') ? 'bg-success' : '' }}">
                            <i class="nav-icon fas fa-truck ml-2"></i>
                            <p>الدليفري</p>
                        </a>
                    </li>
                @endcan

                @can('showAdds')
                    <!-- الإعلانات -->
                    <li class="nav-item mb-1">
                        <a href="{{ route('banares.index') }}"
                            class="nav-link {{ Request::is('dashboard/banares*') ? 'bg-success' : '' }}">
                            <i class="nav-icon fas fa-bullhorn ml-2"></i>
                            <p>الإعلانات</p>
                        </a>
                    </li>
                @endcan


                @can('showNotifications')
                    <!-- إشعارات التطبيق -->
                    <li class="nav-item mb-1">
                        <a href="{{ route('notifications.index') }}"
                            class="nav-link {{ Request::is('dashboard/notifications*') ? 'bg-success' : '' }}">
                            <i class="nav-icon fas fa-bell ml-2"></i>
                            <p>إشعارات التطبيق</p>
                        </a>
                    </li>
                @endcan


                @can('showPromoCode')
                    <!-- البرومو كود -->
                    <li class="nav-item mb-1">
                        <a href="{{ route('promocodes.index') }}"
                            class="nav-link {{ Request::is('dashboard/promocodes*') ? 'bg-success' : '' }}">
                            <i class="nav-icon fas fa-tag ml-2"></i>
                            <p>البرومو كود</p>
                        </a>
                    </li>
                @endcan

                @can('showKitchen')
                    <!-- المطابخ -->
                    <li class="nav-item mb-1">
                        <a href="{{ route('kitchens.index') }}"
                            class="nav-link {{ Request::is('dashboard/kitchens*') ? 'bg-success' : '' }}">
                            <i class="nav-icon fas fa-utensils ml-2"></i>
                            <p>المطابخ</p>
                        </a>
                    </li>
                @endcan

                @can('showPrinters')
                    <!-- الطابعات -->
                    <li class="nav-item mb-1">
                        <a href="{{ route('printers.index') }}"
                            class="nav-link {{ Request::is('dashboard/printers*') ? 'bg-success' : '' }}">
                            <i class="nav-icon fas fa-print ml-2"></i>
                            <p>الطابعات</p>
                        </a>
                    </li>
                @endcan



                <!-- المخازن -->
                @can('showStock')
                    <li class="nav-item mb-1">
                        <a href="{{ route('warehouses.index') }}"
                            class="nav-link {{ Request::is('dashboard/warehouses*') ? 'bg-success' : '' }}">
                            <i class="nav-icon fas fa-warehouse ml-2"></i>
                            <p>المخازن</p>
                        </a>
                    </li>
                @endcan

                <!-- الكاشير -->
                @can('showCashier')
                    <li class="nav-item mb-1">
                        <a href="http://localhost:5174/" target="_blank" class="nav-link">
                            <i class="nav-icon fas fa-cash-register ml-2"></i>
                            <p>الكاشير</p>
                        </a>
                    </li>
                @endcan

                <!-- الوصفات -->

                <!-- الوحدات -->
                @can('showUnits')
                    <li class="nav-item mb-1">
                        <a href="{{ route('units.index') }}"
                            class="nav-link {{ Request::is('dashboard/units*') ? 'bg-success' : '' }}">
                            <i class="nav-icon fas fa-ruler-combined ml-2"></i>
                            <p>الوحدات</p>
                        </a>
                    </li>
                @endcan



                <!-- الموردين -->
                @can('showSuppliers')
                    <li class="nav-item mb-1">
                        <a href="{{ route('suppliers.index') }}"
                            class="nav-link {{ Request::is('dashboard/suppliers*') ? 'bg-success' : '' }}">
                            <i class="nav-icon fas fa-truck-loading ml-2"></i>
                            <p>الموردين</p>
                        </a>
                    </li>
                @endcan

                <!-- العملاء -->
                @can('showClients')
                    <li class="nav-item mb-1">
                        <a href="{{ route('customers.index') }}"
                            class="nav-link {{ Request::is('dashboard/customers*') ? 'bg-success' : '' }}">
                            <i class="nav-icon fas fa-users ml-2"></i>
                            <p>العملاء</p>
                        </a>
                    </li>
                @endcan

                <!-- عننا -->
                @can('showAboutUs')
                    <li class="nav-item mb-1">
                        <a href="{{ route('about.index') }}"
                            class="nav-link {{ Request::is('dashboard/about*') ? 'bg-success' : '' }}">
                            <i class="nav-icon fas fa-info-circle ml-2"></i>
                            <p>عننا</p>
                        </a>
                    </li>
                @endcan

                <!-- تقييمات العملاء -->
                @can('showReviews')
                    <li class="nav-item mb-1">
                        <a href="{{ route('rates.index') }}"
                            class="nav-link {{ Request::is('dashboard/rates*') ? 'bg-success' : '' }}">
                            <i class="nav-icon fas fa-star ml-2"></i>
                            <p>تقييمات العملاء</p>
                        </a>
                    </li>
                @endcan

                <!-- شكاوي العملاء -->
                @can('showComplaints')
                    <li class="nav-item mb-1">
                        <a href="{{ route('contactus.index') }}"
                            class="nav-link {{ Request::is('dashboard/ContactUs*') ? 'bg-success' : '' }}">
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
    /* ============================= */
    /* Sidebar Animation */
    /* ============================= */

    .main-sidebar {
        transition: transform .3s ease-in-out;
    }

    .sidebar-overlay {
        transition: opacity .3s ease-in-out;
    }


    /* ============================= */
    /* Sidebar Background */
    /* ============================= */

    .sidebar-dark-primary {
        background: linear-gradient(180deg, #111827, #1f2937) !important;
    }


    /* ============================= */
    /* Brand Logo */
    /* ============================= */

    .brand-link {
        background: #111827 !important;
        border-bottom: 1px solid #374151 !important;
    }

    .brand-text span {
        letter-spacing: 1px;
        font-weight: 700;
    }


    /* ============================= */
    /* User Panel */
    /* ============================= */

    .user-panel {
        padding-right: 10px;
    }

    .user-panel .info a {
        font-size: 15px;
        font-weight: 600;
        color: #e5e7eb;
    }

    .user-panel small {
        font-size: 12px;
        color: #9ca3af;
    }


    /* ============================= */
    /* Sidebar Links */
    /* ============================= */

    .nav-sidebar .nav-link {

        border-radius: 10px;
        margin: 4px 10px;
        padding: 10px 14px;

        transition: all .25s ease;

        color: #cbd5e1;

        border: 1px solid transparent;

    }

    .nav-sidebar .nav-link i {

        width: 22px;
        text-align: center;
        margin-left: 6px;

    }


    /* ============================= */
    /* Hover Effect */
    /* ============================= */

    .nav-sidebar .nav-link:hover {

        background: rgba(56, 161, 105, 0.15);
        color: #38a169;

        border-color: rgba(56, 161, 105, 0.3);

        transform: translateX(-4px);

    }


    /* ============================= */
    /* Active Link */
    /* ============================= */

    .nav-sidebar .nav-link.bg-success {

        background: linear-gradient(135deg, #38a169, #2f855a) !important;

        color: white;

        border-color: #38a169;

        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);

    }


    /* ============================= */
    /* Treeview (submenu) */
    /* ============================= */

    .nav-treeview {

        background: rgba(255, 255, 255, 0.03);

        margin-right: 10px;

        border-right: 2px solid #38a169;

        border-radius: 6px;

        padding: 4px 0;

    }

    .nav-treeview .nav-link {

        font-size: 14px;

        padding-right: 32px;

    }

    .nav-treeview .nav-link:hover {

        background: rgba(56, 161, 105, 0.2);

    }


    /* ============================= */
    /* Sidebar Scroll */
    /* ============================= */

    .sidebar {

        height: calc(100vh - 130px);

        overflow-y: auto;

    }


    /* ============================= */
    /* Scrollbar Style */
    /* ============================= */

    .sidebar::-webkit-scrollbar {

        width: 5px;

    }

    .sidebar::-webkit-scrollbar-track {

        background: #1f2937;

    }

    .sidebar::-webkit-scrollbar-thumb {

        background: #4b5563;

        border-radius: 10px;

    }

    .sidebar::-webkit-scrollbar-thumb:hover {

        background: #6b7280;

    }

    .nav-treeview {
        background: transparent;
        border-right: 2px solid rgba(56, 161, 105, .4);
        margin-right: 8px;
    }

    .nav-treeview .nav-link {
        font-size: 14px;
        padding-right: 28px;
    }
</style>
