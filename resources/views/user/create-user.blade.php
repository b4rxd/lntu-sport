@extends('layout')

@push('styles')
    <link href="{{ asset('resources/css/survey.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="d-flex justify-content-center align-items-start mt-4">
    <div class="card shadow-lg p-4 text-left" style="max-width: 800px; width: 100%;">

        <h1 class="mb-4 text-center">Новий користувач</h1>

        <form action="{{ route('user.storeUser') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="text" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Ім'я</label>
                <input type="text" name="first_name" class="form-control" min="1" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Прізвище</label>
                <input type="text" name="last_name" class="form-control" min="1" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Пароль</label>
                <input type="password" name="password" class="form-control" min="8" required>
            </div>

            <h5 class="mt-4">Дозволи користувача</h5>

            <div class="mb-3">
                 <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="permissions[]" value="sell" id="sell">
                    <label class="form-check-label" for="sell">Продаж</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="permissions[]" value="create_product" id="create_product">
                    <label class="form-check-label" for="create_product">Створення продуктів</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="permissions[]" value="create_location" id="create_location">
                    <label class="form-check-label" for="create_location">Створення локацій</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="permissions[]" value="edit_location" id="edit_location">
                    <label class="form-check-label" for="edit_location">Редагування локацій</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="permissions[]" value="create_return" id="create_return">
                    <label class="form-check-label" for="create_return">Створення повернень</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="permissions[]" value="create_return" id="create_return">
                    <label class="form-check-label" for="create_prolongation">Створення продовжень</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100">Створити користувача</button>
        </form>
    </div>
</div>
@endsection
