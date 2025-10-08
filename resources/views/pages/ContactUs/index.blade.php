@extends('admin.app')
@section('content')
<div class="container" style="min-height: 100vh;">
    <h1 class="mb-4 text-center">الشكاوي</h1>

    @if($complaints->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>المستخدم</th>
                        <th>السبب</th>
                        <th>الرسالة</th>
                        <th>تاريخ الإنشاء</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($complaints as $complaint)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $complaint->user ? $complaint->user->name : 'مستخدم محذوف' }}</td>
                            <td>{{ $complaint->reason_id }}</td>
                            <td>{{ $complaint->message }}</td>
                            <td>{{ $complaint->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-center">لا توجد شكاوي حتى الآن.</p>
    @endif
</div>
@endsection
