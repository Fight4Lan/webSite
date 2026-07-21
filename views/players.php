<?php
$pageTitle = "Liste des Joueurs";
require_once __DIR__ . '/../header.php';

// Chemin vers le fichier JSON
$jsonPath = __DIR__ . '/../data/players.json';

// Charger les joueurs existants
$joueurs = json_decode(file_get_contents($jsonPath), true) ?? [];

$needSave = false;
foreach ($joueurs as &$j) {
    if (!isset($j['id']) || empty($j['id'])) {
        $j['id'] = uniqid('plr_', true);
        $needSave = true;
    }
}
unset($j);
if ($needSave) {
    file_put_contents($jsonPath, json_encode($joueurs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// -------------------------------------------------------------
// TRAITEMENT DES ACTIONS (ADMIN UNIQUEMENT)
// -------------------------------------------------------------
$message = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    
    // Sécurité : Vérification de la session admin
    if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
        $error = "Action non autorisée. Vous devez être administrateur.";
    } else {
        
        // 1. AJOUTER UN JOUEUR
        if ($_POST['action'] === 'ajouter') {
            $pseudo = trim($_POST['pseudo'] ?? '');

            if (!empty($pseudo)) {
                $nouveauJoueur = [
                    'id'         => uniqid('plr_', true), // Génération d'un ID unique
                    'pseudo'     => htmlspecialchars($pseudo),
                    'DSS'        => htmlspecialchars(trim($_POST['DSS'] ?? '')),
                    'OW'         => htmlspecialchars(trim($_POST['OW'] ?? '')),
                    'Apex'       => htmlspecialchars(trim($_POST['Apex'] ?? '')),
                    'Chess'      => htmlspecialchars(trim($_POST['Chess'] ?? '')),
                    'GeoGuessr'  => htmlspecialchars(trim($_POST['GeoGuessr'] ?? '')),
                    'Brawmhalla' => htmlspecialchars(trim($_POST['Brawmhalla'] ?? ''))
                ];

                $joueurs[] = $nouveauJoueur;
                file_put_contents($jsonPath, json_encode($joueurs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                $message = "Le joueur " . htmlspecialchars($pseudo) . " a été ajouté avec succès !";
            } else {
                $error = "Le pseudo principal est obligatoire.";
            }
        }

        // 2. SUPPRIMER UN JOUEUR
        if ($_POST['action'] === 'supprimer') {
            $idASupprimer = $_POST['id'] ?? '';
            
            if (!empty($idASupprimer)) {
                $countAvant = count($joueurs);
                
                // On filtre le tableau pour retirer le joueur ayant cet ID
                $joueurs = array_values(array_filter($joueurs, function($j) use ($idASupprimer) {
                    return $j['id'] !== $idASupprimer;
                }));

                if (count($joueurs) < $countAvant) {
                    file_put_contents($jsonPath, json_encode($joueurs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                    $message = "Le joueur a été retiré de la LAN avec succès.";
                } else {
                    $error = "Impossible de trouver le joueur à supprimer.";
                }
            }
        }
    }
}
?>

<!-- NAV BAR -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top border-bottom border-secondary border-opacity-10" style="background-color: rgba(11, 12, 16, 0.85); backdrop-filter: blur(10px);">
    <div class="container">
        <a class="navbar-brand fw-bold text-uppercase tracking-wider" href="<?= BASE_URL ?>index.php" style="font-family: 'Orbitron', sans-serif;">
            <span style="color: #ff6b00;">Fight4</span>Lan
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto fw-medium text-uppercase small align-items-center gap-2" style="font-family: 'Orbitron', sans-serif;">
                <li class="nav-item"><a class="nav-link text-white-50" href="index.php?page=home">Accueil</a></li>
                <li class="nav-item"><a class="nav-link text-white-50" href="index.php?page=rules">Règlement</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="index.php?page=players">Joueurs</a></li>
                <li class="nav-item"><a class="nav-link text-white-50" href="#">Lobby</a></li>
                <li class="nav-item"><a class="nav-link text-white-50" href="#">Classements</a></li>
                <li class="nav-item"><a class="nav-link text-white-50" href="#">Partenaires</a></li>
                
                <!-- BOUTON ADMIN -->
                <li class="nav-item ms-lg-3">
                    <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge border border-warning text-warning" style="background: rgba(255, 193, 7, 0.1);">
                                <i class="fa-solid fa-shield-halved me-1"></i> Admin
                            </span>
                            <a href="index.php?page=logout" class="btn btn-sm btn-outline-danger" title="Déconnexion">
                                <i class="fa-solid fa-power-off"></i>
                            </a>
                        </div>
                    <?php else: ?>
                        <a href="index.php?page=login" class="btn btn-sm text-white px-3" style="background: rgba(255, 107, 0, 0.2); border: 1px solid #ff6b00;">
                            <i class="fa-solid fa-user-lock me-1"></i> Connexion
                        </a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-5 mt-5">
    
    <!-- TITRE -->
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold text-uppercase" style="font-family: 'Orbitron', sans-serif;">
            Joueurs <span style="color: #ff6b00; text-shadow: 0 0 15px rgba(255, 107, 0, 0.4);">Inscrits</span>
        </h1>
        <p class="text-secondary">Retrouve tous les participants et leurs identifiants en jeu</p>
    </div>

    <!-- MESSAGES ALERTES -->
    <?php if (!empty($message)): ?>
        <div class="alert alert-success alert-dismissible fade show bg-dark text-success border-success mb-4" role="alert">
            <i class="fa-solid fa-check-circle me-2"></i><?= $message ?>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show bg-dark text-danger border-danger mb-4" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i><?= $error ?>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4 justify-content-center">
        
        <!-- FORMULAIRE D'AJOUT (ADMIN UNIQUEMENT) -->
        <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
            <div class="col-lg-4">
                <div class="p-4 rounded-3 border border-warning border-opacity-25" style="background-color: #1f2833;">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4 class="fw-bold mb-0" style="font-family: 'Orbitron', sans-serif; color: #ff6b00;">
                            Ajouter un Joueur
                        </h4>
                        <span class="badge border border-warning text-warning" style="background: rgba(255, 193, 7, 0.1);">Admin</span>
                    </div>
                    
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="ajouter">
                        
                        <div class="mb-3">
                            <label class="form-label small text-white fw-bold">Pseudo Global *</label>
                            <input type="text" name="pseudo" class="form-control bg-dark text-white border-secondary border-opacity-25" required placeholder="Ex: Slayer99">
                        </div>

                        <hr class="border-secondary opacity-25 my-3">
                        <p class="text-secondary small fw-bold mb-2">Pseudos In-Game (Optionnels) :</p>

                        <div class="mb-2">
                            <label class="form-label small text-secondary mb-1">Disney Speedstorm</label>
                            <input type="text" name="DSS" class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-25">
                        </div>

                        <div class="mb-2">
                            <label class="form-label small text-secondary mb-1">Overwatch</label>
                            <input type="text" name="OW" class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-25">
                        </div>

                        <div class="mb-2">
                            <label class="form-label small text-secondary mb-1">Apex Legends</label>
                            <input type="text" name="Apex" class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-25">
                        </div>

                        <div class="mb-2">
                            <label class="form-label small text-secondary mb-1">Chess.com</label>
                            <input type="text" name="Chess" class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-25">
                        </div>

                        <div class="mb-2">
                            <label class="form-label small text-secondary mb-1">GeoGuessr</label>
                            <input type="text" name="GeoGuessr" class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-25">
                        </div>

                        <div class="mb-3">
                            <label class="form-label small text-secondary mb-1">Brawlhalla</label>
                            <input type="text" name="Brawmhalla" class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-25">
                        </div>

                        <button type="submit" class="btn w-100 fw-bold text-white mt-2" style="font-family: 'Orbitron', sans-serif; background: linear-gradient(45deg, #ff6b00, #ff4500); border: none;">
                            <i class="fa-solid fa-plus me-2"></i> Enregistrer
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <!-- LISTE DES JOUEURS -->
        <div class="<?= (isset($_SESSION['admin']) && $_SESSION['admin'] === true) ? 'col-lg-8' : 'col-lg-10' ?>">
            <div class="accordion" id="accordionJoueurs">
                <?php if (empty($joueurs)): ?>
                    <div class="p-4 rounded-3 text-center border border-secondary border-opacity-10" style="background-color: #1f2833;">
                        <p class="text-secondary mb-0">Aucun joueur inscrit pour le moment.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($joueurs as $index => $j): ?>
                        <?php $targetId = "joueur_" . $index; ?>
                        
                        <div class="accordion-item bg-transparent border border-secondary border-opacity-10 mb-3 rounded-3 overflow-hidden">
                            
                            <!-- EN-TÊTE JOUEUR -->
                            <div class="accordion-header d-flex align-items-center" id="heading_<?= $targetId ?>" style="background-color: #1f2833;">
                                <button class="accordion-button collapsed text-white fw-bold d-flex align-items-center gap-3 flex-grow-1" 
                                        type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#<?= $targetId ?>" 
                                        aria-expanded="false" 
                                        aria-controls="<?= $targetId ?>"
                                        style="background-color: transparent; box-shadow: none;">
                                    
                                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" 
                                         style="width: 40px; height: 40px; background-color: rgba(255, 107, 0, 0.1); color: #ff6b00; border: 1px solid rgba(255, 107, 0, 0.3); flex-shrink: 0;">
                                        <?= strtoupper(substr($j['pseudo'] ?? 'J', 0, 2)) ?>
                                    </div>
                                    
                                    <span class="fs-5" style="font-family: 'Orbitron', sans-serif;">
                                        <?= htmlspecialchars($j['pseudo']) ?>
                                    </span>
                                </button>

                                <!-- BOUTON SUPPRIMER (ADMIN) -->
                                <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
                                    <div class="pe-3">
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger border-0" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal_<?= $j['id'] ?>" 
                                                title="Retirer ce joueur">
                                            <i class="fa-solid fa-trash-can fs-6"></i>
                                        </button>
                                    </div>

                                    <!-- MODAL DE CONFIRMATION BOOTSTRAP -->
                                    <div class="modal fade" id="deleteModal_<?= $j['id'] ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content bg-dark text-white border border-secondary">
                                                <div class="modal-header border-secondary">
                                                    <h5 class="modal-title fw-bold" style="font-family: 'Orbitron', sans-serif; color: #ff6b00;">
                                                        <i class="fa-solid fa-triangle-exclamation me-2"></i>Confirmation
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-start">
                                                    Êtes-vous sûr de vouloir retirer le joueur <strong><?= htmlspecialchars($j['pseudo']) ?></strong> de la LAN ?
                                                </div>
                                                <div class="modal-footer border-secondary">
                                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    <form method="POST" action="" class="d-inline">
                                                        <input type="hidden" name="action" value="supprimer">
                                                        <input type="hidden" name="id" value="<?= $j['id'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-danger fw-bold">
                                                            Confirmer la suppression
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- CONTENU DÉROULANT (PSEUDOS) -->
                            <div id="<?= $targetId ?>" class="accordion-collapse collapse" aria-labelledby="heading_<?= $targetId ?>" data-bs-parent="#accordionJoueurs">
                                <div class="accordion-body p-4" style="background-color: #161c24;">
                                    <p class="text-secondary small mb-3 text-uppercase fw-bold">Identifiants en jeu (IGN) :</p>
                                    
                                    <div class="row g-2">
                                        <div class="col-sm-6">
                                            <div class="p-2 rounded border border-secondary border-opacity-10 d-flex justify-content-between align-items-center bg-dark">
                                                <span class="small text-secondary"><i class="fa-solid fa-car me-2"></i>Speedstorm</span>
                                                <span class="fw-medium text-white small"><?= !empty($j['DSS']) ? htmlspecialchars($j['DSS']) : '<em class="text-white-50">Non renseigné</em>' ?></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="p-2 rounded border border-secondary border-opacity-10 d-flex justify-content-between align-items-center bg-dark">
                                                <span class="small text-secondary"><i class="fa-solid fa-crosshairs me-2"></i>Overwatch</span>
                                                <span class="fw-medium text-white small"><?= !empty($j['OW']) ? htmlspecialchars($j['OW']) : '<em class="text-white-50">Non renseigné</em>' ?></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="p-2 rounded border border-secondary border-opacity-10 d-flex justify-content-between align-items-center bg-dark">
                                                <span class="small text-secondary"><i class="fa-solid fa-skull me-2"></i>Apex</span>
                                                <span class="fw-medium text-white small"><?= !empty($j['Apex']) ? htmlspecialchars($j['Apex']) : '<em class="text-white-50">Non renseigné</em>' ?></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="p-2 rounded border border-secondary border-opacity-10 d-flex justify-content-between align-items-center bg-dark">
                                                <span class="small text-secondary"><i class="fa-solid fa-chess-knight me-2"></i>Chess.com</span>
                                                <span class="fw-medium text-white small"><?= !empty($j['Chess']) ? htmlspecialchars($j['Chess']) : '<em class="text-white-50">Non renseigné</em>' ?></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="p-2 rounded border border-secondary border-opacity-10 d-flex justify-content-between align-items-center bg-dark">
                                                <span class="small text-secondary"><i class="fa-solid fa-earth-americas me-2"></i>GeoGuessr</span>
                                                <span class="fw-medium text-white small"><?= !empty($j['GeoGuessr']) ? htmlspecialchars($j['GeoGuessr']) : '<em class="text-white-50">Non renseigné</em>' ?></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="p-2 rounded border border-secondary border-opacity-10 d-flex justify-content-between align-items-center bg-dark">
                                                <span class="small text-secondary"><i class="fa-solid fa-hand-fist me-2"></i>Brawlhalla</span>
                                                <span class="fw-medium text-white small"><?= !empty($j['Brawmhalla']) ? htmlspecialchars($j['Brawmhalla']) : '<em class="text-white-50">Non renseigné</em>' ?></span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>