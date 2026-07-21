<?php
// En local, si un fichier .env existe, on le lit pour remplir getenv()
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        putenv(trim($name) . '=' . trim($value));
    }
}

// On récupère notre mot de passe secret de manière sécurisée
define('ADMIN_PASSWORD', getenv('ADMIN_PASSWORD'));

// Sécurité : Si la variable n'est pas configurée, on bloque tout pour éviter les failles
if (!ADMIN_PASSWORD) {
    die("Erreur critique : La configuration du serveur est incomplète.");
}

//Config du basepath pour eviter les bugs dans la redirection de la barre de nav
// Calcule automatiquement la racine selon l'environnement (XAMPP ou Serveur distant)
$scriptDir = dirname($_SERVER['SCRIPT_NAME']);
$baseUrl = ($scriptDir === '/' || $scriptDir === '\\') ? '/' : $scriptDir . '/';

define('BASE_URL', $baseUrl);

