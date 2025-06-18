<div class="row mb-4">
    <div class="col">
        <h2><?php echo htmlspecialchars($room['name']); ?></h2>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Room Details</h5>
                <p class="card-text">
                    <i class="bi bi-people"></i> Capacity: <?php echo htmlspecialchars($room['capacity']); ?> people<br>
                    <?php if ($room['equipment']): ?>
                        <i class="bi bi-tools"></i> Equipment: <?php echo htmlspecialchars($room['equipment']); ?><br>
                    <?php endif; ?>
                </p>
                <?php if ($room['description']): ?>
                    <p class="card-text"><?php echo htmlspecialchars($room['description']); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Make a Reservation</h5>
                    <form action="/reservation/create" method="POST">
                        <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
                        <div class="mb-3">
                            <label for="start_time" class="form-label">Start Time</label>
                            <input type="datetime-local" class="form-control" id="start_time" name="start_time" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_time" class="form-label">End Time</label>
                            <input type="datetime-local" class="form-control" id="end_time" name="end_time" required>
                        </div>
                        <div class="mb-3">
                            <label for="purpose" class="form-label">Purpose</label>
                            <textarea class="form-control" id="purpose" name="purpose" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Make Reservation</button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                Please <a href="/login">login</a> to make a reservation.
            </div>
        <?php endif; ?>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Upcoming Reservations</h5>
                <div class="reservation-calendar">
                    <?php if (empty($reservations)): ?>
                        <p class="text-muted">No upcoming reservations for this room.</p>
                    <?php else: ?>
                        <?php foreach ($reservations as $reservation): ?>
                            <div class="card mb-2">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2 text-muted">
                                        <?php echo date('M d, Y H:i', strtotime($reservation['start_time'])); ?> -
                                        <?php echo date('H:i', strtotime($reservation['end_time'])); ?>
                                    </h6>
                                    <p class="card-text">
                                        <strong>Reserved by:</strong> <?php echo htmlspecialchars($reservation['user_name']); ?><br>
                                        <strong>Purpose:</strong> <?php echo htmlspecialchars($reservation['purpose']); ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div> 