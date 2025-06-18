<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h2>Edycja sali: <?php echo htmlspecialchars($room['name']); ?></h2>
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

                    <form action="/admin/rooms/edit/<?php echo $room['id']; ?>" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nazwa sali</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?php echo htmlspecialchars($room['name']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="capacity" class="form-label">Pojemność</label>
                            <input type="number" class="form-control" id="capacity" name="capacity" 
                                   value="<?php echo htmlspecialchars($room['capacity']); ?>" required min="1">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Opis</label>
                            <textarea class="form-control" id="description" name="description" 
                                      rows="3"><?php echo htmlspecialchars($room['description']); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="equipment" class="form-label">Wyposażenie</label>
                            <textarea class="form-control" id="equipment" name="equipment" 
                                      rows="3"><?php echo htmlspecialchars($room['equipment']); ?></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/admin/rooms" class="btn btn-secondary">Powrót</a>
                            <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 