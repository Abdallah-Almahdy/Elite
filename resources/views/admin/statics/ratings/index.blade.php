@extends('admin.app')

@section('content')
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">اسم العميل</th>
                <th scope="col">رقم العميل </th>
                <th scope="col">التقييم</th>
                <th scope="col">الرسالة</th>

            </tr>
        </thead>
        <tbody>
            @forelse ($ratings as $rating)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td><a target="_blank"
                            href="{{ route('users.show', $rating->userData->id) }}">{{ str_replace(',', ' ', $rating->userData->name) }}</a>
                    </td>
                    <td> {{ $rating->userData->email }} </td>
                    <td tyle="width: 100%; white-space: nowrap;">
                        <div style="display: flex; align-items: center; gap: 4px;">

                            @php
                                $ratingValue = $rating->raitnum;
                                $fullStars = floor($ratingValue);
                                $halfStar = fmod($ratingValue, 1) >= 0.5 ? 1 : 0;
                                $emptyStars = 5 - $fullStars - $halfStar;
                            @endphp

                            {{-- Empty Stars --}}
                            @for ($i = 0; $i < $emptyStars; $i++)
                                <i class="far fa-star text-warning"></i>
                            @endfor

                            {{-- Half Star --}}
                            @if ($halfStar)
                                <i class="fas fa-star-half-alt text-warning"></i>
                            @endif


                            {{-- Full Stars --}}
                            @for ($i = 0; $i < $fullStars; $i++)
                                <i class="fas fa-star text-warning"></i>
                            @endfor




                            {{-- Numeric Rating --}}
                            {{-- <span class="ms-2">({{ $ratingValue }})</span> --}}
                        </div>
                    </td>

                    <td> {{ $rating->reviewMessage }} </td>
                </tr>
            @empty
                <tr class="text-center text-danger">
                    <td colspan="6 ">
                        لا يوجد نتائج
                    </td>

                </tr>
            @endforelse
        </tbody>
    </table>


    <div class="d-flex justify-content-center mt-3">
        {{ $ratings->links('pagination::bootstrap-4') }}
    </div>
@endsection
