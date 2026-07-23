<?php
$pageTitle = "Détail de la Session & Scores";
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../data/DataManager.php';

$dataManager = new DataManager();

$sessionId = $_GET['id'] ?? '';
$session = $dataManager->getSessionById($sessionId);

// Si la session n'existe pas, redirection vers ranking
if (!$session) {
    header('Location: index.php?page=ranking');
    exit();
}

$message = "";
$error = "";
$isAdmin = isset($_SESSION['admin']) && $_SESSION['admin'] === true;

// -------------------------------------------------------------
// TRAITEMENT DES ACTIONS ADMIN
// -------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isAdmin) {
    $action = $_POST['action'] ?? '';

    // 1. CRÉATION D'UNE POULE / LOBBY (Si mode hasLobbies)
    if ($action === 'creer_lobby' && $session->hasLobbies()) {
        $lobbyName = trim($_POST['lobby_name'] ?? '');
        $selectedPlayers = $_POST['players'] ?? [];

        if (!empty($lobbyName)) {
            $newLobby = new Lobby(
                sessionId: $session->getId(),
                name: htmlspecialchars($lobbyName),
                playerIds: $selectedPlayers
            );

            $session->addLobby($newLobby);
            if ($dataManager->updateSession($session)) {
                $message = "La poule <strong>" . htmlspecialchars($lobbyName) . "</strong> a été créée !";
            } else {
                $error = "Erreur lors de la création de la poule.";
            }
        } else {
            $error = "Le nom de la poule est obligatoire.";
        }
    }

    // 2. CRÉATION D'UNE ÉQUIPE (Si mode isTeam)
    if ($action === 'creer_equipe' && $session->isTeam()) {
        $teamName = trim($_POST['team_name'] ?? '');
        $selectedPlayers = $_POST['team_players'] ?? [];

        if (!empty($teamName) && !empty($selectedPlayers)) {
            // Un lobby est utilisé en interne pour stocker le groupe de joueurs formant l'équipe
            $newTeamLobby = new Lobby(
                sessionId: $session->getId(),
                name: htmlspecialchars($teamName),
                playerIds: $selectedPlayers
            );

            $session->addLobby($newTeamLobby);
            if ($dataManager->updateSession($session)) {
                $message = "L'équipe <strong>" . htmlspecialchars($teamName) . "</strong> a été créée avec succès !";
            } else {
                $error = "Erreur lors de la création de l'équipe.";
            }
        } else {
            $error = "Le nom de l'équipe et au moins un joueur sont obligatoires.";
        }
    }

    // 3. ENREGISTREMENT DES CLASSEMENTS EN ÉQUIPE
    if ($action === 'sauvegarder_scores_equipes' && $session->isTeam()) {
        $teamRanks = $_POST['team_ranks'] ?? []; // Array [lobbyId => rank]

        $successCount = 0;
        foreach ($session->getLobbies() as $teamLobby) {
            $rankInt = (int)($teamRanks[$teamLobby->getId()] ?? 0);

            if ($rankInt > 0) {
                // On attribue le classement de l'équipe à chaque joueur qui la compose
                foreach ($teamLobby->getPlayerIds() as $pId) {
                    $scoreObj = new Score(
                        sessionId: $session->getId(),
                        playerId: $pId,
                        rawScore: 0,
                        rank: $rankInt,
                        lobbyId: $teamLobby->getId(),
                        teamName: $teamLobby->getName()
                    );
                    if ($dataManager->saveOrUpdateScore($scoreObj)) {
                        $successCount++;
                    }
                }
            }
        }

        if ($successCount > 0) {
            $message = "Le classement des équipes a été enregistré avec succès !";
        }
    }

    // 4. ENREGISTREMENT DES CLASSEMENTS INDIVIDUELS / POULES
    if ($action === 'sauvegarder_scores') {
        $ranks = $_POST['ranks'] ?? [];
        $lobbyId = !empty($_POST['lobby_id']) ? $_POST['lobby_id'] : null;

        $successCount = 0;
        foreach ($ranks as $playerId => $rankValue) {
            $rankInt = (int)$rankValue;

            if ($rankInt > 0) {
                $scoreObj = new Score(
                    sessionId: $session->getId(),
                    playerId: $playerId,
                    rawScore: 0,
                    rank: $rankInt,
                    lobbyId: $lobbyId,
                    teamName: null
                );

                if ($dataManager->saveOrUpdateScore($scoreObj)) {
                    $successCount++;
                }
            }
        }

        if ($successCount > 0) {
            $message = "Les classements ont été enregistrés avec succès !";
        }
    }
}

