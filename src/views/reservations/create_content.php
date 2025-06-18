<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h2>Rezerwacja sali: <?php echo htmlspecialchars($room['name']); ?></h2>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?php 
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <form action="/reservations/create" method="POST">
                        <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
                        
                        <div class="mb-3">
                            <label for="start_time" class="form-label">Data i godzina rozpoczęcia</label>
                            <input type="datetime-local" class="form-control" id="start_time" name="start_time" required>
                        </div>

                        <div class="mb-3">
                            <label for="end_time" class="form-label">Data i godzina zakończenia</label>
                            <input type="datetime-local" class="form-control" id="end_time" name="end_time" required>
                        </div>

                        <div class="mb-3">
                            <label for="purpose" class="form-label">Cel rezerwacji</label>
                            <textarea class="form-control" id="purpose" name="purpose" rows="3" required></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/rooms" class="btn btn-secondary">Powrót</a>
                            <button type="submit" class="btn btn-primary">Zarezerwuj</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');

    // Ustaw minimalną datę na teraz
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    startTimeInput.min = now.toISOString().slice(0, 16);
    endTimeInput.min = now.toISOString().slice(0, 16);

    // Sprawdź czy data zakończenia jest po dacie rozpoczęcia
    startTimeInput.addEventListener('change', function() {
        endTimeInput.min = this.value;
        if (endTimeInput.value && endTimeInput.value < this.value) {
            endTimeInput.value = this.value;
        }
    });
});
</script> 