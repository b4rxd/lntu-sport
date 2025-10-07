@php
    use Milon\Barcode\Facades\DNS1DFacade as DNS1D;
@endphp
<style>
    body {
        font-family: DejaVu Sans, sans-serif;
    }
    .card {
        border: 1px solid #000;
        padding: 20px;
        text-align: center;
    }
    .barcode {
        margin-top: 20px;
    }
</style>

<div class="container mt-4 text-center w-50">
    <div class="card shadow p-4">
        <p>Це вхідний документ, що дозволяє вхід до локацій ЛНТУ</p>
        <p>для продукту: <strong>{{ $card->price->product->title }}</strong></p>

        <p>Дійсний з: <strong>{{ $card->valid_from->format('d.m.Y') }}</strong></p>
        <p>Дійсний до: <strong>{{ $card->valid_till->format('d.m.Y') }}</strong></p>

        <div class="barcode">
            <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($card->barcode->barcode, 'C128', 2, 60) }}" alt="barcode">
            <br>
            <sapn>{{ $card->barcode->barcode }}</span>
        </div>
    </div>
</div>