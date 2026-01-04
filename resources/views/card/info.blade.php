@extends('layout')

@push('styles')
<style>
    .info-block {
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
        background-color: #f9f9f9;
    }
</style>
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
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Інформація про карту</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрити"></button>
      </div>
      <div class="modal-body" id="cardModalBody"></div>
    </div>
  </div>
</div>

{{-- Toast container --}}
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
    <div id="toastMessage" class="toast align-items-center text-bg-primary border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body" id="toastBody"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('barcodeForm');
    const modalEl = document.getElementById('cardModal');
    const cardModal = new bootstrap.Modal(modalEl);

    // Toast function
    function showToast(message, type = 'primary') {
        const toastEl = document.getElementById('toastMessage');
        const toastBody = document.getElementById('toastBody');

        toastBody.textContent = message;
        toastEl.className = `toast align-items-center text-bg-${type} border-0`;

        const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
        toast.show();
    }

    function formatDate(dateStr) {
        if (!dateStr) return '—';
        const date = new Date(dateStr);
        return date.toLocaleDateString('uk-UA', {
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        });
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const barcode = document.getElementById('barcodeInput').value.trim();
        const token = document.querySelector('input[name="_token"]').value;

        if (!barcode) return;

        fetch(`/card/info/${barcode}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({})
        })
        .then(res => {
            if (!res.ok) throw new Error();
            return res.json();
        })
        .then(data => {
            if (!data || !data.id) {
                showToast('Карту не знайдено', 'warning');
                return;
            }

            let html = `
                <div class="info-block">
                    <h5>Дані карти</h5>
                    <p><strong>Штрих-код:</strong> ${data.barcode ?? '—'}</p>
                    <p><strong>Статус:</strong> ${data.status ?? '—'}</p>
                    <p><strong>Дата створення:</strong> ${formatDate(data.created_at)}</p>
                </div>
            `;

            if (data.client) {
                const c = data.client;
                html += `
                    <div class="info-block">
                        <h5>Карта прив’язана до клієнта</h5>
                        <p><strong>Ім’я:</strong> ${c.first_name ?? '—'}</p>
                        <p><strong>Прізвище:</strong> ${c.last_name ?? '—'}</p>
                        <p><strong>Телефон:</strong> ${c.phone ?? '—'}</p>
                        <p><strong>Дата видачі:</strong> ${formatDate(c.assigned_date)}</p>
                    </div>
                `;
            } else {
                html += `<p class="text-muted text-center mt-3">Картка наразі не прив’язана до жодного клієнта</p>`;
            }

            if (data.subscription) {
                const s = data.subscription;
                html += `
                    <div class="info-block">
                        <h5>Підписка</h5>
                         ${s.product ? `<p><strong>Продукт:</strong> ${s.product.title}</p>` : ''}
                        <p><strong>Дійсна до:</strong> ${formatDate(s.end_date)}</p>
                        ${s.price ? `<p><strong>Ціна:</strong> ${s.price.amount_in_uah} грн (${s.price.title})</p>` : ''}
                        ${s.last_payment ? `<p><strong>Остання оплата:</strong> ${s.last_payment.paid_amount} грн, ${formatDate(s.last_payment.paid_at)}</p>` : '<p><em>Оплат ще не було</em></p>'}
                        <p><strong>Кількість використань:</strong> ${s.product.infinite ? "нескінченна" : s.count_usage}</p>
                    </div>
                `;
            }

            if (data.locations && data.locations.length > 0) {
                html += `<div class="info-block">
                            <h5>Локації, де дійсна карта</h5>
                            <ul>`;
                data.locations.forEach(loc => {
                    html += `<li>${loc.title}</li>`;
                });
                html += `</ul></div>`;
            }

            html += `
                <div class="d-flex mt-3" style="gap: 10px;">
                    <button type="button" class="btn btn-success" id="enterCardBtn">Вхід</button>
                    <button type="button" class="btn btn-warning" id="returnCardBtn">Повернути картку</button>
                    <button type="button" class="btn btn-primary" id="renewSubscriptionBtn">Продовжити абонемент</button>
                </div>
            `;

            document.getElementById('cardModalBody').innerHTML = html;
            cardModal.show();

            document.getElementById('enterCardBtn').onclick = function() {
                if (!confirm('Підтвердити вхід?')) return;

                const subscriptionId = data.subscription?.id;
                const locationId = data.locations && data.locations.length > 0 ? data.locations[0].id : null;

                if (!subscriptionId || !locationId) {
                    showToast('Неможливо зафіксувати візит: відсутня підписка або локація', 'danger');
                    return;
                }

                fetch('/visit', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({ subscription_id: subscriptionId, location_id: locationId })
                })
                .then(async res => {
                    const data = await res.json();

                    if (res.ok && data.success) {
                        showToast('Візит зафіксовано!', 'success');
                        cardModal.hide();
                    } else {
                        showToast(data.message ?? 'Сталася помилка при створенні візиту.', 'danger');
                    }
                })
                .catch(() => showToast('Сталася помилка при створенні візиту.', 'danger'));
            };

            document.getElementById('returnCardBtn').onclick = function () {
                if (!confirm('Повернути картку?')) return;

                fetch(`/card/${data.id}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({})
                })
                .then(async res => {
                    const result = await res.json();

                    if (res.ok && result.success) {
                        showToast('Картку повернено!', 'success');
                        cardModal.hide();
                    } else {
                        showToast(result.message ?? 'Помилка при поверненні картки', 'danger');
                    }
                })
                .catch(() => showToast('Сталася помилка при поверненні картки.', 'danger'));
            };

            document.getElementById('renewSubscriptionBtn').onclick = function () {
                if (!confirm('Продовжити абонемент?')) return;

                const cardId = data.id;

                if (!cardId) {
                    showToast('Не знайдено картку', 'danger');
                    return;
                }

                fetch(`/subscription/prolong/${cardId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({})
                })
                .then(async res => {
                    const result = await res.json();

                    if (res.ok) {
                        showToast('Абонемент продовжено успішно!', 'success');
                        cardModal.hide();
                    } else {
                        showToast(result.message ?? 'Помилка під час продовження абонементу.', 'danger');
                    }
                })
                .catch(() => showToast('Сталася помилка.', 'danger'));
            };


        })
        .catch(() => {
            showToast('Карту не знайдено або сталася помилка.', 'warning');
        });
    });

    modalEl.addEventListener('hidden.bs.modal', function () {
        document.getElementById('cardModalBody').innerHTML = '';
    });
});
</script>
@endpush