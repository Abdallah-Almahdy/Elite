<div class="modal fade show" id="returnModal" tabindex="-1" aria-hidden="true"
    style="display: block; background: rgba(0,0,0,0.5);" wire:init="$dispatch('open-return-modal')">
    <div class="modal-dialog modal-xl">
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
                    <div class="row">
                        <!-- العمود الأيمن: المنتجات -->
                        <div class="col-md-7">
                            <div class="mb-3">
                                <label for="returnType" class="form-label">نوع الإرجاع</label>
                                <select wire:model.live="returnType" id="returnType" class="form-control">
                                    <option value="partial">جزئي</option>
                                    <option value="full">كلي</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">المنتجات</label>
                                <div class="table-responsive"
                                    style="max-height: 400px; overflow-y: auto; overflow-x: auto; border: 1px solid #dee2e6; border-radius: 5px;">
                                    <table class="table table-bordered table-sm mb-0" style="min-width: 600px;">
                                        <thead style="position: sticky; top: 0; background-color: #f8f9fa; z-index: 1;">
                                            <tr>
                                                <th style="min-width: 150px;">المنتج</th>
                                                <th style="min-width: 70px;">المتاح</th>
                                                <th style="min-width: 80px;">الكمية</th>
                                                <th style="min-width: 80px;">السعر</th>
                                                <th style="min-width: 100px;">الإجمالي</th>
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
                                                            class="form-control form-control-sm quantity-input"
                                                            style="width: 70px;">
                                                    </td>
                                                    <td>{{ number_format($item['price'], 2) }}</td>
                                                    <td>{{ number_format($item['total'], 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @error('amountError')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                </div>
                            </div>
                        </div>

                        <!-- العمود الأيسر: طرق الدفع -->
                        <div class="col-md-5">
                            <div class="mb-3">
                                <label class="form-label">طرق الدفع</label>
                                <div class="table-responsive"
                                    style="max-height: 400px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 5px;">
                                    <table class="table table-bordered table-sm mb-0">
                                        <thead style="position: sticky; top: 0; background-color: #f8f9fa; z-index: 1;">
                                            <tr>
                                                <th>طريقة الدفع</th>
                                                <th>المدفوع</th>
                                                <th>المسترد</th>
                                                <th>المتبقي</th>
                                                <th>المبلغ المرتجع</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($returnPayments as $method => $data)
                                                <tr>
                                                    <td>{{ $data['label'] }}</td>
                                                    <td>{{ number_format($data['paid'], 2) }}</td>
                                                    <td>{{ number_format($data['refunded'], 2) }}</td>
                                                    <td>{{ number_format($data['available'], 2) }}</td>
                                                    <td>
                                                        <input type="number" step="0.01"
                                                            wire:model.live="returnPayments.{{ $method }}.amount"
                                                            min="0" max="{{ $data['available'] }}"
                                                            class="form-control form-control-sm" style="width: 100px;">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="4" class="text-left">الإجمالي</th>
                                                <th>{{ number_format(collect($this->returnPayments)->sum('amount'), 2) }}
                                                    ج.م</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                @error('returnPayments')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="returnReason" class="form-label">سبب الإرجاع (اختياري)</label>
                                <textarea wire:model="returnReason" id="returnReason" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
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
