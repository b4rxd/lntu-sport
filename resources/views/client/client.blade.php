@extends('layout')

@push('styles')
<style>
    #client_results {
        position: absolute;
        background: white;
        border: 1px solid #ddd;
        border-radius: 0.5rem;
        width: 100%;
        max-height: 250px;
        overflow-y: auto;
        z-index: 1000;
    }
    #client_results .list-group-item {
        cursor: pointer;
    }
    #client_results .list-group-item:hover {
        background-color: #f1f1f1;
    }
    .card-assignment, .subscription-info {
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #ddd;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-center mt-5">
    <div class="card shadow-lg p-4" style="max-width: 700px; width: 100%;">
        <h3 class="text-center mb-4">Пошук клієнта</h3>

        <div class="mb-3 position-relative">
            <label class="form-label">Введіть ім’я, прізвище або телефон</label>
            <input type="text" id="client_search" class="form-control" placeholder="Почніть вводити...">
            <div id="client_results" class="list-group position-absolute w-100"></div>
        </div>
    </div>
</div>

{{-- Модальне вікно для повної інформації --}}
<div class="modal fade" id="clientModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Інформація про клієнта</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрити"></button>
      </div>
      <div class="modal-body" id="clientModalBody"></div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('client_search');
    const results = document.getElementById('client_results');

    const modalEl = document.getElementById('clientModal');
    const clientModal = new bootstrap.Modal(modalEl);

    function formatDate(dateStr) {
        if (!dateStr) return '-';
        const date = new Date(dateStr);
        return date.toLocaleDateString('uk-UA', { day: '2-digit', month: 'long', year: 'numeric' });
    }

    searchInput.addEventListener('input', function () {
        const query = this.value.trim();
        results.innerHTML = '';
        if (query.length < 2) return;

        fetch(`/clients/search?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                results.innerHTML = '';
                if (data.length === 0) {
                    const noRes = document.createElement('div');
                    noRes.classList.add('list-group-item', 'text-muted');
                    noRes.textContent = 'Клієнта не знайдено';
                    results.appendChild(noRes);
                    return;
                }

                data.forEach(client => {
                    const item = document.createElement('div');
                    item.classList.add('list-group-item');
                    item.textContent = `${client.first_name} ${client.last_name} (${client.phone})`;
                    item.addEventListener('click', () => {
                        results.innerHTML = '';
                        searchInput.value = `${client.first_name} ${client.last_name}`;

                        fetch(`/clients/info/${client.id}`)
                            .then(res => res.json())
                            .then(data => {
                                let html = `<p><strong>Ім’я:</strong> ${data.first_name ?? '-'}</p>
                                            <p><strong>Прізвище:</strong> ${data.last_name ?? '-'}</p>
                                            <p><strong>Телефон:</strong> ${data.phone ?? '-'}</p>
                                            <hr>`;

                                html += '<h6>Карти та абонементи:</h6>';

                                (data.card_assignments || []).forEach(a => {
                                    const card = a.card || {};
                                    const subscription = a.subscription || {};
                                    const price = subscription.price || {};
                                    const product = price.product || {};
                                    const locations = product.locations || [];

                                    html += `<div class="row mb-2">
                                        <div class="col-md-6 subscription-info">
                                            <div><strong>Абонемент:</strong> ${price.title ?? '—'}</div>
                                            <div><strong>Ціна:</strong> ${price.amount_in_uah ?? 0} ₴</div>
                                            <div><strong>Статус:</strong> ${subscription.status ?? '—'}</div>
                                            <div><strong>Закінчення:</strong> ${formatDate(subscription.end_date)}</div>
                                            <div><strong>Оплати:</strong> ${(subscription.subscriptions_payments || []).reduce((sum, p) => sum + (p.paid_amount ?? 0), 0)} ₴</div>
                                            <div><strong>Локації:</strong> ${locations.map(l => l.title).join(', ') || '—'}</div>
                                        </div>
                                        <div class="col-md-6 card-assignment">
                                            <div><strong>Штрих-код:</strong> ${card.barcode ?? '—'}</div>
                                            <div><strong>Статус:</strong> ${card.status ?? '—'}</div>
                                            <div><strong>Видано:</strong> ${formatDate(a.assigned_date)}</div>
                                            <div><strong>Повернено:</strong> ${formatDate(a.returned_date)}</div>
                                        </div>
                                    </div>`;
                                });

                                document.getElementById('clientModalBody').innerHTML = html;
                                clientModal.show();
                            })
                            .catch(() => alert('Помилка завантаження інформації про клієнта'));
                    });
                    results.appendChild(item);
                });
            })
            .catch(() => {
                results.innerHTML = '<div class="list-group-item text-danger">Помилка завантаження</div>';
            });
    });

    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !results.contains(e.target)) {
            results.innerHTML = '';
        }
    });
});
</script>
@endpush
