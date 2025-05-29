<?php
$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Logowanie</h3>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="/login">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Hasło</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Zaloguj się</button>
                        </div>
                    </form>
                    
                    <div class="mt-3 text-center">
                        <p>Nie masz konta? <a href="/register">Zarejestruj się</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 