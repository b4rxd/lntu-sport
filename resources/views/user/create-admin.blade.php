@extends('layout')

@push('styles')
    <link href="{{ asset('resources/css/survey.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="d-flex justify-content-center align-items-start mt-4">
    <div class="card shadow-lg p-4 text-left" style="max-width: 800px; width: 100%;">

        <h1 class="mb-4 text-center">Новий адміністратор</h1>

        <form action="{{ route('user.storeAdmin') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="text" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Ім`я</label>
                <input type="text" name="first_name" class="form-control" min="1" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Прізвище</label>
                <input type="text" name="last_name" class="form-control" min="1" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Пароль</label>
                <input type="password" name="password" class="form-control" min="1" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Створити адміністратора</button>
        </form>
    </div>
</div>
@endsection
