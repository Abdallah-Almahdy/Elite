@extends('admin.app')



@section('content')
    @if (count($data) > 0)
    @endif


    <livewire:sections.show :data="$data" />
@endsection
