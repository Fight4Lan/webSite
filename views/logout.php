<?php
// On supprime la variable de session admin
unset($_SESSION['admin']);

// Redirection vers l'accueil
header('Location: index.php?page=home');
exit;