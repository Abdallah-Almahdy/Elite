<div dir="rtl" style="font-family: Cairo, sans-serif; text-align: right;">
    <h1>طلب #{{ $order->id }}</h1>
    <ul>
        @foreach ($products as $product)
            <li>{{ $product->product->name }} x{{ $product->totalCount }}</li>
        @endforeach
    </ul>
    <p>الإجمالي: ${{ $order->totalPrice }}</p>
</div>
