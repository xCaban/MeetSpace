<div class="row mb-4">
    <div class="col-md-8 mb-4 mb-md-0">
        <div class="text-center mb-4">
            <img src="images/logo.png" alt="MeetSpace Logo" style="max-width: 200px; height: auto;">
        </div>
        <h1 class="text-center">Witaj w MeetSpace</h1>
        <p class="lead text-center">Twój prosty i efektywny system rezerwacji sal konferencyjnych.</p>
        <p>Dzięki MeetSpace możesz łatwo:</p>
        <ul>
            <li>Przeglądać dostępne sale konferencyjne</li>
            <li>Sprawdzać dostępność sal w czasie rzeczywistym</li>
            <li>Tworzyć i zarządzać rezerwacjami</li>
            <li>Przeglądać szczegóły i wyposażenie sal</li>
        </ul>
        <div class="mt-4 text-center">
            <a href="/rooms" class="btn btn-primary btn-lg">Przeglądaj sale</a>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="/register" class="btn btn-outline-primary btn-lg ms-2">Załóż konto!</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Szybki dostęp</h5>
                <div class="list-group">
                    <a href="/rooms" class="list-group-item list-group-item-action">
                        <i class="bi bi-door-open"></i> Zobacz wszystkie sale
                    </a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="/reservations" class="list-group-item list-group-item-action">
                            <i class="bi bi-calendar-check"></i> Moje rezerwacje
                        </a>
                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                            <a href="/admin/rooms" class="list-group-item list-group-item-action">
                                <i class="bi bi-gear"></i> Zarządzaj salami
                            </a>
                            <a href="/admin/reservations" class="list-group-item list-group-item-action">
                                <i class="bi bi-list-check"></i> Wszystkie rezerwacje
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="/login" class="list-group-item list-group-item-action">
                            <i class="bi bi-box-arrow-in-right"></i> Logowanie
                        </a>
                        <a href="/register" class="list-group-item list-group-item-action">
                            <i class="bi bi-person-plus"></i> Rejestracja
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-4 mb-4 mb-md-0">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-calendar-check display-4 text-primary mb-3"></i>
                <h5 class="card-title">Łatwa rezerwacja</h5>
                <p class="card-text">Prosty i intuicyjny interfejs do rezerwacji sal. Wystarczy wybrać salę, wybrać termin i gotowe!</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4 mb-md-0">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-clock-history display-4 text-primary mb-3"></i>
                <h5 class="card-title">Dostępność w czasie rzeczywistym</h5>
                <p class="card-text">Sprawdzaj dostępność sal w czasie rzeczywistym. Koniec z podwójnymi rezerwacjami i konfliktami w harmonogramie.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-gear display-4 text-primary mb-3"></i>
                <h5 class="card-title">Panel administracyjny</h5>
                <p class="card-text">Kompleksowy panel administracyjny do zarządzania salami, przeglądania wszystkich rezerwacji i utrzymania systemu.</p>
            </div>
        </div>
    </div>
</div> 