<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Arabic Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class.
    |
    */

    'accepted' => 'يجب قبول :attribute.',
    'active_url' => ':attribute ليس رابطاً صحيحاً.',
    'after' => 'يجب أن يكون :attribute تاريخاً بعد :date.',
    'after_or_equal' => 'يجب أن يكون :attribute تاريخاً بعد أو يساوي :date.',
    'alpha' => 'يجب أن يحتوي :attribute على أحرف فقط.',
    'alpha_dash' => 'يجب أن يحتوي :attribute على أحرف، أرقام، شرطات، وشرطات سفلية فقط.',
    'alpha_num' => 'يجب أن يحتوي :attribute على أحرف وأرقام فقط.',
    'array' => 'يجب أن يكون :attribute مصفوفة.',
    'before' => 'يجب أن يكون :attribute تاريخاً قبل :date.',
    'before_or_equal' => 'يجب أن يكون :attribute تاريخاً قبل أو يساوي :date.',
    'between' => [
        'numeric' => 'يجب أن تكون قيمة :attribute بين :min و :max.',
        'file' => 'يجب أن يكون حجم الملف :attribute بين :min و :max كيلوبايت.',
        'string' => 'يجب أن يكون طول النص :attribute بين :min و :max حروف.',
        'array' => 'يجب أن يحتوي :attribute على عدد عناصر بين :min و :max.',
    ],
    'boolean' => 'يجب أن تكون قيمة :attribute صحيحة أو خاطئة.',
    'confirmed' => 'تأكيد :attribute غير متطابق.',
    'date' => ':attribute ليس تاريخاً صحيحاً.',
    'email' => 'يجب أن يكون :attribute بريداً إلكترونياً صحيحاً.',
    'exists' => ':attribute المحدد غير موجود.',
    'file' => 'يجب أن يكون :attribute ملفاً.',
    'filled' => 'يجب ملء :attribute.',
    'image' => 'يجب أن يكون :attribute صورة.',
    'in' => ':attribute المحدد غير صحيح.',
    'integer' => 'يجب أن يكون :attribute رقماً صحيحاً.',
    'max' => [
        'numeric' => 'يجب ألا تكون قيمة :attribute أكبر من :max.',
        'file' => 'يجب ألا يكون حجم الملف :attribute أكبر من :max كيلوبايت.',
        'string' => 'يجب ألا يتجاوز طول النص :attribute :max حرف.',
    ],
    'min' => [
        'numeric' => 'يجب أن تكون قيمة :attribute على الأقل :min.',
        'file' => 'يجب أن يكون حجم الملف :attribute على الأقل :min كيلوبايت.',
        'string' => 'يجب أن يحتوي النص :attribute على الأقل :min حرف.',
    ],
    'not_in' => ':attribute المحدد غير صحيح.',
    'numeric' => 'يجب أن يكون :attribute رقماً.',
    'regex' => 'صيغة :attribute غير صحيحة.',
    'required' => 'حقل :attribute مطلوب.',
    'same' => 'يجب أن يتطابق :attribute مع :other.',
    'size' => [
        'numeric' => 'يجب أن تكون قيمة :attribute :size.',
        'file' => 'يجب أن يكون حجم الملف :attribute :size كيلوبايت.',
        'string' => 'يجب أن يحتوي النص :attribute على :size حرف.',
    ],
    'string' => 'يجب أن يكون :attribute نصاً.',
    'unique' => ':attribute مستخدم بالفعل.',
    'url' => 'صيغة :attribute غير صحيحة.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom names for attributes instead of field names.
    |
    */

    'attributes' => [
        'email' => ' الفون',
        'password' => 'كلمة المرور',
        'firstName' => 'الاسم الأول',
        'lastName' => 'الاسم الأخير',
        'birthDate' => 'تاريخ الميلاد',
        'gender' => 'النوع',
        'address' => 'العنوان',
        'phone' => 'الهاتف',
        'city' => 'المدينة',
        'country' => 'الدولة',
        'postal_code' => 'الرمز البريدي',
        'name' => 'الاسم',
        'title' => 'العنوان',
        'content' => 'المحتوى',
        'description' => 'الوصف',
        'date' => 'التاريخ',
        'time' => 'الوقت',
        'terms' => 'الشروط',
        'category_id' => 'الفئة',
        'product_id' => 'المنتج',
        'price' => 'السعر',
        'quantity' => 'الكمية',
        'unit_conversion_factor' => 'عامل تحويل الوحدة',
        'unit_id' => 'الوحدة',
        'payment_method' => 'طريقة الدفع',
        'amount' => 'المبلغ',
        'invoice_id' => 'الفاتورة',
        'safe_id' => 'الخزنة',
        'cashier_id' => 'أمين الصندوق',
        'products' => 'المنتجات',
        'payment_methods' => 'طرق الدفع',
        'key' => 'المفتاح',
        'total' => 'المجموع',
        'id' => 'المعرف',
        'amount' => 'المبلغ',
        'cash' => 'نقداً',
        'credit_card' => 'بطاقة ائتمان',
        'instapay' => 'إنستاباي',
        'wallet' => 'محفظة',
        'remaining' => 'المتبقي',
        'payment_methods.*.key' => 'طريقة الدفع',
        'payment_methods.*.amount' => 'مبلغ طريقة الدفع',


    ],

];
