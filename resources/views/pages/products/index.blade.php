@extends('admin.app')

@section('styles')
<style>
    /* Additional styles can be added here if needed */
    .content-wrapper {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        min-height: calc(100vh - 120px);
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <livewire:Products.Index />
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('livewire:load', function() {
        console.log('Products page loaded successfully');

        // Additional page-specific scripts can go here
    });
</script>
@endsection
