@extends('admin.app')

@section('content')
    <livewire:products.edit :product="$data->id" />
@endsection
