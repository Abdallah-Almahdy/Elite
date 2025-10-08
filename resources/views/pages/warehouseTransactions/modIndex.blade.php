@extends('admin.app')

@section('content')
    <div class="flex m-2 overflow-x-auto whitespace-nowrap">
        <a href="{{ route('warehouses.index') }}"
            class="inline-flex items-center h-12 px-2 py-2 text-center text-gray-700 bg-transparent  border-b border-gray-300 sm:px-4 dark:border-gray-500 -px-1 dark:text-white whitespace-nowrap cursor-base focus:outline-none hover:border-gray-400 dark:hover:border-gray-300">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="size-6 ml-1">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
            </svg>


            <span class="mx-1 text-sm sm:text-base">
                المخازن
            </span>
        </a>

        <a href="{{ route('warehouses_trasactions.index') }}"
            class="inline-flex items-center bg-blue-500 text-white h-12 px-2 py-2 text-center text-gray-700 border border-b-0 border-gray-300 sm:px-4 dark:border-gray-500 rounded-t-md -px-1 dark:text-white whitespace-nowrap focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
            </svg>


            <span class="mx-1 text-sm sm:text-base">
                الحركات المخزنية
            </span>
        </a>

    </div>
    <livewire:warehouse-transactions.create />
@endsection
