<div class="card shadow-sm border-0">
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
                        @php
                            $profile = $order->user?->userProfile;
                        @endphp
                        <tr>
                            <td class="fw-bold text-primary">#{{ $order->id }}</td>
                            <td>
                                {{ $profile->first_name ?? '' }} {{ $profile->last_name ?? '' }}
                            </td>
                            <td class="fw-bold">{{ number_format($order->totalPrice, 2) }} ر.س</td>
                            <td>
                                <span class="badge {{ $badge }}">
                                    <i class="fas {{ $icon }} me-1"></i>{{ $label }}
                                </span>
                            </td>
                            <td>
                                @if($profile && $profile->phone_number)
                                    <a href="tel:{{ $profile->phone_number }}" class="text-decoration-none">
                                        <i class="fas fa-phone me-1"></i>{{ $profile->phone_number }}
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('orders.details', $order->id) }}" class="btn btn-outline-info">
                                        <i class="fas fa-eye me-1"></i>معاينة
                                    </a>

                                    @switch($status)
                                        @case('new')
                                            <button wire:click="prep({{ $order->id }})" class="btn btn-outline-primary">
                                                <i class="fas fa-play me-1"></i>بدء التحضير
                                            </button>
                                            <button wire:click="cancel({{ $order->id }})" class="btn btn-outline-danger">
                                                <i class="fas fa-times me-1"></i>إلغاء
                                            </button>
                                            @break

                                        @case('preparing')
                                            <button wire:click="delivery({{ $order->id }})" class="btn btn-outline-primary">
                                                <i class="fas fa-truck me-1"></i>شحن
                                            </button>
                                            <button wire:click="cancel({{ $order->id }})" class="btn btn-outline-danger">
                                                <i class="fas fa-times me-1"></i>إلغاء
                                            </button>
                                            @break

                                        @case('delivery')
                                            <button wire:click="done({{ $order->id }})" class="btn btn-outline-success">
                                                <i class="fas fa-check me-1"></i>إتمام
                                            </button>
                                            <button wire:click="cancel({{ $order->id }})" class="btn btn-outline-danger">
                                                <i class="fas fa-times me-1"></i>إلغاء
                                            </button>
                                            @break

                                        @case('completed')
                                            {{-- No actions --}}
                                            @break

                                        @case('failed')
                                            <button wire:click="done({{ $order->id }})" class="btn btn-outline-success">
                                                <i class="fas fa-check me-1"></i>إتمام
                                            </button>
                                            @break
                                    @endswitch
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fas fa-info-circle fa-2x mb-2 d-block"></i>
                                لا توجد طلبات في هذه الفئة
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
