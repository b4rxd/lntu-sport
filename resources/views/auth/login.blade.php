@extends('layout')

@push('styles')
    <link href="{{ asset('resources/css/survey.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="d-flex justify-content-center align-items-start">
    <div class="card shadow-lg p-4 text-left" style="max-width: 500px; width: 100%;">
        <h1 class="mb-4 text-center">Логін</h1>
        
        <form action="{{ route('post-login') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="email" class="form-label">Електронна пошта</label>
                <input type="text" class="form-control" id="email" name="email" placeholder="Введіть електронну пошту" required>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Пароль</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Введіть пароль" required>
            </div>
            
            <div class="d-grid">
                <button type="submit" class="btn btn-success">Увійти</button>
            </div>
        </form>
    </div>
</div>
@endsection
