@extends('layout')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Звіт по оплатах підписок</h2>

    <form method="GET" class="card p-3 mb-4">
        <div class="row">
            <div class="col-md-3">
                <label>Від дати</label>
                <input type="date" name="date_from" value="{{ $filters['date_from'] }}" class="form-control">
            </div>

            <div class="col-md-3">
                <label>До дати</label>
                <input type="date" name="date_till" value="{{ $filters['date_till'] }}" class="form-control">
            </div>

            <div class="col-md-2">
                <label>Продукт</label>
                <select name="product_id" class="form-select">
                    <option value="">Всі</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}"
                            {{ $filters['product_id'] == $product->id ? 'selected' : '' }}>
                            {{ $product->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label>Користувач</label>
                <select name="user_id" class="form-select">
                    <option value="">Всі</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}"
                            {{ $filters['user_id'] == $user->id ? 'selected' : '' }}>
                            {{ $user->first_name }} {{ $user->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label>Локація</label>
                <select name="location_id" class="form-select">
                    <option value="">Всі</option>
                    @foreach($locations as $location)
                        <option value="{{ $location->id }}"
                            {{ $filters['location_id'] == $location->id ? 'selected' : '' }}>
                            {{ $location->title }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- <div class="mt-3 text-end">
            <button class="btn btn-primary">Згенерувати звіт</button>
        </div> -->
    </form>

    <div class="card p-3">
        <h5>Результати:</h5>

        <p>Кількість оплат: <strong>{{ $totalSold }}</strong></p>
        <p>Сума оплат: <strong>{{ number_format($totalAmount, 2) }} грн</strong></p>

        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Продукт</th>
                    <th>Ціна</th>
                    <th>Оплачено</th>
                    <th>Клієнт</th>
                    <th>Користувач</th>
                    <th>Дата</th>
                </tr>
            </thead>

            <tbody>
                @forelse($payments as $payment)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $payment->price->product->title ?? '—' }}</td>
                        <td>{{ $payment->price->amount_in_uah ?? 0 }} грн</td>
                        <td>{{ $payment->paid_amount ?? 0 }} грн</td>
                        <td>
                            {{ $payment->subscription->client->first_name ?? '' }}
                            {{ $payment->subscription->client->last_name ?? '' }}
                        </td>
                        <td>
                            {{ $payment->createdBy->first_name ?? '' }}
                            {{ $payment->createdBy->last_name ?? '' }}
                        </td>
                        <td>{{ $payment->created_at->format('d.m.Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center">Немає даних</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
