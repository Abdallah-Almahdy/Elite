import React, { useState, useEffect } from 'react';
import { Trash2, Plus, Minus, ShoppingCart, User, MapPin, Phone, Clock, CreditCard } from 'lucide-react';
import { router } from '@inertiajs/react';

export function PosCart({ cart, setCart }) {
    const [isDialogOpen, setIsDialogOpen] = useState(false);
    const [customerExists, setCustomerExists] = useState(null);
    const [orderType, setOrderType] = useState(""); // delivery | dine-in | takeaway
    const [step, setStep] = useState(1);
    const [checkingCustomer, setCheckingCustomer] = useState(false);
    const [creatingCustomer, setCreatingCustomer] = useState(false);
    const [customerInfo, setCustomerInfo] = useState({
        name: '',
        phone: '',
        address: '',
        notes: ''
    });
    const [selectedHall, setSelectedHall] = useState('');
    const [selectedTable, setSelectedTable] = useState('');
    const [paymentMethod, setPaymentMethod] = useState('cash');

    const halls = [
        { id: 1, name: "الصالة الرئيسية" },
        { id: 2, name: "الصالة الخارجية" },
        { id: 3, name: "الصالة الهادئة" }
    ];

    const tables = [
        { id: 1, number: "1", capacity: 4 },
        { id: 2, number: "2", capacity: 6 },
        { id: 3, number: "3", capacity: 2 },
        { id: 4, number: "4", capacity: 8 },
        { id: 5, number: "5", capacity: 4 }
    ];

    const updateQuantity = (uid, newQuantity) => {
        if (newQuantity === 0) {
            removeFromCart(uid);
        } else {
            setCart(cart.map(item =>
                item.uid === uid
                    ? { ...item, quantity: newQuantity }
                    : item
            ));
        }
    };
    const getMaxSteps = () => {
        return orderType === 'dine-in' ? 3 : 4; // dine-in: hall/table, payment, review | others: customer, payment, review
    };

    const checkUserInfo = () => {
        if (customerExists === true) {
            // Customer exists, phone is enough
            return customerInfo.phone !== '';
        } else if (customerExists === false) {
            // Customer doesn't exist, need name and phone, address only for delivery
            const hasRequiredFields = customerInfo.name !== '' && customerInfo.phone !== '';
            if (orderType === 'delivery') {
                return hasRequiredFields && customerInfo.address !== '';
            }
            return hasRequiredFields;
        }
        return false;
    };

    // Check customer when phone changes
    useEffect(() => {
        // Only check customers for delivery and takeaway, not dine-in
        if (orderType !== 'delivery' && orderType !== 'takeaway') {
            return;
        }

        const timer = setTimeout(() => {
            const phone = customerInfo.phone.trim();

            if (phone.length >= 6) {
                setCheckingCustomer(true);
                fetch('/api/check-customer', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    },
                    body: JSON.stringify({ phone }),
                })
                    .then(res => res.json())
                    .then(data => {
                        setCustomerExists(data.exists);

                        if (data.exists && data.customer) {
                            // Auto-fill customer data if found
                            setCustomerInfo(prev => ({
                                ...prev,
                                name: `${data.customer.firstName} ${data.customer.lastName}`.trim(),
                                address: data.customer.addressCountry || ''
                            }));
                        } else {
                            // Clear fields if customer doesn't exist
                            setCustomerInfo(prev => ({
                                ...prev,
                                name: '',
                                address: ''
                            }));
                        }
                    })
                    .catch(() => {
                        setCustomerExists(false);
                        setCustomerInfo(prev => ({
                            ...prev,
                            name: '',
                            address: ''
                        }));
                    })
                    .finally(() => setCheckingCustomer(false));
            } else {
                setCustomerExists(null);
                setCustomerInfo(prev => ({
                    ...prev,
                    name: '',
                    address: ''
                }));
            }
        }, 500);

        return () => clearTimeout(timer);
    }, [customerInfo.phone, orderType]); // Added orderType dependency

    const removeFromCart = (uid) => {
        setCart(cart.filter(item => item.uid !== uid));
    };


    const getTotalPrice = () => {
        return cart.reduce((total, item) => total + (item.totalItemPrice * item.quantity), 0).toFixed(1);
    };

    const getTotalItems = () => {
        return cart.reduce((total, item) => total + item.quantity, 0);
    };

    const createCustomer = async () => {
        if (customerExists === false) {
            setCreatingCustomer(true);
            try {
                const response = await fetch('/api/create-customer', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        name: customerInfo.name,
                        phone: customerInfo.phone,
                        address: customerInfo.address
                    }),
                });

                const data = await response.json();

                if (data.success) {
                    setCustomerExists(true);
                    return true;
                } else {
                    alert('حدث خطأ في إنشاء العميل. حاول مرة أخرى.');
                    return false;
                }
            } catch (error) {
                console.error('Error creating customer:', error);

                alert('rte حدث خطأ في إنشاء العميل. حاول مرة أخرى.');
                return false;
            } finally {
                setCreatingCustomer(false);
            }
        }
        return true;
    };

    const handleNext = async () => {
        if (step === 1 && customerExists === false) {
            // Need to create customer before proceeding
            const created = await createCustomer();
            if (!created) return;
        }

        if (step < 4) {
            setStep(step + 1);
        }
    };

    const handlePrevious = () => {
        if (step > 1) {
            setStep(step - 1);
        }
    };

    const renderCompactStepContent = () => {
        if (orderType === 'dine-in') {
            switch (step) {
                case 1:
                    return (
                        <div className="space-y-4">
                            <h3 className="font-bold text-gray-800">اختيار الطاولة</h3>

                            {/* Compact Hall Selection */}
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-2">الصالة</label>
                                <div className="space-y-2">
                                    {halls.map(hall => (
                                        <button
                                            key={hall.id}
                                            onClick={() => setSelectedHall(hall.id)}
                                            className={`w-full p-2 text-sm border rounded-lg transition-all ${selectedHall === hall.id
                                                ? 'border-blue-500 bg-blue-50 text-blue-700'
                                                : 'border-gray-300 hover:border-gray-400'
                                                }`}
                                        >
                                            {hall.name}
                                        </button>
                                    ))}
                                </div>
                            </div>

                            {/* Compact Table Selection */}
                            {selectedHall && (
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-2">الطاولة</label>
                                    <div className="grid grid-cols-4 gap-2">
                                        {tables.map(table => (
                                            <button
                                                key={table.id}
                                                onClick={() => setSelectedTable(table.id)}
                                                className={`p-2 border rounded-lg transition-all text-center ${selectedTable === table.id
                                                    ? 'border-blue-500 bg-blue-50'
                                                    : 'border-gray-300 hover:border-gray-400'
                                                    }`}
                                            >
                                                <div className="font-bold text-sm">{table.number}</div>
                                                <div className="text-xs text-gray-500">{table.capacity}</div>
                                            </button>
                                        ))}
                                    </div>
                                </div>
                            )}
                        </div>
                    );
                case 2:
                    return renderCompactPaymentStep();
                case 3:
                    return renderCompactReviewStep();
            }
        } else {
            switch (step) {
                case 1:
                    return renderCompactCustomerStep();
                case 2:
                    return renderCompactPaymentStep();
                case 3:
                    return renderCompactReviewStep();
            }
        }
    };

    const renderCompactCustomerStep = () => (
        <div className="space-y-4">
            <h3 className="font-bold text-gray-800">معلومات العميل</h3>
            <div className="space-y-3">
                <div>
                    <label className="block text-sm font-medium text-gray-700 mb-1">رقم الهاتف</label>
                    <input
                        type="tel"
                        value={customerInfo.phone}
                        onChange={(e) => setCustomerInfo({ ...customerInfo, phone: e.target.value })}
                        className="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="أدخل رقم الهاتف"
                    />

                    {checkingCustomer && (
                        <p className="text-xs text-blue-500 mt-1">🔍 جارٍ التحقق...</p>
                    )}

                    {!checkingCustomer && customerExists === true && (
                        <p className="text-xs text-green-600 mt-1">✔️ العميل موجود</p>
                    )}

                    {!checkingCustomer && customerExists === false && (
                        <p className="text-xs text-red-500 mt-1">❌ عميل جديد</p>
                    )}
                </div>

                {/* Show name field if customer doesn't exist OR if we have existing customer data */}
                {(customerExists === false || (customerExists === true && customerInfo.name)) && (
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
                        <input
                            type="text"
                            value={customerInfo.name}
                            onChange={(e) => setCustomerInfo({ ...customerInfo, name: e.target.value })}
                            className="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="أدخل اسم العميل"
                            readOnly={customerExists === true} // Make readonly if customer exists
                        />
                    </div>
                )}

                {/* Show address field for delivery orders */}
                {orderType === 'delivery' && (customerExists === false || customerExists === true) && (
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">العنوان</label>
                        <textarea
                            value={customerInfo.address}
                            onChange={(e) => setCustomerInfo({ ...customerInfo, address: e.target.value })}
                            className="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="أدخل العنوان"
                            rows={2}
                            readOnly={customerExists === true} // Make readonly if customer exists
                        />
                    </div>
                )}
            </div>
        </div>
    );

    const renderCompactPaymentStep = () => (
        <div className="space-y-4">
            <h3 className="font-bold text-gray-800">طريقة الدفع</h3>
            <div className="space-y-2">
                <button
                    onClick={() => setPaymentMethod('cash')}
                    className={`w-full p-3 border rounded-lg transition-all flex items-center justify-between ${paymentMethod === 'cash'
                        ? 'border-blue-500 bg-blue-50'
                        : 'border-gray-300 hover:border-gray-400'
                        }`}
                >
                    <span className="flex items-center gap-2">
                        <span>💵</span>
                        <span className="font-medium">كاش</span>
                    </span>
                    <div className="w-4 h-4 rounded-full border-2 border-gray-400 flex items-center justify-center">
                        {paymentMethod === 'cash' && <div className="w-2 h-2 bg-blue-500 rounded-full" />}
                    </div>
                </button>
                <button
                    onClick={() => setPaymentMethod('card')}
                    className={`w-full p-3 border rounded-lg transition-all flex items-center justify-between ${paymentMethod === 'card'
                        ? 'border-blue-500 bg-blue-50'
                        : 'border-gray-300 hover:border-gray-400'
                        }`}
                >
                    <span className="flex items-center gap-2">
                        <span>💳</span>
                        <span className="font-medium">بطاقة ائتمان</span>
                    </span>
                    <div className="w-4 h-4 rounded-full border-2 border-gray-400 flex items-center justify-center">
                        {paymentMethod === 'card' && <div className="w-2 h-2 bg-blue-500 rounded-full" />}
                    </div>
                </button>
            </div>
        </div>
    );

    const renderCompactReviewStep = () => (
        <div className="space-y-4">
            <h3 className="font-bold text-gray-800">مراجعة الطلب</h3>

            <div className="bg-blue-50 border border-blue-200 rounded-lg p-3">
                <h4 className="font-bold text-blue-800 text-sm mb-2">تفاصيل الطلب</h4>
                <div className="space-y-1 text-xs">
                    <div className="flex justify-between">
                        <span>النوع:</span>
                        <span className="font-medium">
                            {orderType === 'delivery' ? '🚚 ديليفري' :
                                orderType === 'dine-in' ? '🍽️ صالة' : '📦 تيك اواي'}
                        </span>
                    </div>

                    {orderType === 'dine-in' && (
                        <>
                            <div className="flex justify-between">
                                <span>الصالة:</span>
                                <span className="font-medium">{halls.find(h => h.id === selectedHall)?.name}</span>
                            </div>
                            <div className="flex justify-between">
                                <span>الطاولة:</span>
                                <span className="font-medium">رقم {tables.find(t => t.id === selectedTable)?.number}</span>
                            </div>
                        </>
                    )}

                    <div className="flex justify-between">
                        <span>الدفع:</span>
                        <span className="font-medium">{paymentMethod === 'cash' ? '💵 كاش' : '💳 كارت'}</span>
                    </div>
                </div>
            </div>

            <div className="bg-green-50 border border-green-200 rounded-lg p-3">
                <h4 className="font-bold text-green-800 text-sm mb-2">الملخص</h4>
                <div className="space-y-1 text-xs">
                    <div className="flex justify-between">
                        <span>عدد الأصناف:</span>
                        <span className="font-medium">{cart.length}</span>
                    </div>
                    <div className="flex justify-between">
                        <span>إجمالي القطع:</span>
                        <span className="font-medium">{getTotalItems()}</span>
                    </div>
                    <div className="flex justify-between border-t border-green-200 pt-1 mt-2">
                        <span className="font-bold">الإجمالي:</span>
                        <span className="font-bold text-green-700">ج.م {getTotalPrice()}</span>
                    </div>
                </div>
            </div>
        </div>
    );
    const handleFinishOrder = async () => {
        try {
            const response = await fetch('/api/create-order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    // Remove X-CSRF-TOKEN header completely
                },
                body: JSON.stringify({
                    customer_phone: customerInfo.phone,
                    customer_name: customerInfo.name,
                    customer_address: customerInfo.address,
                    order_type: orderType,
                    payment_method: paymentMethod,
                    cart_items: cart.map(item => ({
                        id: item.id,
                        quantity: item.quantity,
                        price: item.price
                    })),
                    total_price: parseFloat(getTotalPrice())
                }),
            });

            const data = await response.json();

            if (data.success) {
                alert('تم إنشاء الطلب بنجاح! رقم الطلب: ' + data.order_id);
                // Reset everything
                setCart([]);
                setIsDialogOpen(false);
                setStep(1);
                setOrderType('');
                setCustomerInfo({ name: '', phone: '', address: '', notes: '' });
                setPaymentMethod('cash');
                setCustomerExists(null);
            } else {
                alert('حدث خطأ: ' + data.message);
            }
        } catch (error) {
            console.error('Error creating order:', error);
            alert('حدث خطأ في إنشاء الطلب');
        }
    };

    // Mock cart data for demonstration
    const mockCart = cart.length > 0 ? cart : [
        { id: 1, name: 'لافاش كلاسيك 16 قطعة', price: 72.0, quantity: 1, photo: 'products/img.png' },
        { id: 2, name: 'مزارع دينا ليمه 250 جرام', price: 60.0, quantity: 1, photo: 'products/img.png' },
        { id: 3, name: 'تشيرا جبنة فريش 450 جرام برامبلي', price: 94.0, quantity: 1, photo: 'products/img.png' }
    ];

    const renderStepContent = () => {
        switch (step) {
            case 1:
                return (
                    <div className="space-y-6">
                        <meta name="csrf-token" content="{{ csrf_token() }}"></meta>
                        <h3 className="text-lg font-bold text-gray-800">معلومات العميل</h3>
                        <div className="space-y-4">
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
                                <div className="relative">
                                    <Phone className="absolute right-3 top-3 h-4 w-4 text-gray-400" />
                                    <input
                                        type="tel"
                                        value={customerInfo.phone}
                                        onChange={(e) => setCustomerInfo({ ...customerInfo, phone: e.target.value })}
                                        className="w-full pr-10 pl-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="أدخل رقم الهاتف"
                                    />
                                </div>

                                {checkingCustomer && (
                                    <p className="text-sm text-blue-500 mt-1">🔍 جارٍ التحقق من رقم الهاتف...</p>
                                )}

                                {!checkingCustomer && customerExists === true && (
                                    <p className="text-sm text-green-600 mt-1">✔️ العميل موجود</p>
                                )}

                                {!checkingCustomer && customerExists === false && (
                                    <p className="text-sm text-red-500 mt-1">❌ العميل غير موجود - سيتم إنشاء عميل جديد</p>
                                )}
                            </div>

                            {!checkingCustomer && customerExists === false && (
                                <>
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-2">الاسم</label>
                                        <div className="relative">
                                            <User className="absolute right-3 top-3 h-4 w-4 text-gray-400" />
                                            <input
                                                type="text"
                                                value={customerInfo.name}
                                                onChange={(e) => setCustomerInfo({ ...customerInfo, name: e.target.value })}
                                                className="w-full pr-10 pl-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                placeholder="أدخل اسم العميل"
                                                required
                                            />
                                        </div>
                                    </div>

                                    {orderType === 'delivery' && (
                                        <div>
                                            <label className="block text-sm font-medium text-gray-700 mb-2">العنوان</label>
                                            <div className="relative">
                                                <MapPin className="absolute right-3 top-3 h-4 w-4 text-gray-400" />
                                                <textarea
                                                    value={customerInfo.address}
                                                    onChange={(e) => setCustomerInfo({ ...customerInfo, address: e.target.value })}
                                                    className="w-full pr-10 pl-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                    placeholder="أدخل العنوان بالتفصيل"
                                                    rows={3}
                                                    required
                                                />
                                            </div>
                                        </div>
                                    )}

                                    {creatingCustomer && (
                                        <p className="text-sm text-blue-500 mt-1">⏳ جارٍ إنشاء العميل...</p>
                                    )}
                                </>
                            )}

                            {!checkingCustomer && customerExists === true && (
                                <div className="bg-green-50 p-4 rounded-lg">
                                    <h4 className="font-medium text-green-800 mb-2">بيانات العميل</h4>
                                    <div className="space-y-1 text-sm text-green-700">
                                        <p><strong>الاسم:</strong> {customerInfo.name}</p>
                                        {customerInfo.address && (
                                            <p><strong>العنوان:</strong> {customerInfo.address}</p>
                                        )}
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>
                );
            case 2:
                return (
                    <div className="space-y-6">
                        <h3 className="text-lg font-bold text-gray-800">طريقة الدفع</h3>
                        <div className="space-y-4">
                            <div className="grid grid-cols-1 gap-3">
                                <div
                                    onClick={() => setPaymentMethod('cash')}
                                    className={`p-4 border rounded-lg cursor-pointer transition-all ${paymentMethod === 'cash'
                                        ? 'border-blue-500 bg-blue-50'
                                        : 'border-gray-300 hover:border-gray-400'
                                        }`}
                                >
                                    <div className="flex items-center justify-between">
                                        <div className="flex items-center space-x-3">
                                            <div className="w-4 h-4 rounded-full border-2 border-gray-400 flex items-center justify-center">
                                                {paymentMethod === 'cash' && <div className="w-2 h-2 bg-blue-500 rounded-full" />}
                                            </div>
                                            <span className="font-medium">كاش</span>
                                        </div>
                                        <span className="text-sm text-gray-500">دفع نقدي</span>
                                    </div>
                                </div>
                                <div
                                    onClick={() => setPaymentMethod('card')}
                                    className={`p-4 border rounded-lg cursor-pointer transition-all ${paymentMethod === 'card'
                                        ? 'border-blue-500 bg-blue-50'
                                        : 'border-gray-300 hover:border-gray-400'
                                        }`}
                                >
                                    <div className="flex items-center justify-between">
                                        <div className="flex items-center space-x-3">
                                            <div className="w-4 h-4 rounded-full border-2 border-gray-400 flex items-center justify-center">
                                                {paymentMethod === 'card' && <div className="w-2 h-2 bg-blue-500 rounded-full" />}
                                            </div>
                                            <CreditCard className="w-5 h-5 text-gray-600" />
                                            <span className="font-medium">بطاقة ائتمان</span>
                                        </div>
                                        <span className="text-sm text-gray-500">فيزا - ماستركارد</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                );
            case 3:
                return (
                    <div className="space-y-6">
                        <h3 className="text-lg font-bold text-gray-800">مراجعة الطلب</h3>
                        <div className="space-y-4">
                            <div className="bg-gray-50 p-4 rounded-lg">
                                <h4 className="font-medium text-gray-800 mb-2">معلومات العميل</h4>
                                <div className="space-y-1 text-sm text-gray-600">
                                    <p><strong>الاسم:</strong> {customerInfo.name || 'غير محدد'}</p>
                                    <p><strong>الهاتف:</strong> {customerInfo.phone || 'غير محدد'}</p>
                                    {orderType === 'delivery' && (
                                        <p><strong>العنوان:</strong> {customerInfo.address || 'غير محدد'}</p>
                                    )}
                                    {customerInfo.notes && (
                                        <p><strong>ملاحظات:</strong> {customerInfo.notes}</p>
                                    )}
                                </div>
                            </div>
                            <div className="bg-gray-50 p-4 rounded-lg">
                                <h4 className="font-medium text-gray-800 mb-2">تفاصيل الطلب</h4>
                                <div className="space-y-1 text-sm text-gray-600">
                                    <p><strong>نوع الطلب:</strong> {
                                        orderType === 'delivery' ? 'ديليفري' :
                                            orderType === 'dine-in' ? 'صالة' : 'تيك اواي'
                                    }</p>
                                    <p><strong>طريقة الدفع:</strong> {paymentMethod === 'cash' ? 'كاش' : 'بطاقة ائتمان'}</p>
                                    <p><strong>الوقت المتوقع:</strong> {orderType === 'delivery' ? '30-45 دقيقة' : '15-20 دقيقة'}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                );
            default:
                return null;
        }
    };

    return (
        <div className="w-96 bg-white border-r border-gray-200 p-6 flex flex-col" dir="rtl">
            <div className="flex items-center justify-between mb-6">
                <h2 className="text-2xl font-bold text-gray-800">تفاصيل الطلب</h2>
                <div className="flex items-center space-x-2 space-x-reverse">
                    <span>🛒</span>
                    <div className="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm font-medium">
                        {getTotalItems()}
                    </div>
                </div>
            </div>

            {/* Cart Items */}
            <div className="flex-1 overflow-y-auto">
                {cart.length === 0 ? (
                    <div className="text-center text-gray-500 mt-8">
                        <div className="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                            <span className="text-2xl">🛒</span>
                        </div>
                        <p>لم يتم إضافة منتجات</p>
                    </div>
                ) : (
                    <div className="space-y-3">
                        {cart.map((item) => (
                            <div key={item.uid} className="border rounded-lg p-3 bg-white">
                                <div className="flex items-start space-x-3 space-x-reverse mb-2">
                                    <img
                                        src={`http://localhost:8000/uploads/${item.photo}`}
                                        alt={item.name}
                                        className="w-12 h-12 object-cover rounded bg-gray-100"
                                    />
                                    <div className="flex-1 min-w-0">
                                        <h4 className="font-medium text-gray-800 text-sm line-clamp-1">{item.name}</h4>
                                        <p className="text-xs text-gray-600">ج.م {item.price.toFixed(1)}</p>

                                        {/* Show selected options */}
                                        {Object.keys(item.selectedOptions).length > 0 && (
                                            <div className="mt-1">
                                                {Object.values(item.selectedOptions).map((option, idx) => (
                                                    <span key={idx} className="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded mr-1 mb-1">
                                                        {option.value} {option.price > 0 && `(+${option.price})`}
                                                    </span>
                                                ))}
                                            </div>
                                        )}

                                        {/* Show selected add-ons */}
                                        {item.selectedAddOns.length > 0 && (
                                            <div className="mt-1">
                                                {item.selectedAddOns.map((addon, idx) => (
                                                    <span key={idx} className="inline-block bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded mr-1 mb-1">
                                                        {addon.name} (+{addon.price})
                                                    </span>
                                                ))}
                                            </div>
                                        )}
                                    </div>
                                    <div className="text-right">
                                        <p className="font-bold text-sm text-gray-800">
                                            ج.م {(item.totalItemPrice * item.quantity).toFixed(1)}
                                        </p>
                                    </div>
                                </div>

                                {/* Quantity controls */}
                                <div className="flex items-center justify-between">
                                    <div className="flex items-center space-x-2 space-x-reverse">
                                        <button
                                            onClick={() => updateQuantity(item.uid, item.quantity - 1)}
                                            className="h-7 w-7 border border-gray-300 rounded hover:bg-gray-100 flex items-center justify-center"
                                        >
                                            <Minus className="w-3 h-3" />
                                        </button>
                                        <span className="w-8 text-center font-medium text-sm">{item.quantity}</span>
                                        <button
                                            onClick={() => updateQuantity(item.uid, item.quantity + 1)}
                                            className="h-7 w-7 border border-gray-300 rounded hover:bg-gray-100 flex items-center justify-center"
                                        >
                                            <Plus className="w-3 h-3" />
                                        </button>
                                    </div>
                                    <button
                                        onClick={() => removeFromCart(item.uid)}
                                        className="h-7 w-7 text-red-500 hover:text-red-700 hover:bg-red-50 rounded flex items-center justify-center"
                                    >
                                        <Trash2 className="w-3 h-3" />
                                    </button>
                                </div>
                            </div>
                        ))}
                    </div>
                )}
            </div>

            {/* Cart Summary */}
            {cart.length > 0 && (
                <div className="border-t pt-4 mt-4">
                    <div className="flex justify-between items-center mb-6">
                        <span className="text-lg font-semibold text-gray-800">الإجمالي:</span>
                        <span className="text-2xl font-bold text-blue-600">ج.م {getTotalPrice()}</span>
                    </div>
                    <div className="space-y-3">
                        <button
                            className="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg font-medium transition-colors"
                            onClick={() => setIsDialogOpen(true)}
                        >
                            إنهاء الطلب
                        </button>
                        <button
                            className="w-full border border-red-500 text-red-600 hover:bg-red-50 py-3 px-4 rounded-lg font-medium transition-colors"
                            onClick={() => setCart([])}
                        >
                            حذف الجميع
                        </button>
                    </div>
                </div>
            )}

            {/* Dialog */}
            {/* Order Dialog - Full Screen */}
            {isDialogOpen && (
                <div className="fixed inset-0 bg-white z-50 flex flex-col">
                    {/* Compact Header */}
                    <div className="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-4 shadow-lg">
                        <div className="flex justify-between items-center">
                            <div className="flex items-center gap-3">
                                <span className="text-2xl">🛒</span>
                                <div>
                                    <h3 className="text-xl font-bold">تأكيد الطلب</h3>
                                    <p className="text-blue-100 text-sm">إجمالي: ج.م {getTotalPrice()}</p>
                                </div>
                            </div>
                            <button
                                onClick={() => {
                                    setIsDialogOpen(false);
                                    setStep(1);
                                    setOrderType('');
                                    setSelectedHall('');
                                    setSelectedTable('');
                                }}
                                className="bg-white/20 hover:bg-white/30 rounded-full p-2 transition-colors"
                            >
                                <span className="text-xl">×</span>
                            </button>
                        </div>
                    </div>

                    <div className="flex-1 flex overflow-hidden">
                        {/* RIGHT: Cart Items - Scrollable */}
                        <div className="flex-1 p-4 overflow-y-auto bg-gray-50">
                            <div className="max-w-4xl mx-auto">
                                <h3 className="text-lg font-bold mb-3 text-gray-800 sticky top-0 bg-gray-50 py-2">
                                    المنتجات ({cart.length})
                                </h3>

                                {cart.length === 0 ? (
                                    <div className="text-center text-gray-500 py-8">
                                        <span className="text-4xl block mb-2">🛒</span>
                                        <p>لا يوجد منتجات</p>
                                    </div>
                                ) : (
                                    <div className="space-y-3">
                                        {cart.map((item) => (
                                            <div key={item.uid} className="bg-white rounded-lg p-3 shadow-sm border">
                                                <div className="flex gap-3">
                                                    {/* Product Image */}
                                                    <div className="w-12 h-12 bg-gradient-to-br from-orange-100 to-orange-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                                        <span className="text-lg">🍽️</span>
                                                    </div>

                                                    {/* Product Info */}
                                                    <div className="flex-1 min-w-0">
                                                        <div className="flex justify-between items-start mb-2">
                                                            <h4 className="font-bold text-gray-900 text-base line-clamp-1">{item.name}</h4>
                                                            <div className="text-right ml-2">
                                                                <p className="text-xs text-gray-500">الكمية: {item.quantity}</p>
                                                                <p className="font-bold text-green-600">
                                                                    ج.م {(item.totalItemPrice * item.quantity).toFixed(1)}
                                                                </p>
                                                            </div>
                                                        </div>

                                                        {/* Base Price */}
                                                        <p className="text-xs text-gray-600 mb-2">
                                                            السعر الأساسي: ج.م {item.price.toFixed(1)}
                                                        </p>

                                                        {/* Options & Add-ons as compact badges */}
                                                        <div className="flex flex-wrap gap-1">
                                                            {Object.values(item.selectedOptions).map((option, idx) => (
                                                                <span key={idx} className="inline-flex items-center bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-md">
                                                                    <span className="w-1.5 h-1.5 bg-blue-500 rounded-full mr-1"></span>
                                                                    {option.value} {option.price > 0 && `+${option.price}`}
                                                                </span>
                                                            ))}
                                                            {item.selectedAddOns.map((addon, idx) => (
                                                                <span key={idx} className="inline-flex items-center bg-green-100 text-green-800 text-xs px-2 py-1 rounded-md">
                                                                    <span className="w-1.5 h-1.5 bg-green-500 rounded-full mr-1"></span>
                                                                    {addon.name} +{addon.price}
                                                                </span>
                                                            ))}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                )}
                            </div>
                        </div>

                        {/* LEFT: Order Configuration - Fixed Width */}
                        <div className="w-80 bg-white border-r border-gray-200 flex flex-col">
                            {/* Order Type Selection */}
                            <div className="p-4 border-b">
                                <h4 className="font-bold text-gray-800 mb-3">نوع الطلب</h4>
                                <div className="grid grid-cols-3 gap-1 bg-gray-100 p-1 rounded-lg">
                                    {[
                                        { key: "delivery", label: "ديليفري", icon: "🚚" },
                                        { key: "dine-in", label: "صالة", icon: "🍽️" },
                                        { key: "takeaway", label: "تيك اواي", icon: "📦" }
                                    ].map((type) => (
                                        <button
                                            key={type.key}
                                            onClick={() => {
                                                setOrderType(type.key);
                                                setStep(1);
                                                setSelectedHall('');
                                                setSelectedTable('');
                                            }}
                                            className={`px-2 py-3 text-xs font-medium transition-colors rounded-md ${orderType === type.key
                                                ? "bg-blue-600 text-white shadow-sm"
                                                : "text-gray-700 hover:bg-white"
                                                }`}
                                        >
                                            <div className="text-sm mb-1">{type.icon}</div>
                                            {type.label}
                                        </button>
                                    ))}
                                </div>
                            </div>

                            {/* Stepper - Compact */}
                            {orderType && (
                                <div className="flex-1 flex flex-col">
                                    <div className="p-4 border-b">
                                        <div className="flex items-center justify-center gap-2">
                                            {Array.from({ length: getMaxSteps() }, (_, i) => i + 1).map((s, i) => (
                                                <div key={s} className="flex items-center">
                                                    <div className={`w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold ${step >= s ? "bg-blue-600 text-white" : "bg-gray-200 text-gray-500"
                                                        }`}>
                                                        {s}
                                                    </div>
                                                    {i < getMaxSteps() - 1 && <div className="w-6 h-0.5 bg-gray-300 mx-1" />}
                                                </div>
                                            ))}
                                        </div>
                                    </div>

                                    {/* Step Content - Scrollable */}
                                    <div className="flex-1 p-4 overflow-y-auto">
                                        {renderCompactStepContent()}
                                    </div>

                                    {/* Navigation Buttons - Fixed Bottom */}
                                    <div className="p-4 border-t bg-gray-50">
                                        <div className="flex gap-2">
                                            {step > 1 && (
                                                <button
                                                    onClick={handlePrevious}
                                                    className="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-3 rounded-lg text-sm font-medium transition-colors"
                                                >
                                                    السابق
                                                </button>
                                            )}
                                            {step < getMaxSteps() ? (
                                                <button
                                                    onClick={handleNext}
                                                    disabled={!checkUserInfo()}
                                                    className="flex-1 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white py-2 px-3 rounded-lg text-sm font-medium transition-colors"
                                                >
                                                    التالي
                                                </button>
                                            ) : (
                                                <button
                                                    onClick={() => {
                                                        alert('تم إنشاء الطلب بنجاح!');
                                                        setCart([]);
                                                        setIsDialogOpen(false);
                                                        setStep(1);
                                                        setOrderType('');
                                                        setSelectedHall('');
                                                        setSelectedTable('');
                                                    }}
                                                    className="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 px-3 rounded-lg text-sm font-medium transition-colors"
                                                >
                                                    تأكيد الطلب
                                                </button>
                                            )}
                                        </div>
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            )}
        </div>
    );

}
