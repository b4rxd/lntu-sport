@extends('layout')

@push('styles')
    <link href="{{ asset('resources/css/survey.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="d-flex justify-content-center align-items-start">
        <div class="card shadow-lg p-4 text-left" style="max-width: 500px; width: 100%;">
            @auth
                <h1 class="mb-3">Вітаю, <span class="text-primary">{{ auth()->user()->first_name }}</span>!</h1>
                <p class="mb-3">Ви вже увійшли в систему.</p>
            @else
                <h1 class="mb-3">Вас вітає <span class="text-primary">CRM ЛНТУ!</span></h1>
                <p class="mb-3">Залогінтесь тут <a href="/login">Увійти</a></p>
            @endauth

            <p class="text-muted">Якщо у вас немає доступу — зверніться до адміністратора системи</p>
        </div>
    </div>
@endsection