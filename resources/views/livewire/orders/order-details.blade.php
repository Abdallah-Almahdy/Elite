<div class="container-fluid py-3">
    <!-- بطاقة معلومات الطلب -->
    <div class="card shadow-sm mb-4 border-info">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">
                <i class="fas fa-shopping-bag me-2"></i>
                معلومات الطلب
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless table-sm">
                        <tbody>
                            <tr>
                                <td class="fw-bold text-muted" width="40%">رقم الطلب:</td>
                                <td class="fw-bold text-primary">#{{ $orderArray['id'] }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">تاريخ الطلب:</td>
                                <td>{{ \Carbon\Carbon::parse($orderArray['created_at'])->format('Y-m-d H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">المبلغ الكلي:</td>
                                <td class="fw-bold text-success">{{ number_format($orderArray['total'], 2) }} جم</td>
                            </tr>


                            <tr>
                                <td class="fw-bold text-muted">الخصم:</td>
                                <td class="fw-bold text-danger">-{{ number_format($orderArray['discount'], 2) }} جم</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">الإجمالي بعد الخصم:</td>
                                <td class="fw-bold text-success">
                                    {{ number_format($orderArray['total'] - $orderArray['discount'], 2) }} جم
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless table-sm">
                        <tbody>
                            <tr>
                                <td class="fw-bold text-muted" width="40%">الحالة:</td>
                                <td>
                                    @if ($orderArray['status'] == 0)
                                        @if ($orderArray['tracking_status'] == 0)
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-clock me-1"></i>طلب جديد
                                            </span>
                                        @elseif ($orderArray['tracking_status'] == 1)
                                            <span class="badge bg-info text-white">
                                                <i class="fas fa-cog me-1"></i>يتم تجهيز الطلب
                                            </span>
                                        @elseif ($orderArray['tracking_status'] == 2)
                                            <span class="badge bg-primary text-white">
                                                <i class="fas fa-truck me-1"></i>يتم التوصيل
                                            </span>
                                        @endif
                                    @elseif ($orderArray['status'] == 1)
                                        <span class="badge bg-success text-white">
                                            <i class="fas fa-check-circle me-1"></i>تم التوصيل بنجاح
                                        </span>
                                    @elseif ($orderArray['status'] == 2)
                                        <span class="badge bg-danger text-white">
                                            <i class="fas fa-times-circle me-1"></i>فشل الطلب
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @if ($orderArray['promo_name'])
                                <tr>
                                    <td class="fw-bold text-muted">كود الخصم:</td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $orderArray['promo_name'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- بطاقة معلومات العميل -->
    <div class="card shadow-sm mb-4 border-primary">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-user me-2"></i>
                معلومات العميل
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless table-sm">
                        <tbody>
                            <tr>
                                <td class="fw-bold text-muted" width="40%">اسم العميل:</td>
                                <td class="fw-bold">{{ $orderArray['customer']['name'] }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">رقم الهاتف:</td>
                                <td>
                                    <a href="tel:{{ $orderArray['customer']['phone'] }}" class="text-decoration-none">
                                        <i class="fas fa-phone me-2 text-primary"></i>
                                        {{ $orderArray['customer']['phone'] }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">طريقة الدفع:</td>
                                <td>
                                    @switch($orderArray['payment_method'])
                                        @case(0)
                                            <span class="badge bg-success"><i class="fas fa-money-bill-wave me-1"></i>كاش</span>
                                        @break

                                        @case(1)
                                            <span class="badge bg-primary"><i class="fas fa-credit-card me-1"></i>فيزا</span>
                                        @break

                                        @case(2)
                                            <span class="badge bg-info"><i class="fas fa-wallet me-1"></i>محفظة</span>
                                        @break

                                        @case(3)
                                            <span class="badge bg-warning text-dark"><i
                                                    class="fas fa-mobile-alt me-1"></i>instapay</span>
                                        @break

                                        @default
                                            <span class="badge bg-secondary">غير محدد</span>
                                    @endswitch
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- ✅ عرض العنوان المنسق --}}
                <div class="col-md-6">
                    <table class="table table-borderless table-sm">
                        <tbody>
                            <tr>
                                <td class="fw-bold text-muted" width="40%">العنوان:</td>
                                <td>
                                    @php
                                        $address = $orderArray['customer']['finalAddress'] ?? null;
                                    @endphp
                                    @if ($address)
                                        <div class="address-info">
                                            <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                            <div class="d-inline-block">
                                                {{ $address['country'] ?? '' }},
                                                {{ $address['city'] ?? '' }},
                                                {{ $address['street'] ?? '' }}
                                                @if ($address['building_number'])
                                                    - مبنى رقم {{ $address['building_number'] }}
                                                @endif
                                                @if ($address['floor_number'])
                                                    ، الدور {{ $address['floor_number'] }}
                                                @endif
                                                @if ($address['apartment_number'])
                                                    ، شقة {{ $address['apartment_number'] }}
                                                @endif
                                                @if (!empty($orderArray['notes']))
                                                    <br><small class="text-muted">ملاحظات:
                                                        {{ $orderArray['notes'] }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- بطاقة المنتجات -->
    <div class="card shadow-sm mb-4 border-success">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">
                <i class="fas fa-boxes me-2"></i>
                المنتجات المطلوبة
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="25%">المنتج</th>
                            <th width="10%">الصورة</th>
                            <th width="15%">القسم</th>
                            <th width="10%">السعر</th>
                            <th width="10%">الكمية</th>
                            <th width="20%">الخيارات</th>
                            <th width="10%">الإجمالي</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orderArray['products'] as $product)
                            <tr>
                                <td class="fw-bold">{{ $product['name'] }}</td>
                                <td>
                                    <img src="{{ asset('uploads/' . $product['photo']) }}"
                                        alt="{{ $product['name'] }}" class="img-thumbnail" width="60"
                                        height="60" style="object-fit: cover; border-radius: 8px ;  transition: transform 0.2s;">
                                </td>
                                <td><span class="badge bg-secondary">{{ $product['section'] }}</span></td>
                                <td class="text-nowrap">{{ number_format($product['price'], 2) }} جم</td>
                                <td><span class="badge bg-primary rounded-pill">{{ $product['count'] }}</span></td>
                                <td>
                                    @if (!empty($product['options']))
                                        <ul class="list-unstyled mb-0">
                                            @foreach ($product['options'] as $option)
                                                <li>
                                                    <span
                                                        class="fw-bold text-muted">{{ $option['option_name'] }}:</span>
                                                    {{ $option['value_name'] }}
                                                    @if (!empty($option['price']))
                                                        <small
                                                            class="text-success">(+{{ number_format($option['price'], 2) }}
                                                            جم)</small>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                    @if (!empty($product['adds_on']))
                                        <hr class="my-1">
                                        <small class="text-muted d-block">إضافات:</small>
                                        <ul class="list-unstyled mb-0">
                                            @foreach ($product['adds_on'] as $add)
                                                <li>
                                                    {{ $add['name'] }}
                                                    @if ($add['price'] > 0)
                                                        <span
                                                            class="text-success">(+{{ number_format($add['price'], 2) }}
                                                            جم)</span>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </td>
                                <td class="fw-bold text-success text-nowrap">
                                    {{ number_format($product['total'], 2) }} جم
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="6" class="fw-bold text-end">المجموع الكلي:</td>
                            <td class="fw-bold text-success fs-5">{{ number_format($orderArray['total'], 2) }} جم</td>
                        </tr>

                        <tr>
                            <td colspan="6" class="fw-bold text-end text-danger">الخصم:</td>
                            <td class="fw-bold text-danger">-{{ number_format($orderArray['discount'], 2) }} جم</td>
                        </tr>
                        <tr>
                            <td colspan="6" class="fw-bold text-end">الإجمالي بعد الخصم:</td>
                            <td class="fw-bold text-success fs-5">
                                {{ number_format($orderArray['total'] - $orderArray['discount'], 2) }} جم
                            </td>
                        </tr>

                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- زر الطباعة -->

    <div class="text-center mb-4">
        <a href="{{ route('orders.print', $orderArray['id']) }}" class="btn btn-primary btn-lg">
            <i class="fas fa-print me-2"></i>
            طباعة إيصال التحضير
        </a>
    </div>

</div>

@section('styles')
    <style>
        .address-info {
            line-height: 1.6;
        }

        .img-thumbnail {
            border-radius: 8px;
            transition: transform 0.2s;
        }

        .img-thumbnail:hover {
            transform: scale(1.1);
        }

        .table tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
    </style>
@endsection
