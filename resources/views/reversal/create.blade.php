@extends('layout')

@push('styles')
    <link href="{{ asset('resources/css/survey.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="d-flex justify-content-center mt-5">
    <div class="card shadow p-4" style="width: 500px;">
        <h3 class="text-center mb-3">Пошук карти по штрих-коду</h3>
        <form id="barcodeForm">
            @csrf
            <div class="mb-3">
                <label class="form-label">Введіть номер штрих-коду</label>
                <input type="text" name="barcode" id="barcodeInput" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Знайти</button>
        </form>
    </div>
</div>

<div class="modal fade" id="cardModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Інформація про карту</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрити"></button>
      </div>
      <div class="modal-body" id="cardModalBody"></div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
$(document).ready(function() {
    var modalEl = document.getElementById('cardModal');
    var cardModal = new bootstrap.Modal(modalEl);

    function formatDate(dateStr) {
        if (!dateStr) return '-';
        const date = new Date(dateStr);
        return date.toLocaleDateString('uk-UA', {
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        });
    }

    $('#barcodeForm').on('submit', function(e) {
        e.preventDefault();
        let barcode = $('#barcodeInput').val();
        let token = $('input[name="_token"]').val();

        $.ajax({
            url: "/card/info/" + barcode,
            type: 'POST',
            data: {_token: token},
            success: function(response) {
                $('#cardModalBody').html(`
                    <p><strong>Дійсний від:</strong> ${formatDate(response.valid_from)}</p>
                    <p><strong>Дійсний до:</strong> ${formatDate(response.valid_till)}</p>
                    <p><strong>Використано:</strong> ${response.count_usage}</p>
                    <p><strong>Залишилось використань:</strong> ${response.max_usage}</p>

                    <div class="modal-body" id="cardModalBody">
                        <form id="refundForm" action="{{ route('reversal.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="cardId" id="refundCardId" value="${response.id}">

                            <div class="mb-3">
                                <label class="form-label">Сума повернення</label>
                                <input type="number" name="amount_in_uah" id="refundAmount" class="form-control" placeholder="Введіть суму" min="1" step="0.01" required>
                            </div>

                            <button type="submit" class="btn btn-success w-100">Здійснити повернення</button>
                        </form>
                    </div>
                `);

                cardModal.show();
            },
            error: function() {
                alert('Карту не знайдено');
            }
        });
    });

    modalEl.addEventListener('hidden.bs.modal', function () {
        $('#cardModalBody').html('');
    });
});
</script>
@endpush
