@extends('layout')

@push('styles')
    <link href="{{ asset('resources/css/survey.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 text-center">Список локацій</h1>
    <div class="text-end mt-3 mb-3">
        <a href="{{ route('locations.create') }}" class="btn btn-success">Додати нову локацію</a>
    </div>

    @forelse($locations as $location)
        @php
            $days = ['Пн','Вт','Ср','Чт','Пт','Сб','Нд'];
            $regularByDay = collect($location->regularSchedulers)->groupBy('day_number');
        @endphp

        <div class="card shadow-lg mb-4 p-3">
            <div class="row">
                <div class="col-md-3 border-end">
                    <h4>{{ $location->title }}</h4>
                    <p>{{ $location->description }}</p>

                    <div class="d-flex gap-2 flex-wrap mt-2">
                        <a href="{{ route('locations.edit', $location->id) }}" class="btn btn-primary btn-sm w-100">
                            Редагувати
                        </a>

                        <form class="w-100" action="{{ route('locations.destroy', $location->id) }}" method="POST"
                              onsubmit="return confirm('Точно видалити?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm w-100">Видалити</button>
                        </form>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="mb-3">
                        <h5>Графік роботи</h5>
                        <ul class="mb-0">

                            @for($i = 1; $i <= 7; $i++)
                                <li>
                                    <strong>{{ $days[$i-1] }}:</strong>

                                    @if(isset($regularByDay[$i]))
                                      @foreach($regularByDay[$i] as $r)
                                        {{ \Carbon\Carbon::parse($r->time_from)->format('H:i') }}
                                        –
                                        {{ \Carbon\Carbon::parse($r->time_till)->format('H:i') }}
                                    @endforeach

                                    @else
                                        вихідний
                                    @endif
                                </li>
                            @endfor

                        </ul>
                    </div>
                </div>

            </div>
        </div>

    @empty
        <p class="text-center">Локацій ще немає</p>
    @endforelse
</div>
@endsection
