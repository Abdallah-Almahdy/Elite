@extends('admin.app')

@section('content')
<div class="container mt-5">
    <h3 class="mb-4 text-center">Edit Configuration</h3>

    {{-- ✅ Success Message --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ❌ Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>There were some problems with your input:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ⚙️ Edit Form --}}
    <form action="{{ route('admin.configs.edit') }}" method="POST" class="card shadow-sm p-4">
        @csrf

        <div class="mb-3">
            <label class="form-label">Minimum Supported Version</label>
            <input type="text" name="min_supported_version"
                   value="{{ old('min_supported_version', $config->min_supported_version) }}"
                   class="form-control @error('min_supported_version') is-invalid @enderror">
            @error('min_supported_version')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Exact Blocked Version</label>
            <input type="text" name="exact_blocked_version"
                   value="{{ old('exact_blocked_version', $config->exact_blocked_version) }}"
                   class="form-control @error('exact_blocked_version') is-invalid @enderror">
            @error('exact_blocked_version')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="maintenance_mode" value="1" class="form-check-input" id="maintenance_mode"
                   {{ old('maintenance_mode', $config->maintenance_mode) ? 'checked' : '' }}>
            <label for="maintenance_mode" class="form-check-label">Enable Maintenance Mode</label>
        </div>

        <div class="mb-3">
            <label class="form-label">Maintenance Message</label>
            <textarea name="maintenance_message" rows="3"
                      class="form-control @error('maintenance_message') is-invalid @enderror">{{ old('maintenance_message', $config->maintenance_message) }}</textarea>
            @error('maintenance_message')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Theme Color</label>
            <input type="color" name="color"
                   value="{{ old('color', $config->color ?? '#ffffff') }}"
                   class="form-control form-control-color @error('color') is-invalid @enderror">
            @error('color')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary px-5">Save Changes</button>
        </div>
    </form>
</div>
@endsection
