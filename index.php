<?php


session_start();


// On récupère la page demandée, par défaut 'home'
$page = $_GET['page'] ?? 'home';

// Sécurité : on vérifie que le fichier existe dans views/
$file = __DIR__ . '/views/' . $page . '.php';

if (file_exists($file)) {
    require_once $file;
} else {
    require_once __DIR__ . '/views/404.php'; // ou par défaut views/home.php
}