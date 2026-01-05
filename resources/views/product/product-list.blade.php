@extends('layout')

@push('styles')
    <link href="{{ asset('resources/css/survey.css') }}" rel="stylesheet">
@endpush

@section('content') 
<div class="container mt-4">
    <h1 class="mb-4 text-center">Список продуктів</h1>
    <div class="text-end mt-3 mb-3">
        @if(auth()->user()->hasPermission(\App\Enums\Permission::CREATE_PRODUCT))
            <a href="{{ route('products.create') }}" class="btn btn-success">Створити продукт</a>
        @endif
    </div>


    @php
        $types = [
            'one_time' => 'Одноразовий',
            'monthly' => 'Місячний',
            'yearly' => 'Річний'
        ];
    @endphp

    @forelse($products->chunk(3) as $productChunk)
        <div class="row mb-4">
            @foreach($productChunk as $product)
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $product->title }}</h5>
                            <p class="card-text">{{ $product->description }}</p>
                            
                            <ul class="list-group list-group-flush mb-3">
                               <li class="list-group-item">
                                    Тип: {{ $types[$product->type->value] ?? $product->type->value }}
                                </li>
                                <li class="list-group-item">
                                    Кількість використань:
                                    {{ $product->infinite ? 'безліч' : $product->count_usage }}
                                </li>
                            </ul>

                            @if($product->prices->count() > 0)
                                <h6>Ціни:</h6>
                                <ul class="list-group mb-3">
                                    @foreach($product->prices as $price)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{ $price->title }} — {{ $price->amount_in_uah }} ₴

                                            @if(auth()->user()->isAdmin())
                                                <form action="{{ route('prices.destroy', $price->id) }}" method="POST" class="ms-3">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm w-100">Видалити</button>
                                                </form>
                                            @endif

                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted mb-3">Цін ще немає</p>
                            @endif

                            <div class="mt-auto">
                                @if(auth()->user()->hasPermission(\App\Enums\Permission::CREATE_PRODUCT))
                                    <button class="btn btn-primary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#priceModal-{{ $product->id }}">
                                        Створити ціну
                                    </button>
                                @endif

                                <a href="{{ route('subscription.create', $product->id) }}" class="btn btn-success w-100 mb-2">Продати</a>
                                @if(auth()->user()->isAdmin())
                                    <form class="w-100" action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Точно видалити?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm w-100">Видалити</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="priceModal-{{ $product->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Створення ціни для "{{ $product->title }}"</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрити"></button>
                            </div>
                            <form action="{{ route('prices.store') }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                                    <div class="mb-3">
                                        <label class="form-label">Назва</label>
                                        <input type="text" name="title" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Сума</label>
                                        <input type="number" step="0.01" name="amount_in_uah" class="form-control" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Створити</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрити</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            @endforeach
        </div>
    @empty
        <p class="text-center">Продуктів ще немає</p>
    @endforelse
</div>
@endsection