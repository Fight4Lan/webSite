<?php
$error = "";

// 1. TRAITEMENT DU FORMULAIRE EN PREMIER
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password_input = $_POST['password'] ?? '';
    
    // Ton echo va enfin s'afficher si le mot de passe est faux ! 
    // (Si le mot de passe est bon, il affichera "requete post" tout en haut de la page admin)
    if (hash_equals(ADMIN_PASSWORD, $password_input)) {
        $_SESSION['is_admin'] = true;
        require './admin.php';
        exit;
    } else {
        $error = "Mot de passe incorrect.";
    }
} 

// 2. VERIFICATION DE LA SESSION (Seulement si on n'est pas en train de soumettre le formulaire)
else {
    
    if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
        require "./admin.php";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <div class="card shadow-sm p-4" style="width: 100%; max-width: 400px;">
        <h2 class="text-center mb-4">Espace Admin</h2>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="./login.php">
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
        </form>
    </div>

</body>
</html>