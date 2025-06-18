<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Zarządzanie salami konferencyjnymi</h2>
                <a href="/admin/rooms/create" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Dodaj nową salę
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

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php 
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                    ?>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nazwa</th>
                            <th>Pojemność</th>
                            <th>Wyposażenie</th>
                            <th>Opis</th>
                            <th>Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rooms as $room): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($room['name']); ?></td>
                                <td><?php echo htmlspecialchars($room['capacity']); ?> osób</td>
                                <td><?php echo htmlspecialchars($room['equipment']); ?></td>
                                <td><?php echo htmlspecialchars($room['description'] ?? ''); ?></td>
                                <td>
                                    <div class="d-grid gap-1">
                                        <a href="/admin/rooms/edit/<?php echo $room['id']; ?>" 
                                           class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i> Edytuj
                                        </a>
                                        <a href="/admin/rooms/delete/<?php echo $room['id']; ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Czy na pewno chcesz usunąć tę salę?')">
                                            <i class="bi bi-trash"></i> Usuń
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if (empty($rooms)): ?>
                <div class="alert alert-info">
                    Brak sal konferencyjnych w systemie.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div> 