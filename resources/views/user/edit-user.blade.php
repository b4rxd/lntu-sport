@extends('layout')

@push('styles')
    <link href="{{ asset('resources/css/survey.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="d-flex justify-content-center align-items-start mt-4">
    <div class="card shadow-lg p-4 text-left" style="max-width: 800px; width: 100%;">

        <h1 class="mb-4 text-center">Редагування користувача</h1>

        <form action="{{ route('user.update', $editUser->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input
                    type="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email', $editUser->email) }}"
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Ім'я</label>
                <input
                    type="text"
                    name="first_name"
                    class="form-control @error('first_name') is-invalid @enderror"
                    value="{{ old('first_name', $editUser->first_name) }}"
                >
                @error('first_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Прізвище</label>
                <input
                    type="text"
                    name="last_name"
                    class="form-control @error('last_name') is-invalid @enderror"
                    value="{{ old('last_name', $editUser->last_name) }}"
                >
                @error('last_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Новий пароль (необовʼязково)</label>
                <input
                    type="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                >
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

          @if($editUser->role === 'user')
            <h5 class="mt-4">Дозволи користувача</h5>

            @php
                $permissions = $editUser->access_list ?? [];
            @endphp

            <div class="mb-3">
                <div class="form-check">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="permissions[]"
                        value="sell"
                        id="sell"
                        {{ in_array('sell', $permissions) ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="sell">Продаж</label>
                </div>

                <div class="form-check">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="permissions[]"
                        value="create_product"
                        id="create_product"
                        {{ in_array('create_product', $permissions) ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="create_product">Створення продуктів</label>
                </div>

                <div class="form-check">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="permissions[]"
                        value="create_location"
                        id="create_location"
                        {{ in_array('create_location', $permissions) ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="create_location">Створення локацій</label>
                </div>

                <div class="form-check">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="permissions[]"
                        value="edit_location"
                        id="edit_location"
                        {{ in_array('edit_location', $permissions) ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="edit_location">Редагування локацій</label>
                </div>

                <div class="form-check">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="permissions[]"
                        value="create_return"
                        id="create_return"
                        {{ in_array('create_return', $permissions) ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="create_return">Створення повернень</label>
                </div>

                <div class="form-check">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="permissions[]"
                        value="create_prolongation"
                        id="create_prolongation"
                        {{ in_array('create_prolongation', $permissions) ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="create_prolongation">Створення продовжень</label>
                </div>
            </div>
        @endif
            <button type="submit" class="btn btn-primary w-100">
                Зберегти зміни
            </button>
        </form>
    </div>
</div>
@endsection
