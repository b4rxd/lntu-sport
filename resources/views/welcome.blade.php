<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Спорткомплекс ЛНТУ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .hero {
            position: relative;
            height: 90vh;
            background: url('{{ asset('images/sport-complex.jpg') }}') center/cover no-repeat;
        }
        .hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,.55);
        }
        .hero-content { position: relative; z-index: 2; }
        .info-block { border: 1px solid #ddd; border-radius: 10px; padding: 15px; margin-bottom: 15px; background-color: #f9f9f9; }
    </style>
</head>
<body>

<header class="bg-light shadow-sm">
    <div class="container d-flex justify-content-between align-items-center py-3">
        <img src="{{ asset('/images/svg/logo-lntu.svg') }}" height="45" alt="ЛНТУ">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#searchCardModal">Пошук картки</button>
    </div>
</header>

<section class="hero d-flex align-items-center text-center text-white">
    <div class="container hero-content">
        <h1 class="display-5 fw-bold mb-3">Спорткомплекс Луцького національного технічного університету</h1>
        <p class="lead">Сучасний простір для спорту, здоров’я та активного життя</p>
    </div>
</section>

<section class="py-5">
    <div class="container text-center">
        <h2 class="mb-3">Про нас</h2>
        <p class="text-muted mx-auto" style="max-width: 800px">
            Спорткомплекс ЛНТУ — це сучасні зали, басейн та інфраструктура для занять спортом,
            тренувань і оздоровлення студентів та працівників університету.
        </p>
    </div>
</section>

<section class="bg-light py-5">
    <div class="container">
        <h2 class="text-center mb-5">Локації спорткомплексу</h2>
        <div class="row g-4 justify-content-center">
            @forelse($locations as $location)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="text-primary">{{ $location->title }}</h5>
                            <p class="text-muted">{{ $location->description }}</p>

                            @php
                                $days = [
                                    1 => 'Понеділок',2 => 'Вівторок',3 => 'Середа',
                                    4 => 'Четвер',5 => "Пʼятниця",6 => 'Субота',7 => 'Неділя',
                                ];
                                $schedule = $location->regularSchedulers->keyBy('day_number');
                            @endphp

                            <hr><small class="fw-bold">Розклад:</small>
                            <ul class="list-unstyled mb-0">
                                @foreach($days as $dayNum => $dayName)
                                    @if(isset($schedule[$dayNum]))
                                        <li>{{ $dayName }}: {{ $schedule[$dayNum]->time_from->format('H:i') }} – {{ $schedule[$dayNum]->time_till->format('H:i') }}</li>
                                    @else
                                        <li class="text-muted">{{ $dayName }} — Вихідний</li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted">Локації відсутні</div>
            @endforelse
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Контакти</h2>

        <div class="row g-4 align-items-stretch">
            <div class="col-md-5">
                <div class="h-100 p-4 border rounded bg-light">
                    <p><strong>Адреса:</strong><br>м. Луцьк, вул. Львівська, 75</p>
                    <p><strong>Email:</strong><br>sport@lntu.edu.ua</p>
                    <p><strong>Телефон:</strong><br>+38</p>
                </div>
            </div>            <div class="col-md-7">
                <div class="h-100 rounded overflow-hidden shadow-sm">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14143.6017637671!2d25.31022912692364!3d50.71952543186223!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4725999f6e2df683%3A0xf91cf1fdf1ea3404!2z0KHQv9C-0YDRgtC60L7QvNC_0LvQtdC60YEg0JvQndCi0KM!5e1!3m2!1suk!2sua!4v1768481725011!5m2!1suk!2sua"
                        width="100%"
                        height="100%"
                        style="border:0; min-height:300px;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</section>


<footer class="bg-dark text-white text-center py-3">Спорткомплекс ЛНТУ</footer>

<div class="modal fade" id="searchCardModal">
    <div class="modal-dialog">
        <form class="modal-content" id="barcodeForm">
            <div class="modal-header">
                <h5 class="modal-title">Пошук картки</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="barcodeInput" class="form-control" placeholder="Введіть штрих-код" required>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Знайти</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="cardResultModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Інформація по картці</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="cardModalBody"></div>
        </div>
    </div>
</div>

<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
    <div id="toastMessage" class="toast align-items-center text-bg-primary border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body" id="toastBody"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('barcodeForm');
    const searchModalEl = document.getElementById('searchCardModal');
    const resultModalEl = document.getElementById('cardResultModal');
    const cardModal = new bootstrap.Modal(resultModalEl);

    const showToast = (msg, type='primary') => {
        const toastEl = document.getElementById('toastMessage');
        const toastBody = document.getElementById('toastBody');
        toastBody.textContent = msg;
        toastEl.className = `toast align-items-center text-bg-${type} border-0`;
        new bootstrap.Toast(toastEl, { delay: 3000 }).show();
    };

    const formatDate = dateStr => {
        if (!dateStr) return '—';
        const date = new Date(dateStr);
        return date.toLocaleDateString('uk-UA', { day:'2-digit', month:'long', year:'numeric' });
    };

    form.addEventListener('submit', function(e){
        e.preventDefault();
        const barcode = document.getElementById('barcodeInput').value.trim();
        const token = document.querySelector('meta[name="csrf-token"]').content;

        if(!barcode) return;

        fetch(`/card/${barcode}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }
        })
        .then(r => r.ok ? r.json() : Promise.reject())
        .then(data => {
            let html = `<div class="info-block">
                <p><strong>Картка:</strong> ${data.barcode}</p>
                <p><strong>Статус:</strong> ${data.status}</p>
            </div>`;

            if(data.subscription){
                html += `<div class="info-block">
                    <p><strong>Абонемент:</strong> ${data.subscription.product?.title ?? '—'}</p>
                    <p><strong>Дійсний до:</strong> ${formatDate(data.subscription.end_date)}</p>
                </div>`;
            }

            if(data.locations?.length){
                html += `<div class="info-block"><strong>Локації:</strong><ul>`;
                data.locations.forEach(l => html += `<li>${l.title}</li>`);
                html += `</ul></div>`;
            }

            document.getElementById('cardModalBody').innerHTML = html;

            bootstrap.Modal.getInstance(searchModalEl).hide();
            cardModal.show();
        })
        .catch(() => showToast('Картку не знайдено.', 'warning'));
    });
});
</script>
</body>
</html>
