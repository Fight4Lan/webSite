<?php
$pageTitle = "Lobbies";
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../data/DataManager.php';

$dataManager = new DataManager();

// Récupération de toutes les sessions
$sessions = $dataManager->getSessions();

// Filtrer pour ne garder que les sessions qui contiennent des lobbies
$sessionsWithLobbies = array_filter($sessions, function (Session $s) {
    return $s->hasLobbies() && !empty($s->getLobbies());
});
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
                <li class="nav-item"><a class="nav-link text-white-50" href="index.php?page=players">Joueurs</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="index.php?page=lobby">Lobby</a></li>
                <li class="nav-item"><a class="nav-link text-white-50" href="index.php?page=ranking">Classements</a></li>
                <li class="nav-item"><a class="nav-link text-white-50" href="index.php?page=partners">Partenaires</a></li>
                
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

    <!-- TITRE DE LA PAGE -->
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold text-uppercase" style="font-family: 'Orbitron', sans-serif;">
            Lobbies
        </h1>
        <p class="text-secondary">Retrouve la répartition des joueurs par lobby pour chaque session de jeu</p>
    </div>

    <?php if (empty($sessionsWithLobbies)): ?>
        <!-- AUCUN LOBBY CRÉÉ -->
        <div class="p-5 rounded-3 text-center border border-secondary border-opacity-10" style="background-color: #1f2833;">
            <i class="fa-solid fa-gamepad fs-1 text-secondary mb-3"></i>
            <h4 class="fw-bold text-white mb-2" style="font-family: 'Orbitron', sans-serif;">Aucun lobby actif</h4>
            <p class="text-secondary mb-0">Les poules et lobbies des prochaines sessions seront affichés ici dès leur création par l'administration.</p>
        </div>
    <?php else: ?>
        <!-- LISTE DES SESSIONS ET LEURS LOBBIES -->
        <div class="row g-4 justify-content-center">
            <?php foreach ($sessionsWithLobbies as $session): ?>
                <div class="col-12">
                    <div class="p-4 rounded-3 border border-secondary border-opacity-10" style="background-color: #1f2833;">
                        
                        <!-- EN-TÊTE DE LA SESSION -->
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 pb-3 border-bottom border-secondary border-opacity-25">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge border border-warning text-warning" style="background: rgba(255, 193, 7, 0.1);">
                                        <i class="fa-solid fa-gamepad me-1"></i> <?= htmlspecialchars($session->getGame()->value) ?>
                                    </span>
                                    <?php if ($session->isTeam()): ?>
                                        <span class="badge border border-info text-info" style="background: rgba(13, 202, 240, 0.1);">
                                            <i class="fa-solid fa-users me-1"></i> En Équipe
                                        </span>
                                    <?php else: ?>
                                        <span class="badge border border-secondary text-secondary bg-dark">
                                            <i class="fa-solid fa-user me-1"></i> Solo
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <h3 class="fw-bold text-white mb-0" style="font-family: 'Orbitron', sans-serif;">
                                    <?= htmlspecialchars($session->getName()) ?>
                                </h3>
                                <?php if (!empty($session->getDescription())): ?>
                                    <p class="text-secondary small mb-0 mt-1"><?= htmlspecialchars($session->getDescription()) ?></p>
                                <?php endif; ?>
                            </div>

                            <span class="text-secondary small">
                                <i class="fa-solid fa-layer-group me-1" style="color: #ff6b00;"></i> 
                                <?= count($session->getLobbies()) ?> Lobby(s) configuré(s)
                            </span>
                        </div>

                        <!-- GRILLE DES LOBBIES DE CETTE SESSION -->
                        <div class="row g-3">
                            <?php foreach ($session->getLobbies() as $lobby): ?>
                                <div class="col-md-6 col-lg-4">
                                    <div class="p-3 rounded border border-secondary border-opacity-10 h-100 bg-dark">
                                        
                                        <!-- TITRE DU LOBBY -->
                                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom border-secondary border-opacity-10">
                                            <h5 class="fw-bold mb-0" style="font-family: 'Orbitron', sans-serif; color: #ff6b00;">
                                                <i class="fa-solid fa-trophy fs-6 me-2"></i><?= htmlspecialchars($lobby->getName()) ?>
                                            </h5>
                                            <span class="badge bg-secondary bg-opacity-25 text-white-50 small">
                                                <?= count($lobby->getPlayerIds()) ?> joueur(s)
                                            </span>
                                        </div>

                                        <!-- LISTE DES JOUEURS DANS LE LOBBY -->
                                        <ul class="list-unstyled mb-0 d-flex flex-column gap-2">
                                            <?php if (empty($lobby->getPlayerIds())): ?>
                                                <li class="text-secondary small italic">Aucun joueur assigné</li>
                                            <?php else: ?>
                                                <?php foreach ($lobby->getPlayerIds() as $playerId): ?>
                                                    <?php $player = $dataManager->getPlayerById($playerId); ?>
                                                    <?php if ($player): ?>
                                                        <li class="p-2 rounded border border-secondary border-opacity-10 d-flex align-items-center gap-2" style="background-color: #1f2833;">
                                                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold small" 
                                                                 style="width: 28px; height: 28px; background-color: rgba(255, 107, 0, 0.1); color: #ff6b00; border: 1px solid rgba(255, 107, 0, 0.3);">
                                                                <?= strtoupper(substr($player->getPseudo() !== '' ? $player->getPseudo() : 'J', 0, 2)) ?>
                                                            </div>
                                                            <span class="text-white fw-medium small">
                                                                <?= htmlspecialchars($player->getPseudo()) ?>
                                                            </span>
                                                        </li>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </ul>

                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>