<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2>Zarządzanie rezerwacjami</h2>
            
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
                            <th>Sala</th>
                            <th>Użytkownik</th>
                            <th>Data</th>
                            <th>Godzina rozpoczęcia</th>
                            <th>Godzina zakończenia</th>
                            <th>Cel</th>
                            <th>Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservations as $reservation): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($reservation['room_name']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['user_name']); ?></td>
                                <td><?php echo date('Y-m-d', strtotime($reservation['start_time'])); ?></td>
                                <td><?php echo date('H:i', strtotime($reservation['start_time'])); ?></td>
                                <td><?php echo date('H:i', strtotime($reservation['end_time'])); ?></td>
                                <td><?php echo htmlspecialchars($reservation['purpose']); ?></td>
                                <td>
                                    <form action="/admin/reservations/<?php echo $reservation['id']; ?>/delete" method="POST" class="d-inline">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Czy na pewno chcesz usunąć tę rezerwację?')">
                                            <i class="bi bi-trash"></i> Usuń
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if (empty($reservations)): ?>
                <div class="alert alert-info">
                    Brak rezerwacji w systemie.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div> 