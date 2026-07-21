<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// On récupère la page demandée, par défaut 'home'
$page = $_GET['page'] ?? 'home';

// Sécurité : on vérifie que le fichier existe dans views/
$file = __DIR__ . '/views/' . $page . '.php';

if (file_exists($file)) {
    require_once $file;
} else {
    echo "Erreur : page non trouvée";
}