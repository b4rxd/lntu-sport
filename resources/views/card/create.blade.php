@extends('layout')

@push('styles')
    <link href="{{ asset('resources/css/survey.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="d-flex justify-content-center align-items-start mt-4">
    <div class="card shadow-lg p-4 text-left" style="max-width: 800px; width: 100%;">

        <h1 class="mb-4 text-center">Сформувати вхідний документ для продукта {{ $product->title }}</h1>

        <form action="{{ route('card.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="price_id" class="form-label">Оберіть прайс</label>
                <select class="form-select" id="price_id" name="price_id" required>
                    <option value="" selected disabled>Оберіть прайс</option>
                    @foreach($product->prices as $price)
                        <option value="{{ $price->id }}">{{ $price->title }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">Сформувати вхідний документ</button>
        </form>
    </div>
</div>
@endsectionф
