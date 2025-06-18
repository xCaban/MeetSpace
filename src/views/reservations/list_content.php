<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2>Moje rezerwacje</h2>
            
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
                            <th>Data</th>
                            <th>Godzina rozpoczęcia</th>
                            <th>Godzina zakończenia</th>
                            <th>Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservations as $reservation): ?>
                            <?php 
                            $now = new DateTime();
                            $startTime = new DateTime($reservation['start_time']);
                            $endTime = new DateTime($reservation['end_time']);
                            $isFuture = $now < $startTime;
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($reservation['room_name']); ?></td>
                                <td><?php echo date('Y-m-d', strtotime($reservation['start_time'])); ?></td>
                                <td><?php echo date('H:i', strtotime($reservation['start_time'])); ?></td>
                                <td><?php echo date('H:i', strtotime($reservation['end_time'])); ?></td>
                                <td>
                                    <?php if ($isFuture): ?>
                                        <a href="/reservations/cancel/<?php echo $reservation['id']; ?>" 
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Czy na pewno chcesz anulować tę rezerwację?')">
                                            Anuluj
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if (empty($reservations)): ?>
                <div class="alert alert-info">
                    Nie masz jeszcze żadnych rezerwacji.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div> 