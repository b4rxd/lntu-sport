@extends('layout')

@push('styles')
    <link href="{{ asset('resources/css/survey.css') }}" rel="stylesheet">
    <style>
        .is-invalid {
            border-color: #dc3545;
        }
        .invalid-feedback {
            display: block;
        }
    </style>
@endpush

@section('content')
<div class="d-flex justify-content-center align-items-start">
    <div class="card shadow-lg p-4 text-left" style="max-width: 500px; width: 100%;">
        <h1 class="mb-4 text-center">Логін</h1>
        
        <form action="{{ route('post-login') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="email" class="form-label">Електронна пошта</label>
                <input 
                    type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Введіть електронну пошту"
                    required
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Пароль</label>
                <input 
                    type="password"
                    class="form-control @error('password') is-invalid @enderror"
                    id="password"
                    name="password"
                    placeholder="Введіть пароль"
                    required
                >
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-grid">
                <button type="submit" class="btn btn-success">Увійти</button>
            </div>
        </form>
    </div>
</div>
@endsection
