<?php
// 1. Importation du fichier de configuration globale (on remonte d'un dossier si le header est dans /views)
require_once __DIR__ . '/../config.php';

// 2. Gestion sécurisée de la session globale
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Le titre dynamique : s'il n'est pas défini avant l'appel du header, on met un titre par défaut -->
    <title><?= 'Fight4Lan - '.$pageTitle ?? 'Fight4Lan'; ?></title>
    
    <!-- IMPORTS GLOBAUX -->
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome (Icônes) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts Gaming -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;800&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body class="text-white min-vh-100" style="background-color: #0b0c10; font-family: 'Poppins', sans-serif; overflow-x: hidden;"></body>