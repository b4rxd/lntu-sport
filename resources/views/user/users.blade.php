@extends('layout')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 text-center">Список користувачів</h1>

    <div class="text-end mb-3">
        <a href="{{ route('user.createAdmin', ['role' => 'admin']) }}" class="btn btn-primary me-2">
            Створити адміністратора
        </a>
        <a href="{{ route('user.createUser', ['role' => 'user']) }}" class="btn btn-success">
            Створити користувача
        </a>
    </div>

    @if($users->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Ім’я</th>
                        <th>Прізвище</th>
                        <th>Email</th>
                        <th>Роль</th>
                        <th class="text-center">Статус</th>
                        <th class="text-center">Дії</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->first_name ?? '—' }}</td>
                            <td>{{ $user->last_name ?? '—' }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->roles }}</td>
                            <td class="text-center">
                                @if($user->enabled)
                                    <span class="badge bg-success">Активний</span>
                                @else
                                    <span class="badge bg-secondary">Вимкнений</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <form action="{{ route('user.toggle', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="btn btn-sm {{ $user->enabled ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                        {{ $user->enabled ? 'Вимкнути' : 'Увімкнути' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-center text-muted">Користувачів ще немає</p>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endpush