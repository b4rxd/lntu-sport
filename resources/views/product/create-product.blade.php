@extends('layout')

@push('styles')
    <link href="{{ asset('resources/css/survey.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="d-flex justify-content-center align-items-start mt-4">
    <div class="card shadow-lg p-4 text-left" style="max-width: 800px; width: 100%;">

        <h1 class="mb-4 text-center">Новий продукт</h1>

        <form action="{{ route('products.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Назва</label>
                <input type="text" name="title" class="form-control" required>
            </div>


            <div class="mb-3">
                <label class="form-label">Кількість використань</label>
                <input type="number" name="count_usage" id="count_usage" class="form-control" min="1">
            </div>
                        
            <div class="form-check mb-3">
               <input type="hidden" name="infinite" value="0">
                <input class="form-check-input" type="checkbox" name="infinite" id="infinite" value="1">
                <label class="form-check-label" for="infinite">
                    Безліч використань
                </label>
            </div>

            <div class="mb-3">
                <label class="form-label">Тип</label>
                <select name="type" class="form-select" required>
                    <option value="one_time">Одноразовий</option>
                    <option value="monthly">Місячний</option>
                    <option value="yearly">Річний</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Опис</label>
                <textarea name="description" class="form-control" rows="4" required></textarea>
            </div>

            <hr>

            <h4>Доступні локації</h4>
            <div class="mb-3">
                @foreach($locations as $location)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="locations[]" value="{{ $location->id }}" id="loc{{ $location->id }}">
                        <label class="form-check-label" for="loc{{ $location->id }}">
                            {{ $location->title }}
                        </label>
                    </div>
                @endforeach
            </div>

            <button type="submit" class="btn btn-primary w-100">Створити продукт</button>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const infiniteCheckbox = document.getElementById('infinite');
    const countField = document.getElementById('count_usage');

    function toggleCount() {
        if (infiniteCheckbox.checked) {
            countField.disabled = true;
            countField.value = '';
        } else {
            countField.disabled = false;
        }
    }

    infiniteCheckbox.addEventListener('change', toggleCount);
    toggleCount();
});
</script>
@endpush