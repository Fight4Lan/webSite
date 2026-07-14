<?php



// Sécurité : Si l'admin n'est pas connecté, on le renvoie au login
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    require"./login.php";
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Panel Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="alert alert-success text-center">
            <h1>Bienvenue dans l'espace Admin ! 🎉</h1>
            <p>Si tu vois cette page, c'est que ton .env et ta connexion PHP fonctionnent parfaitement.</p>
        </div>
        <a href="logout.php" class="btn btn-danger">Se déconnecter</a>
    </div>
</body>
</html>