// Chargement des données
$allPlayers = $dataManager->getPlayers();
$existingScores = $dataManager->getScoresBySessionId($session->getId());

// Indexation des scores par ID de joueur
$scoresByPlayer = [];
foreach ($existingScores as $s) {
    $scoresByPlayer[$s->getPlayerId()] = $s;
}

// Récupération des groupes (poules ou équipes)
$lobbies = $session->getLobbies();
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
                <li class="nav-item"><a class="nav-link text-white-50" href="index.php?page=lobby">Lobby</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="index.php?page=ranking">Classements</a></li>
                <li class="nav-item"><a class="nav-link text-white-50" href="index.php?page=partners">Partenaires</a></li>
                
                <!-- BOUTON ADMIN -->
                <li class="nav-item ms-lg-3">
                    <?php if ($isAdmin): ?>
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

    <!-- BOUTON RETOUR -->
    <a href="index.php?page=ranking" class="btn btn-sm text-secondary border-0 mb-4 hover-orange">
        <i class="fa-solid fa-arrow-left me-2"></i>Retour aux sessions
    </a>

    <!-- EN-TÊTE DE LA SESSION -->
    <div class="p-4 rounded-3 border border-secondary border-opacity-10 mb-4" style="background-color: #1f2833;">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge border border-warning text-warning" style="background: rgba(255, 193, 7, 0.1);">
                        <i class="fa-solid fa-gamepad me-1"></i> <?= htmlspecialchars($session->getGame()->value) ?>
                    </span>
                    <?php if ($session->isTeam()): ?>
                        <span class="badge border border-info text-info" style="background: rgba(13, 202, 240, 0.1);">
                            <i class="fa-solid fa-users me-1"></i> Mode Équipe
                        </span>
                    <?php else: ?>
                        <span class="badge border border-secondary text-secondary bg-dark">
                            <i class="fa-solid fa-user me-1"></i> Mode Solo
                        </span>
                    <?php endif; ?>

                    <?php if ($session->hasLobbies()): ?>
                        <span class="badge border border-primary text-primary" style="background: rgba(13, 110, 253, 0.1);">
                            <i class="fa-solid fa-layer-group me-1"></i> Poules / Lobbies
                        </span>
                    <?php endif; ?>
                </div>
                <h2 class="fw-bold text-white mb-1" style="font-family: 'Orbitron', sans-serif;">
                    <?= htmlspecialchars($session->getName()) ?>
                </h2>
                <?php if (!empty($session->getDescription())): ?>
                    <p class="text-secondary small mb-0"><?= htmlspecialchars($session->getDescription()) ?></p>
                <?php endif; ?>
            </div>
        </div>
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

    <!-- ========================================================= -->
    <!-- CRÉATION DES ÉQUIPES (ADMIN - SI MODE ISTEAM) -->
    <!-- ========================================================= -->
    <?php if ($isAdmin && $session->isTeam()): ?>
        <div class="p-4 rounded-3 border border-info border-opacity-25 mb-4" style="background-color: #1f2833;">
            <h4 class="fw-bold mb-3 text-white" style="font-family: 'Orbitron', sans-serif; color: #0dcaf0;">
                <i class="fa-solid fa-users me-2"></i> Créer une Équipe Temporaire
            </h4>

            <form method="POST" action="" class="row g-3">
                <input type="hidden" name="action" value="creer_equipe">

                <div class="col-md-4">
                    <label class="form-label small text-white fw-bold">Nom de l'Équipe *</label>
                    <input type="text" name="team_name" class="form-control bg-dark text-white border-secondary border-opacity-25" required placeholder="Ex: Team Alpha">
                </div>

                <div class="col-md-8">
                    <label class="form-label small text-white fw-bold">Sélectionner les membres de l'équipe *</label>
                    <div class="p-2 rounded bg-dark border border-secondary border-opacity-25" style="max-height: 120px; overflow-y: auto;">
                        <div class="row g-2">
                            <?php foreach ($allPlayers as $player): ?>
                                <div class="col-6 col-sm-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="team_players[]" value="<?= $player->getId() ?>" id="tpl_<?= $player->getId() ?>">
                                        <label class="form-check-label text-white small" for="tpl_<?= $player->getId() ?>">
                                            <?= htmlspecialchars($player->getPseudo()) ?>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-sm btn-info fw-bold text-white px-4">
                        <i class="fa-solid fa-plus me-1"></i> Enregistrer l'équipe
                    </button>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <!-- ========================================================= -->
    <!-- CRÉATION DES POULES (ADMIN - SI MODE HASLOBBIES) -->
    <!-- ========================================================= -->
    <?php if ($isAdmin && $session->hasLobbies()): ?>
        <div class="p-4 rounded-3 border border-primary border-opacity-25 mb-4" style="background-color: #1f2833;">
            <h4 class="fw-bold mb-3 text-white" style="font-family: 'Orbitron', sans-serif; color: #ff6b00;">
                <i class="fa-solid fa-layer-group me-2"></i> Ajouter une Poule / Lobby
            </h4>

            <form method="POST" action="" class="row g-3">
                <input type="hidden" name="action" value="creer_lobby">

                <div class="col-md-4">
                    <label class="form-label small text-white fw-bold">Nom de la Poule / Lobby *</label>
                    <input type="text" name="lobby_name" class="form-control bg-dark text-white border-secondary border-opacity-25" required placeholder="Ex: Poule A">
                </div>

                <div class="col-md-8">
                    <label class="form-label small text-white fw-bold">Sélectionner les joueurs de cette poule</label>
                    <div class="p-2 rounded bg-dark border border-secondary border-opacity-25" style="max-height: 120px; overflow-y: auto;">
                        <div class="row g-2">
                            <?php foreach ($allPlayers as $player): ?>
                                <div class="col-6 col-sm-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="players[]" value="<?= $player->getId() ?>" id="pl_<?= $player->getId() ?>">
                                        <label class="form-check-label text-white small" for="pl_<?= $player->getId() ?>">
                                            <?= htmlspecialchars($player->getPseudo()) ?>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-sm btn-primary fw-bold px-4">
                        <i class="fa-solid fa-plus me-1"></i> Créer la poule
                    </button>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <!-- ========================================================= -->
    <!-- SAISIE DES CLASSEMENTS (ADMIN) -->
    <!-- ========================================================= -->
    <?php if ($isAdmin): ?>
        <div class="p-4 rounded-3 border border-warning border-opacity-25 mb-5" style="background-color: #1f2833;">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h4 class="fw-bold mb-0" style="font-family: 'Orbitron', sans-serif; color: #ff6b00;">
                    <i class="fa-solid fa-pen-to-square me-2"></i> Saisie des Classements
                </h4>
                <span class="badge border border-warning text-warning" style="background: rgba(255, 193, 7, 0.1);">Admin</span>
            </div>

            <!-- CAS 1 : SESSION EN ÉQUIPE -->
            <?php if ($session->isTeam()): ?>
                <?php if (empty($lobbies)): ?>
                    <p class="text-warning small mb-0">
                        <i class="fa-solid fa-circle-exclamation me-1"></i> Vous devez d'abord créer au moins une équipe ci-dessus.
                    </p>
                <?php else: ?>
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="sauvegarder_scores_equipes">

                        <div class="table-responsive">
                            <table class="table table-dark table-hover align-middle border-secondary border-opacity-25">
                                <thead>
                                    <tr class="text-secondary small text-uppercase" style="font-family: 'Orbitron', sans-serif;">
                                        <th>Équipe</th>
                                        <th>Membres de l'Équipe</th>
                                        <th style="width: 220px;">Classement de l'Équipe</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($lobbies as $teamLobby): ?>
                                        <?php 
                                            // Récupérer le rang actuel de l'équipe (s'il existe déjà)
                                            $firstPlayerId = $teamLobby->getPlayerIds()[0] ?? null;
                                            $currentRank = ($firstPlayerId && isset($scoresByPlayer[$firstPlayerId])) ? $scoresByPlayer[$firstPlayerId]->getRank() : '';
                                        ?>
                                        <tr>
                                            <td class="fw-bold text-info fs-5" style="font-family: 'Orbitron', sans-serif;">
                                                <i class="fa-solid fa-users me-2"></i><?= htmlspecialchars($teamLobby->getName()) ?>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-1">
                                                    <?php foreach ($teamLobby->getPlayerIds() as $pId): ?>
                                                        <?php $p = $dataManager->getPlayerById($pId); if (!$p) continue; ?>
                                                        <span class="badge bg-dark border border-secondary border-opacity-25 text-white">
                                                            <?= htmlspecialchars($p->getPseudo()) ?>
                                                        </span>
                                                    <?php endforeach; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text bg-dark text-secondary border-secondary border-opacity-25">#</span>
                                                    <input type="number" min="1" name="team_ranks[<?= $teamLobby->getId() ?>]" class="form-control bg-dark text-white border-secondary border-opacity-25 fw-bold text-warning" placeholder="Ex: 1 (1ère)" value="<?= $currentRank > 0 ? $currentRank : '' ?>">
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="text-end mt-3">
                            <button type="submit" class="btn fw-bold text-white px-4 py-2" style="font-family: 'Orbitron', sans-serif; background: linear-gradient(45deg, #ff6b00, #ff4500); border: none;">
                                <i class="fa-solid fa-floppy-disk me-2"></i> Enregistrer les classements des équipes
                            </button>
                        </div>
                    </form>
                <?php endif; ?>

            <!-- CAS 2 : SESSION PAR POULES / LOBBIES (SOLO) -->
            <?php elseif ($session->hasLobbies()): ?>
                <?php if (empty($lobbies)): ?>
                    <p class="text-warning small mb-0">
                        <i class="fa-solid fa-circle-exclamation me-1"></i> Vous devez d'abord créer au moins une poule ci-dessus.
                    </p>
                <?php else: ?>
                    <?php foreach ($lobbies as $lobby): ?>
                        <div class="p-3 mb-4 rounded bg-dark border border-secondary border-opacity-25">
                            <h5 class="fw-bold text-white mb-3" style="font-family: 'Orbitron', sans-serif; color: #ff6b00;">
                                <i class="fa-solid fa-trophy fs-6 me-2"></i>Saisie pour : <?= htmlspecialchars($lobby->getName()) ?>
                            </h5>

                            <form method="POST" action="">
                                <input type="hidden" name="action" value="sauvegarder_scores">
                                <input type="hidden" name="lobby_id" value="<?= $lobby->getId() ?>">

                                <div class="table-responsive">
                                    <table class="table table-dark table-hover align-middle border-secondary border-opacity-25 mb-2">
                                        <thead>
                                            <tr class="text-secondary small text-uppercase" style="font-family: 'Orbitron', sans-serif;">
                                                <th>Joueur de la poule</th>
                                                <th style="width: 180px;">Classement dans la poule</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($lobby->getPlayerIds() as $pId): ?>
                                                <?php $player = $dataManager->getPlayerById($pId); if (!$player) continue; ?>
                                                <?php $scoreObj = $scoresByPlayer[$player->getId()] ?? null; ?>
                                                <tr>
                                                    <td class="fw-bold text-white"><?= htmlspecialchars($player->getPseudo()) ?></td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <span class="input-group-text bg-dark text-secondary border-secondary border-opacity-25">#</span>
                                                            <input type="number" min="1" name="ranks[<?= $player->getId() ?>]" class="form-control bg-dark text-white border-secondary border-opacity-25 fw-bold text-warning" placeholder="Ex: 1 (1er)" value="<?= $scoreObj && $scoreObj->getRank() > 0 ? $scoreObj->getRank() : '' ?>">
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-sm fw-bold text-white px-3" style="font-family: 'Orbitron', sans-serif; background: linear-gradient(45deg, #ff6b00, #ff4500); border: none;">
                                        Enregistrer pour <?= htmlspecialchars($lobby->getName()) ?>
                                    </button>
                                </div>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            <!-- CAS 3 : SESSION GLOBALE SOLO (SANS POULE NI ÉQUIPE) -->
            <?php else: ?>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="sauvegarder_scores">

                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle border-secondary border-opacity-25">
                            <thead>
                                <tr class="text-secondary small text-uppercase" style="font-family: 'Orbitron', sans-serif;">
                                    <th>Joueur</th>
                                    <th style="width: 200px;">Classement (Rang)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($allPlayers as $player): ?>
                                    <?php $scoreObj = $scoresByPlayer[$player->getId()] ?? null; ?>
                                    <tr>
                                        <td class="fw-bold text-white"><?= htmlspecialchars($player->getPseudo()) ?></td>
                                        <td>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text bg-dark text-secondary border-secondary border-opacity-25">#</span>
                                                <input type="number" min="1" name="ranks[<?= $player->getId() ?>]" class="form-control bg-dark text-white border-secondary border-opacity-25 fw-bold text-warning" placeholder="Ex: 1 pour 1er" value="<?= $scoreObj && $scoreObj->getRank() > 0 ? $scoreObj->getRank() : '' ?>">
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="text-end mt-3">
                        <button type="submit" class="btn fw-bold text-white px-4 py-2" style="font-family: 'Orbitron', sans-serif; background: linear-gradient(45deg, #ff6b00, #ff4500); border: none;">
                            <i class="fa-solid fa-floppy-disk me-2"></i> Enregistrer les classements
                        </button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- ========================================================= -->
    <!-- AFFICHAGE PUBLIQUE DES CLASSEMENTS ET ÉQUIPES/LOBBIES -->
    <!-- ========================================================= -->
    <div class="p-4 p-md-5 rounded-3 border border-secondary border-opacity-10" style="background-color: #1f2833;">
        <h3 class="fw-bold text-white mb-4" style="font-family: 'Orbitron', sans-serif;">
            <i class="fa-solid fa-trophy me-2" style="color: #ff6b00;"></i> Classement Officiel
        </h3>

        <!-- SI LA SESSION SE JOUE EN ÉQUIPE -->
        <?php if ($session->isTeam()): ?>
            <?php if (empty($lobbies)): ?>
                <p class="text-secondary text-center py-4 mb-0">Aucune équipe n'a encore été configurée pour cette session.</p>
            <?php else: ?>
                <?php 
                    // Regrouper et trier les équipes par rang
                    usort($lobbies, function($a, $b) use ($scoresByPlayer) {
                        $pA = $a->getPlayerIds()[0] ?? null;
                        $pB = $b->getPlayerIds()[0] ?? null;
                        $rA = ($pA && isset($scoresByPlayer[$pA])) ? $scoresByPlayer[$pA]->getRank() : 999;
                        $rB = ($pB && isset($scoresByPlayer[$pB])) ? $scoresByPlayer[$pB]->getRank() : 999;
                        return $rA <=> $rB;
                    });
                ?>
                <div class="row g-4">
                    <?php foreach ($lobbies as $teamLobby): ?>
                        <?php 
                            $firstPId = $teamLobby->getPlayerIds()[0] ?? null;
                            $teamRank = ($firstPId && isset($scoresByPlayer[$firstPId])) ? $scoresByPlayer[$firstPId]->getRank() : 0;
                        ?>
                        <div class="col-lg-6">
                            <div class="p-3 rounded bg-dark border border-secondary border-opacity-25 h-100">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h5 class="fw-bold text-info mb-0" style="font-family: 'Orbitron', sans-serif;">
                                        <i class="fa-solid fa-users me-2"></i><?= htmlspecialchars($teamLobby->getName()) ?>
                                    </h5>
                                    <div>
                                        <?php if ($teamRank === 1): ?>
                                            <span class="badge bg-warning text-dark fs-6">🥇 1ère Équipe</span>
                                        <?php elseif ($teamRank === 2): ?>
                                            <span class="badge bg-light text-dark fs-6">🥈 2e Équipe</span>
                                        <?php elseif ($teamRank === 3): ?>
                                            <span class="badge text-white fs-6" style="background-color: #cd7f32;">🥉 3e Équipe</span>
                                        <?php elseif ($teamRank > 0): ?>
                                            <span class="badge bg-secondary text-white">#<?= $teamRank ?> Place</span>
                                        <?php else: ?>
                                            <span class="badge bg-dark border border-secondary text-secondary">Attente de résultat</span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <p class="text-secondary small mb-2"><i class="fa-solid fa-user-group me-1"></i> Membres de l'équipe :</p>
                                <div class="d-flex flex-wrap gap-2">
                                    <?php foreach ($teamLobby->getPlayerIds() as $pId): ?>
                                        <?php $p = $dataManager->getPlayerById($pId); if (!$p) continue; ?>
                                        <span class="badge border border-secondary border-opacity-25 bg-black bg-opacity-50 text-white p-2">
                                            <?= htmlspecialchars($p->getPseudo()) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        <!-- SI LA SESSION SE JOUE PAR POULES / LOBBIES -->
        <?php elseif ($session->hasLobbies()): ?>
            <?php if (empty($lobbies)): ?>
                <p class="text-secondary text-center py-4 mb-0">Aucune poule n'a encore été configurée pour cette session.</p>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($lobbies as $lobby): ?>
                        <?php 
                            $lobbyScores = $dataManager->getScoresByLobbyId($lobby->getId());
                            usort($lobbyScores, fn($a, $b) => $a->getRank() <=> $b->getRank());
                        ?>
                        <div class="col-lg-6">
                            <div class="p-3 rounded bg-dark border border-secondary border-opacity-25 h-100">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h5 class="fw-bold text-white mb-0" style="font-family: 'Orbitron', sans-serif; color: #ff6b00;">
                                        <i class="fa-solid fa-layer-group me-2"></i><?= htmlspecialchars($lobby->getName()) ?>
                                    </h5>
                                    <span class="badge bg-secondary bg-opacity-25 text-white-50 small">
                                        <?= count($lobby->getPlayerIds()) ?> joueur(s)
                                    </span>
                                </div>

                                <?php if (!empty($lobbyScores)): ?>
                                    <table class="table table-dark table-striped align-middle mb-0 small">
                                        <thead>
                                            <tr class="text-secondary">
                                                <th style="width: 70px;">Place</th>
                                                <th>Joueur</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($lobbyScores as $score): ?>
                                                <?php $player = $dataManager->getPlayerById($score->getPlayerId()); if (!$player) continue; ?>
                                                <tr>
                                                    <td>
                                                        <?php if ($score->getRank() === 1): ?>
                                                            <span class="badge bg-warning text-dark">🥇 1er</span>
                                                        <?php elseif ($score->getRank() === 2): ?>
                                                            <span class="badge bg-light text-dark">🥈 2e</span>
                                                        <?php elseif ($score->getRank() === 3): ?>
                                                            <span class="badge text-white" style="background-color: #cd7f32;">🥉 3e</span>
                                                        <?php else: ?>
                                                            <span class="fw-bold text-white-50">#<?= $score->getRank() ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="fw-bold text-white"><?= htmlspecialchars($player->getPseudo()) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <div class="p-2 rounded border border-secondary border-opacity-10 bg-black bg-opacity-25">
                                        <p class="text-warning micro-text mb-2"><i class="fa-solid fa-clock me-1"></i> Composition de la poule (Match à venir) :</p>
                                        <ul class="list-unstyled mb-0 d-flex flex-wrap gap-2">
                                            <?php foreach ($lobby->getPlayerIds() as $pId): ?>
                                                <?php $p = $dataManager->getPlayerById($pId); if (!$p) continue; ?>
                                                <li class="badge border border-secondary border-opacity-25 bg-dark text-white p-2">
                                                    <i class="fa-solid fa-user me-1 text-secondary"></i><?= htmlspecialchars($p->getPseudo()) ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        <!-- SI SESSION GLOBALE SOLO -->
        <?php else: ?>
            <?php if (empty($existingScores)): ?>
                <p class="text-secondary text-center py-4 mb-0">Aucun classement n'a encore été publié pour cette session.</p>
            <?php else: ?>
                <?php 
                    usort($existingScores, fn($a, $b) => $a->getRank() <=> $b->getRank());
                ?>
                <div class="table-responsive">
                    <table class="table table-dark table-striped align-middle mb-0">
                        <thead>
                            <tr class="text-secondary small text-uppercase" style="font-family: 'Orbitron', sans-serif;">
                                <th style="width: 100px;">Place</th>
                                <th>Joueur</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($existingScores as $score): ?>
                                <?php $player = $dataManager->getPlayerById($score->getPlayerId()); if (!$player) continue; ?>
                                <tr>
                                    <td>
                                        <?php if ($score->getRank() === 1): ?>
                                            <span class="badge bg-warning text-dark fs-6 rounded-pill px-3">🥇 1er</span>
                                        <?php elseif ($score->getRank() === 2): ?>
                                            <span class="badge bg-light text-dark fs-6 rounded-pill px-3">🥈 2e</span>
                                        <?php elseif ($score->getRank() === 3): ?>
                                            <span class="badge text-white fs-6 rounded-pill px-3" style="background-color: #cd7f32;">🥉 3e</span>
                                        <?php else: ?>
                                            <span class="fw-bold text-white fs-5 ms-2">#<?= $score->getRank() ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="fw-bold text-white fs-5" style="font-family: 'Orbitron', sans-serif;">
                                        <?= htmlspecialchars($player->getPseudo()) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

</div>

<style>
    .hover-orange:hover {
        color: #ff6b00 !important;
    }
    .micro-text {
        font-size: 0.75rem;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>