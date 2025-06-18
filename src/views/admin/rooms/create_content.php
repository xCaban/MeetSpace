<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Dodaj nową salę konferencyjną</h2>
                <a href="/admin/rooms" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Powrót do listy
                </a>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form action="/admin/rooms/create" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nazwa sali</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="capacity" class="form-label">Pojemność</label>
                            <input type="number" class="form-control" id="capacity" name="capacity" min="1" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Opis</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="equipment" class="form-label">Wyposażenie</label>
                            <input type="text" class="form-control" id="equipment" name="equipment" 
                                   placeholder="np. projektor, tablica, klimatyzacja">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Dodaj salę
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 