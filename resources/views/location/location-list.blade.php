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
        <div class="card shadow-lg mb-4 p-3">
            <div class="row">
                <div class="col-md-3 border-end">
                    <h4>{{ $location->title }}</h4>
                    <p>{{ $location->description }}</p>
                    <div class="d-flex gap-2 flex-wrap mt-2">
                       <form class="w-100" action="{{ route('locations.toggle', $location->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-{{ $location->enabled ? 'warning' : 'success' }} btn-sm w-100">
                                {{ $location->enabled ? 'Вимкнути' : 'Увімкнути' }}
                            </button>
                        </form>
                        <a href="{{ route('locations.edit', $location->id) }}" class="btn btn-primary btn-sm w-100">Редагувати</a>
                        <a href="{{ url('/location/scan-barcode/'.$location->id) }}" class="btn btn-info btn-sm w-100">Сканувати штрих-код</a>
                        <a href="{{ url('/location/flow-report/'.$location->id) }}" class="btn btn-success btn-sm w-100">Звіт потоку</a>
                        <form  class="w-100" action="{{ route('locations.destroy', $location->id) }}" method="POST" onsubmit="return confirm('Точно видалити?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm w-100">Видалити</button>
                        </form>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="mb-3">
                        <h5>Стандартний графік</h5>
                        <ul class="mb-0">
                            @forelse($location->regularSchedulers as $r)
                                <li>
                                    {{ \Carbon\Carbon::parse($r->date_from)->format('d.m.Y') }} 
                                    - {{ $r->date_till ? \Carbon\Carbon::parse($r->date_till)->format('d.m.Y') : '∞' }},
                                    День: {{ ['Пн','Вт','Ср','Чт','Пт','Сб','Нд'][$r->day_number -1] }},
                                    {{ $r->time_from }} - {{ $r->time_till }}
                                </li>
                            @empty
                                <li>Немає елементів</li>
                            @endforelse
                        </ul>
                    </div>

                    <div class="mb-3">
                        <h5>Графік вихідних</h5>
                        <ul class="mb-0">
                            @forelse($location->vacationSchedulers as $v)
                                <li>
                                    {{ \Carbon\Carbon::parse($v->date_from)->format('d.m.Y') }} 
                                    - {{ $v->date_till ? \Carbon\Carbon::parse($v->date_till)->format('d.m.Y') : '∞' }},
                                    День: {{ ['Пн','Вт','Ср','Чт','Пт','Сб','Нд'][$v->day_number -1] }},
                                    Назва: {{ $v->title }}
                                </li>
                            @empty
                                <li>Немає елементів</li>
                            @endforelse
                        </ul>
                    </div>

                    <div>
                        <h5>Нетипові дні</h5>
                        <ul class="mb-0">
                            @forelse($location->specialSchedulers as $s)
                                <li>
                                    {{ \Carbon\Carbon::parse($s->date_from)->format('d.m.Y') }} 
                                    - {{ $s->date_till ? \Carbon\Carbon::parse($s->date_till)->format('d.m.Y') : '∞' }},
                                    {{ $s->time_from }} - {{ $s->time_till }}
                                </li>
                            @empty
                                <li>Немає елементів</li>
                            @endforelse
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
