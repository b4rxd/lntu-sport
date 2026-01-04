@extends('layout')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 text-center">Список повернень</h1>
    <div class="text-end mt-3 mb-3">
        <a href="{{ route('reversal.create') }}" class="btn btn-success">Зробити повернення</a>
    </div>

    @if($reversals->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Сума (₴)</th>
                        <th>Продукт</th>
                        <th>Ціна</th>
                        <th>Дійсна з</th>
                        <th>Дійсна до</th>
                        <th>Користувач (ID)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reversals as $reversal)
                        <tr>
                            <td>{{ $reversal->id }}</td>
                            <td>{{ number_format($reversal->amount_in_uah, 2) }}</td>
                            <td>{{ $reversal->card->price->product->title ?? '—' }}</td>
                            <td>{{ $reversal->card->price->title ?? '—' }}</td>
                            <td>{{ $reversal->card->valid_from?->format('d.m.Y') ?? '—' }}</td>
                            <td>{{ $reversal->card->valid_till?->format('d.m.Y') ?? '—' }}</td>
                            <td>{{ $reversal->createdBy->id ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-center text-muted">Повернень ще немає</p>
    @endif
</div>
@endsection