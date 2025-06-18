<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2>Dostępne sale konferencyjne</h2>
            
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

            <div class="row">
                <?php foreach ($rooms as $room): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($room['name']); ?></h5>
                                <p class="card-text">
                                    <strong>Pojemność:</strong> <?php echo htmlspecialchars($room['capacity']); ?> osób<br>
                                    <strong>Wyposażenie:</strong> <?php echo htmlspecialchars($room['equipment']); ?>
                                </p>
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <a href="/reservations/create/<?php echo $room['id']; ?>" class="btn btn-primary">Zarezerwuj</a>
                                <?php else: ?>
                                    <a href="/login" class="btn btn-secondary">Zaloguj się, aby zarezerwować</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div> 