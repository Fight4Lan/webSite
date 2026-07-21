<?php
$pageTitle = "Connexion Admin";
require_once __DIR__ . '/header.php';

// Récupération des identifiants depuis les variables d'environnement (ou valeurs par défaut)
$adminUser = $_ENV['ADMIN_USERNAME'] ?? getenv('ADMIN_USERNAME') ??'';
$adminPass = $_ENV['ADMIN_PASSWORD'] ?? getenv('ADMIN_PASSWORD') ??'';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === $adminUser && $password === $adminPass) {
        // Définition de la variable de session admin
        $_SESSION['admin'] = true;
        
        // Redirection vers la page d'accueil
        header('Location: index.php?page=home');
        exit;
    } else {
        $error = "Identifiants administrateur incorrects.";
    }
}
?>

<div class="container py-5 mt-5 d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="p-4 p-md-5 rounded-3 border border-secondary border-opacity-10 w-100" style="max-width: 420px; background-color: #1f2833; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
        
        <div class="text-center mb-4">
            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; background-color: rgba(255, 107, 0, 0.1); color: #ff6b00; border: 1px solid rgba(255, 107, 0, 0.3);">
                <i class="fa-solid fa-shield-halved fs-3"></i>
            </div>
            <h3 class="fw-bold text-uppercase" style="font-family: 'Orbitron', sans-serif;">Espace <span style="color: #ff6b00;">Admin</span></h3>
            <p class="text-secondary small">Veuillez vous identifier pour accéder aux fonctionnalités de gestion.</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger bg-dark text-danger border-danger small py-2 text-center mb-4">
                <i class="fa-solid fa-triangle-exclamation me-1"></i> <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label small text-secondary">Nom d'utilisateur</label>
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary border-opacity-25 text-secondary"><i class="fa-solid fa-user"></i></span>
                    <input type="text" name="username" class="form-control bg-dark text-white border-secondary border-opacity-25" required placeholder="Ex: admin">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label small text-secondary">Mot de passe</label>
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary border-opacity-25 text-secondary"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" name="password" class="form-control bg-dark text-white border-secondary border-opacity-25" required placeholder="••••••••">
                </div>
            </div>

            <button type="submit" class="btn w-100 fw-bold text-white py-2" style="font-family: 'Orbitron', sans-serif; background: linear-gradient(45deg, #ff6b00, #ff4500); border: none;">
                Se Connecter
            </button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>