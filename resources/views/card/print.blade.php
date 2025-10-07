@extends('layout')

@section('content')
@php
    use Milon\Barcode\Facades\DNS1DFacade as DNS1D;
@endphp

<div class="container mt-4 text-center w-50">

    <h1 class="mb-4">Друк вхідного документа</h1>

    <div class="card shadow p-4">
        <p>Це вхідний документ, що дозволяє вхід до локацій ЛНТУ</p>
        <p>для продукту: <strong>{{ $card->price->product->title }}</strong></p>

        <p>Дійсний з: <strong>{{ $card->valid_from->format('d.m.Y') }}</strong></p>
        <p>Дійсний до: <strong>{{ $card->valid_till->format('d.m.Y') }}</strong></p>

        <div class="barcode">
            {!! DNS1D::getBarcodeSVG($card->barcode->barcode, 'C128', 2, 60) !!}
        </div>
    </div>

    <a href="{{ route('card.print.pdf', $card->id) }}" class="btn btn-primary mt-4">
        Завантажити PDF
    </a>
</div>
@endsection
