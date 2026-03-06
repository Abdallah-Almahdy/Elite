<div class="modal fade show" id="returnModal" tabindex="-1" aria-hidden="true"
    style="display: block; background: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6 me-2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                    </svg>
                    إرجاع فاتورة #{{ $invoiceIdForReturn }}
                </h5>
                <button type="button" class="btn-close btn-close-white" wire:click="closeReturnModal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="submitReturn">
                    <div class="mb-3">
                        <label for="returnType" class="form-label">نوع الإرجاع</label>
                        <select wire:model.live="returnType" id="returnType" class="form-control">
                            <option value="partial">جزئي</option>
                            <option value="full">كلي</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">المنتجات</label>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>المنتج</th>
                                    <th>الكمية المتاحة</th>
                                    <th>الكمية المرتجعة</th>
                                    <th>السعر</th>
                                    <th>الإجمالي</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($returnItems as $index => $item)
                                    <tr>
                                        <td>{{ $item['name'] }}</td>
                                        <td>{{ $item['max_quantity'] }}</td>
                                        <td>
                                            <input type="number"
                                                wire:model.live="returnItems.{{ $index }}.quantity"
                                                min="0" max="{{ $item['max_quantity'] }}"
                                                class="form-control form-control-sm" style="width: 80px;">
                                        </td>
                                        <td>{{ number_format($item['price'], 2) }}</td>
                                        <td>{{ number_format($item['total'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-left">الإجمالي</th>
                                    <th>{{ number_format($refundAmount, 2) }} ج.م</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mb-3">
                        <label for="selectedPaymentMethod" class="form-label">طريقة الرد</label>
                        <select wire:model="selectedPaymentMethod" id="selectedPaymentMethod" class="form-control">
                            @foreach ($paymentMethods as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="refundAmount" class="form-label">المبلغ المرتجع</label>
                        <div class="input-group">
                            <input type="number" step="0.01" wire:model="refundAmount" id="refundAmount"
                                class="form-control" min="0" max="{{ $maxRefundAmount }}">
                            <span class="input-group-text">ج.م</span>
                        </div>
                        <small class="text-muted">المبلغ المتاح للرد: {{ number_format($maxRefundAmount, 2) }}
                            ج.م</small>
                        @error('refundAmount')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="returnReason" class="form-label">سبب الإرجاع (اختياري)</label>
                        <textarea wire:model="returnReason" id="returnReason" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary"
                            wire:click="closeReturnModal">إلغاء</button>
                        <button type="submit" class="btn btn-warning">تأكيد الإرجاع</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
