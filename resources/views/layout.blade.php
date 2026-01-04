    <!DOCTYPE html>
    <html lang="uk">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title', 'CRM')</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/app.css">

        @stack('styles')

        <style>
            html, body {
                height: 100%;
            }

            body {
                display: flex;
                flex-direction: column;
            }

            main {
                flex: 1;
            }

            .dropdown-menu {
                z-index: 1055;
                position: absolute !important;
            }

            header {
                position: relative;
                z-index: 1040;
            }
        </style>
    </head>
    <body>
        <header class="bg-light shadow-sm">
            <div class="container d-flex align-items-center justify-content-between py-2">
                
                <div class="d-flex align-items-center">
                    <a href="{{ route('welcome') }}"> <img src="{{ asset('/images/svg/logo-lntu.svg') }}" alt="Логотип" height="40" class="me-2"></a>
                </div>

                <nav class="position-absolute start-50 translate-middle-x d-none d-md-flex gap-4">
                    @auth
                        <div class="dropdown">
                            <a class="text-decoration-none text-dark dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                Пошук
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('card.info') }}">Пошук картки</a></li>
                                <li><a class="dropdown-item" href="{{ route('client.index') }}">Пошук клієнта</a></li>
                            </ul>
                        </div>
                        <div class="dropdown">
                            <a class="text-decoration-none text-dark dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                Управління
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('locations.index') }}">Локації</a></li>
                                <li><a class="dropdown-item" href="{{ route('products.index') }}">Продукти</a></li>
                                <!-- <li><a class="dropdown-item" href="{{ route('reversal.index') }}">Повернення</a></li>
                                <li><a class="dropdown-item" href="/location/list">Пролонгації</a></li> -->
                            </ul>
                        </div>

                        <div class="dropdown">
                            <a class="text-decoration-none text-dark dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                Звіти
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('visit.index') }}">Звіт потоку</a></li>
                                <li><a class="dropdown-item" href="{{ route('report.sale.index') }}">Продажі</a></li>
                            </ul>
                        </div>
                        <a class="dropdown-item" href="{{ route('user.index') }}">Користувачі</a>
                    @endauth
                </nav>


                <div>
                    <div>
                        @auth
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary">Вийти</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-primary">Увійти</a>
                        @endauth
                    </div>
                </div>

            </div>
        </header>

        <main class="container py-5">
            @yield('content')
        </main>

        <footer class="bg-dark text-white text-center py-3">Спорткомплекс ЛНТУ</footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        @stack('scripts')
    </body>
    </html>