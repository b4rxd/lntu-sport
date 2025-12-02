@extends('layout')

@push('styles')
    <link href="{{ asset('resources/css/survey.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="d-flex justify-content-center align-items-start mt-4">
    <div class="card shadow-lg p-4 text-left" style="max-width: 800px; width: 100%;">

        <h1 class="mb-4 text-center">Нова локація</h1>

        <form action="{{ route('locations.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Назва</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Опис</label>
                <textarea name="description" class="form-control" rows="3" required></textarea>
            </div>

            <hr>

            <h4>Графік роботи</h4>
            <div id="regular-list" class="mb-3"></div>
            <button type="button" class="btn btn-sm btn-outline-primary mb-3" onclick="addItem('regular')">
                Додати елемент
            </button>

            
            <hr>

            <button type="submit" class="btn btn-primary w-100">Створити локацію</button>
        </form>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/location-create.js') }}"></script>
@endpush
@endsection