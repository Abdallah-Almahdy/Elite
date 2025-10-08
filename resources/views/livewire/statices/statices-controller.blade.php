<div>
    <div>
        <div class="row text-lg justify-content-center p-2 m-2">
            <div class="card m-2 border-info mb-3" style="max-width: 18rem;">
                <a target="_blank" href="{{ route('statices.orders') }}" class="card-header"> الطلبات</a>
                <div class="card-body">
                    <h5 class="card-title text-lg">
                    </h5>

                    <p class="card-text"> {{ $orders ?? 0 }}
                </div>
            </div>





            <div class="card m-2 border-success mb-3" style="max-width: 18rem;">
                <a target="_blank" href="{{ route('orders.successed') }}" class="card-header"> الطلبات الناجحة
                </a>
                <div class="card-body">
                    <h5 class="card-title"></h5>
                    <p class="card-text">


                        {{ $successOrders ?? 0 }}
                    </p>
                </div>
            </div>



            <div class="card m-2 border-danger mb-3" style="max-width: 18rem;">
                <a target="_blank" href="{{ route('orders.faild') }}" class="card-header"> الطلبات الفاشلة</a>
                <div class="card-body">
                    <h5 class="card-title"></h5>
                    <p class="card-text">{{ $faildOrders ?? 0 }}</p>
                </div>
            </div>

            <div class="card m-2 border-warning mb-3" style="max-width: 18rem;">
                <a target="_blank" href="{{ route('statices.users') }}" class="card-header"> المستخدمين</a>
                <div class="card-body">
                    <h5 class="card-title"></h5>
                    <p class="card-text">{{ $users }}</p>
                </div>
            </div>
        </div>
        <div>
            <div class="row text-lg justify-content-center p-2 m-2">
                <div class="card m-2 border-info mb-3" style="max-width: 18rem;">
                    <a target="_blank" href="{{ route('sections.main') }}" class="card-header"> عدد الأقسام
                        الرئيسية</a>
                    <div class="card-body">
                        <h5 class="card-title text-lg">



                        </h5>

                        <p class="card-text"> {{ $sectionsCount ?? 0 }}
                    </div>
                </div>





                <div class="card m-2 border-success mb-3" style="max-width: 18rem;">
                    <a target="_blank" href="{{ route('sections.sub') }}" class="card-header"> عدد الأقسام الفرعية</a>
                    <div class="card-body">
                        <h5 class="card-title"></h5>
                        <p class="card-text">

                            {{ $subSectionsCount ?? 0 }}
                        </p>
                    </div>
                </div>

                <div class="card m-2 border-warning mb-3" style="max-width: 18rem;">
                    <a target="_blank" href="{{ route('products.index') }}" class="card-header">عدد المنتجات</a>
                    <div class="card-body">
                        <h5 class="card-title"></h5>
                        <p class="card-text">{{ $productsCount }}</p>
                    </div>
                </div>

                <div class="card m-2 border-danger mb-3" style="max-width: 18rem;">
                    <a target="_blank" href="{{ route('statics.ratings') }}" class="card-header">التقييمات والمقترحات
                    </a>
                    <div class="card-body">
                        <h5 class="card-title"></h5>
                        <p class="card-text">{{ $ratingsCount ?? 0 }}</p>
                    </div>
                </div>

            </div>


        </div>

    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <label for="startDate" class="form-label"> من </label>
            <input type="date" id="startDate" wire:model="startDate" class="form-control">
        </div>
        <div class="col-md-3">
            <label for="endDate" class="form-label">إلي </label>
            <input type="date" id="endDate" wire:model="endDate" class="form-control">
        </div>

    </div>
    <div class="text-end mb-4">
        <button wire:click="updateMetricsByDate" class="btn btn-primary"> تطبيق </button>
    </div>

    <style>
        .card {
            width: 100%;
            height: 2 00px;
            /* Set a fixed height for the cards */
            display: flex;
            text-align: center;
            flex-direction: column;
            justify-content: space-between;
        }

        /* .card-body {
            flex-grow: 1;
        } */
        .card-img-top {
            width: 50px;
            height: 50px;
            margin: 0 auto;
            object-fit: cover;
        }
    </style>
    <!-- SubSection with the Most Orders -->

    <script>
        $('#daterange-btn').daterangepicker({
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(
                        'month')]
                },
                startDate: moment().subtract(29, 'days'),
                endDate: moment()
            },
            function(start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
            }
        )
    </script>
</div>
