@extends('layout')

@section('content')

<div class="container mt-4">
    <h1 class="mb-4 text-center">Звіт відвідувань</h1>

@if($visitLogs->count() > 0)
    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Клієнт</th>
                    <th>Локація</th>
                    <th>Тип відвідування</th>
                    <th>Створив</th>
                    <th>Дата</th>
                </tr>
            </thead>
            <tbody>
                @foreach($visitLogs as $log)
                    <tr>
                        <td>{{ $log->id }}</td>
                        <td>{{ $log->subscription?->client?->first_name ?? '—' }} {{ $log->subscription?->client?->last_name ?? '' }}</td>
                        <td>{{ $log->location?->title ?? '—' }}</td>
                        <td>{{ $log->type }}</td>
                        <td>{{ $log->createdBy?->first_name ?? '—' }} {{ $log->createdBy?->last_name ?? '' }}</td>
                        <td>{{ $log->created_at->format('d.m.Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <p class="text-center text-muted">Записів відвідувань ще немає</p>
@endif

</div>
@endsection
