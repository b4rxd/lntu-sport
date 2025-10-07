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
        </style>
    </head>
    <body>
        <header class="bg-light shadow-sm">
            <div class="container d-flex align-items-center justify-content-between py-2">
                
                <div class="d-flex align-items-center">
                    <img src="{{ asset('/images/svg/logo-lntu.svg') }}" alt="Логотип" height="40" class="me-2">
                </div>

                <nav class="position-absolute start-50 translate-middle-x d-none d-md-flex gap-4">
                    <a href="{{ route('card.info') }}" class="text-decoration-none text-dark">Інформація по штрих-коду</a>
                    @auth
                        <a href="{{ route('locations.index') }}" class="text-decoration-none text-dark">Локації</a>
                        <a href="{{ route('products.index') }}" class="text-decoration-none text-dark">Продукти</a>
                        <a href="{{ route('reversal.index') }}" class="text-decoration-none text-dark">Повернення</a>
                        <a href="/location/list" class="text-decoration-none text-dark">Пролонгації</a>
                        <a href="/location/list" class="text-decoration-none text-dark">Звіт потоку</a>
                        <a href={{ route('report.sale.index') }} class="text-decoration-none text-dark">Звіт продажів</a>
                        <a href="/location/list" class="text-decoration-none text-dark">Користувачі</a>
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

        <footer class="bg-light shadow">
            <div class="container d-flex justify-content-center py-2">
                <nav class="d-flex gap-4">
                    <a href="/about" class="text-decoration-none text-dark">Про нас</a>
                    <a href="/contacts" class="text-decoration-none text-dark">Контакти</a>
                </nav>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        @stack('scripts')
    </body>
    </html>