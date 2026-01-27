@extends('layout')

@push('styles')
<link href="{{ asset('resources/css/survey.css') }}" rel="stylesheet">
<style>
    #client_results {
        position: absolute;
        background: white;
        border: 1px solid #ddd;
        border-radius: 0.5rem;
        width: 100%;
        max-height: 250px;
        overflow-y: auto;
        z-index: 1000;
    }
    #client_results .list-group-item {
        cursor: pointer;
    }
    #client_results .list-group-item:hover {
        background-color: #f1f1f1;
    }
    #client_selected {
        background-color: #e8f5e9;
        border: 1px solid #a5d6a7;
        border-radius: 0.5rem;
        padding: 10px;
        margin-top: 10px;
        display: none;
    }
    .is-invalid {
        border-color: #dc3545;
    }
    .invalid-feedback {
        display: block;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-center align-items-start mt-4">
    <div class="card shadow-lg p-4 text-left" style="max-width: 800px; width: 100%;">

        <h1 class="mb-4 text-center">Сформувати вхідний документ для продукта {{ $product->title }}</h1>

        <form action="{{ route('subscription.store') }}" method="POST" id="subscriptionForm">
            @csrf

            <div class="mb-3">
                <label for="price_id" class="form-label">Оберіть прайс</label>
                <select class="form-select @error('price_id') is-invalid @enderror" id="price_id" name="price_id" required>
                    <option value="" selected disabled>Оберіть прайс</option>
                    @foreach($product->prices as $price)
                        <option value="{{ $price->id }}" {{ old('price_id') == $price->id ? 'selected' : '' }}>
                            {{ $price->title }}
                        </option>
                    @endforeach
                </select>
                @error('price_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <h5 class="mt-4">Клієнт</h5>
            <div class="mb-3 position-relative">
                <label for="client_search" class="form-label">Пошук клієнта (за ім’ям або телефоном)</label>
                <input type="text" id="client_search" class="form-control" placeholder="Почніть вводити ім’я або телефон..." value="{{ old('first_name') ? old('first_name').' '.old('last_name') : '' }}">
                <div id="client_results" class="list-group position-absolute w-100"></div>
            </div>

            <div id="client_selected">
                @if(old('client_id'))
                    <strong>✅ Вибраний клієнт:</strong><br>
                    {{ old('first_name') }} {{ old('last_name') }}<br>
                    <small>{{ old('phone') }}</small>
                @endif
            </div>

            <input type="hidden" name="client_id" id="client_id" value="{{ old('client_id') }}">

            <hr>
            <h5 class="mt-4">Особиста інформація клієнта</h5>

            <div class="mb-3">
                <label for="first_name" class="form-label">Ім’я</label>
                <input type="text" name="first_name" id="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                @error('first_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="last_name" class="form-label">Прізвище</label>
                <input type="text" name="last_name" id="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                @error('last_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Телефон</label>
                <input type="tel" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="+380..." required>
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 mt-3">
                <label class="form-label">Зіскануйте штрих-код</label>
                <input type="text" name="barcode" class="form-control @error('barcode') is-invalid @enderror" value="{{ old('barcode') }}" required>
                @error('barcode')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-3">Сформувати вхідний документ</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('client_search');
    const results = document.getElementById('client_results');
    const clientIdInput = document.getElementById('client_id');
    const clientSelected = document.getElementById('client_selected');

    searchInput.addEventListener('input', function () {
        const query = this.value.trim();
        results.innerHTML = '';
        if (query.length < 2) return;

        fetch(`/clients/search?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                results.innerHTML = '';
                if (data.length === 0) {
                    const noRes = document.createElement('div');
                    noRes.classList.add('list-group-item', 'text-muted');
                    noRes.textContent = 'Клієнта не знайдено';
                    results.appendChild(noRes);
                    return;
                }

                data.forEach(client => {
                    const item = document.createElement('div');
                    item.classList.add('list-group-item');
                    item.textContent = `${client.first_name} ${client.last_name} (${client.phone})`;
                    item.addEventListener('click', () => {
                        document.getElementById('first_name').value = client.first_name;
                        document.getElementById('last_name').value = client.last_name;
                        document.getElementById('phone').value = client.phone;
                        clientIdInput.value = client.id;
                        results.innerHTML = '';
                        searchInput.value = `${client.first_name} ${client.last_name}`;

                        clientSelected.style.display = 'block';
                        clientSelected.innerHTML = `
                            <strong>✅ Вибраний клієнт:</strong><br>
                            ${client.first_name} ${client.last_name}<br>
                            <small>${client.phone}</small>
                            <button type="button" class="btn btn-sm btn-outline-danger mt-2" id="clearClient">Змінити клієнта</button>
                        `;
                        
                        document.getElementById('first_name').readOnly = true;
                        document.getElementById('last_name').readOnly = true;
                        document.getElementById('phone').readOnly = true;

                        document.getElementById('clearClient').addEventListener('click', () => {
                            clientIdInput.value = '';
                            clientSelected.style.display = 'none';
                            searchInput.value = '';
                            document.getElementById('first_name').value = '';
                            document.getElementById('last_name').value = '';
                            document.getElementById('phone').value = '';
                            document.getElementById('first_name').readOnly = false;
                            document.getElementById('last_name').readOnly = false;
                            document.getElementById('phone').readOnly = false;
                        });
                    });
                    results.appendChild(item);
                });
            })
            .catch(() => {
                results.innerHTML = '<div class="list-group-item text-danger">Помилка завантаження</div>';
            });
    });
});
</script>
@endpush