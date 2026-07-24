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

// Récupération du lobby directement lié à cette sous-session (si défini)
$associatedLobby = $session->getLobbyId() ? $dataManager->getLobbyById($session->getLobbyId()) : null;

// -------------------------------------------------------------
// TRAITEMENT DES ACTIONS ADMIN (SAISIE SCORES & SUPPRESSION)
// -------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isAdmin) {
    $action = $_POST['action'] ?? '';

    // 1. SUPPRESSION DE LA SOUS-SESSION COURANTE
    if ($action === 'supprimer_sous_session') {
        if ($dataManager->removeSessionById($session->getId())) {
            header('Location: index.php?page=ranking');
            exit();
        } else {
            $error = "Erreur lors de la suppression de la sous-session.";
        }
    }

    // 2. ENREGISTREMENT DES SCORES
    if ($action === 'sauvegarder_scores') {
        $ranks = $_POST['ranks'] ?? [];
        $successCount = 0;

        if ($session->isTeam()) {
            foreach ($ranks as $teamId => $rankValue) {
                $rankInt = (int)$rankValue;
                $team = $dataManager->getTeamById($teamId);

                if ($rankInt > 0 && $team) {
                    foreach ($team->getPlayerIds() as $pId) {
                        $scoreObj = new Score(
                            sessionId: $session->getId(),
                            playerId: $pId,
                            rawScore: 0,
                            rank: $rankInt,
                            lobbyId: $session->getLobbyId(),
                            teamName: $team->getName()
                        );
                        if ($dataManager->saveOrUpdateScore($scoreObj)) {
                            $successCount++;
                        }
                    }
                }
            }
        } else {
            foreach ($ranks as $playerId => $rankValue) {
                $rankInt = (int)$rankValue;

                if ($rankInt > 0) {
                    $scoreObj = new Score(
                        sessionId: $session->getId(),
                        playerId: $playerId,
                        rawScore: 0,
                        rank: $rankInt,
                        lobbyId: $session->getLobbyId(),
                        teamName: null
                    );

                    if ($dataManager->saveOrUpdateScore($scoreObj)) {
                        $successCount++;
                    }
                }
            }
        }

        if ($successCount > 0) {
            $message = "Les classements ont été enregistrés avec succès !";
        } else {
            $error = "Aucun classement valide n'a été saisi.";
        }
    }
}

// Chargement des données globales
$allPlayers = $dataManager->getPlayers();
$allTeams   = $dataManager->getTeams();
$allSessions = $dataManager->getSessions();
$existingScores = $dataManager->getScoresBySessionId($session->getId());

$scoresByPlayer = [];
foreach ($existingScores as $s) {
    $scoresByPlayer[$s->getPlayerId()] = $s;
}

// -------------------------------------------------------------
// CALCUL DU CUMUL SI C'EST UNE SESSION MÈRE / PHASE
// -------------------------------------------------------------
$cumulatedRanks = [];
if ($session->isPhase()) {
    $subSessions = array_filter($allSessions, fn(Session $s) => $s->getParentId() === $session->getId());

    foreach ($subSessions as $sub) {
        $subScores = $dataManager->getScoresBySessionId($sub->getId());
        $processedTeamsInSub = [];

        foreach ($subScores as $score) {
            if ($session->isTeam()) {
                $teamName = $score->getTeamName();
                if (!empty($teamName) && !in_array($teamName, $processedTeamsInSub)) {
                    $processedTeamsInSub[] = $teamName;
                    if (!isset($cumulatedRanks[$teamName])) {
                        $cumulatedRanks[$teamName] = 0;
                    }
                    $cumulatedRanks[$teamName] += $score->getRank();
                }
            } else {
                $pId = $score->getPlayerId();
                if (!isset($cumulatedRanks[$pId])) {
                    $cumulatedRanks[$pId] = 0;
                }
                $cumulatedRanks[$pId] += $score->getRank();
            }
        }
    }

    asort($cumulatedRanks);
}
?>

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
                
                <li class="nav-item ms-lg-3">
                    <?php if ($isAdmin): ?>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge border border-warning text-warning" style="background: rgba(255, 193, 7, 0.1);">
                                <i class="fa-solid fa-shield-halved me-1"></i> Admin
                            </span>
                            <a href="index.php?page=logout" class="btn btn-sm btn-outline-danger" title="Déconnexion"><i class="fa-solid fa-power-off"></i></a>
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

    <a href="index.php?page=ranking" class="btn btn-sm text-secondary border-0 mb-4 hover-orange">
        <i class="fa-solid fa-arrow-left me-2"></i>Retour aux sessions
    </a>

    <!-- EN-TÊTE DE LA SESSION -->
    <div class="p-4 rounded-3 border border-secondary border-opacity-10 mb-4" style="background-color: #1f2833;">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <?php if ($session->getGame()): ?>
                        <span class="badge border border-warning text-warning" style="background: rgba(255, 193, 7, 0.1);">
                            <i class="fa-solid fa-gamepad me-1"></i> <?= htmlspecialchars($session->getGame()->value) ?>
                        </span>
                    <?php else: ?>
                        <span class="badge border border-warning text-warning" style="background: rgba(255, 193, 7, 0.1);">
                            <i class="fa-solid fa-trophy me-1"></i> Phase Mère (Classement Général)
                        </span>
                    <?php endif; ?>

                    <?php if ($session->isTeam()): ?>
                        <span class="badge border border-info text-info" style="background: rgba(13, 202, 240, 0.1);">
                            <i class="fa-solid fa-users me-1"></i> Mode Équipe
                        </span>
                    <?php else: ?>
                        <span class="badge border border-secondary text-secondary bg-dark">
                            <i class="fa-solid fa-user me-1"></i> Mode Solo
                        </span>
                    <?php endif; ?>

                    <?php if ($associatedLobby): ?>
                        <span class="badge border border-primary text-primary" style="background: rgba(13, 110, 253, 0.1);">
                            <i class="fa-solid fa-layer-group me-1"></i> <?= htmlspecialchars($associatedLobby->getName()) ?>
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

            <!-- BOUTON DE SUPPRESSION ADMIN -->
            <?php if ($isAdmin): ?>
                <div>
                    <form method="POST" action="" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette épreuve / sous-session ?');">
                        <input type="hidden" name="action" value="supprimer_sous_session">
                        <button type="submit" class="btn btn-outline-danger btn-sm fw-bold">
                            <i class="fa-solid fa-trash-can me-2"></i> Supprimer l'épreuve
                        </button>
                    </form>
                </div>
            <?php endif; ?>
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
    <!-- SAISIE DES CLASSEMENTS (ADMIN - ÉPREUVE) -->
    <!-- ========================================================= -->
    <?php if ($isAdmin && !$session->isPhase()): ?>
        <div class="p-4 rounded-3 border border-warning border-opacity-25 mb-5" style="background-color: #1f2833;">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h4 class="fw-bold mb-0" style="font-family: 'Orbitron', sans-serif; color: #ff6b00;">
                    <i class="fa-solid fa-pen-to-square me-2"></i> Saisie des Classements
                </h4>
                <span class="badge border border-warning text-warning" style="background: rgba(255, 193, 7, 0.1);">Admin</span>
            </div>

            <form method="POST" action="">
                <input type="hidden" name="action" value="sauvegarder_scores">

                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle border-secondary border-opacity-25">
                        <thead>
                            <tr class="text-secondary small text-uppercase" style="font-family: 'Orbitron', sans-serif;">
                                <th><?= $session->isTeam() ? 'Équipe' : 'Joueur' ?></th>
                                <th style="width: 200px;">Classement (Rang)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($session->isTeam()): ?>
                                <?php 
                                    $teamsToDisplay = $associatedLobby ? 
                                        array_filter($allTeams, fn($t) => in_array($t->getId(), $associatedLobby->getTeamIds())) : 
                                        $allTeams;
                                ?>
                                <?php foreach ($teamsToDisplay as $team): ?>
                                    <?php 
                                        $firstPId = $team->getPlayerIds()[0] ?? null;
                                        $currentRank = ($firstPId && isset($scoresByPlayer[$firstPId])) ? $scoresByPlayer[$firstPId]->getRank() : '';
                                    ?>
                                    <tr>
                                        <td class="fw-bold text-info"><i class="fa-solid fa-users me-2"></i><?= htmlspecialchars($team->getName()) ?></td>
                                        <td>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text bg-dark text-secondary border-secondary border-opacity-25">#</span>
                                                <input type="number" min="1" name="ranks[<?= $team->getId() ?>]" class="form-control bg-dark text-white border-secondary border-opacity-25 fw-bold text-warning" placeholder="Ex: 1" value="<?= $currentRank > 0 ? $currentRank : '' ?>">
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <?php 
                                    $playersToDisplay = $associatedLobby ? 
                                        array_filter($allPlayers, fn($p) => in_array($p->getId(), $associatedLobby->getPlayerIds())) : 
                                        $allPlayers;
                                ?>
                                <?php foreach ($playersToDisplay as $player): ?>
                                    <?php $scoreObj = $scoresByPlayer[$player->getId()] ?? null; ?>
                                    <tr>
                                        <td class="fw-bold text-white"><?= htmlspecialchars($player->getPseudo()) ?></td>
                                        <td>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text bg-dark text-secondary border-secondary border-opacity-25">#</span>
                                                <input type="number" min="1" name="ranks[<?= $player->getId() ?>]" class="form-control bg-dark text-white border-secondary border-opacity-25 fw-bold text-warning" placeholder="Ex: 1" value="<?= $scoreObj && $scoreObj->getRank() > 0 ? $scoreObj->getRank() : '' ?>">
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn fw-bold text-white px-4 py-2" style="font-family: 'Orbitron', sans-serif; background: linear-gradient(45deg, #ff6b00, #ff4500); border: none;">
                        <i class="fa-solid fa-floppy-disk me-2"></i> Enregistrer les classements
                    </button>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <!-- ========================================================= -->
    <!-- AFFICHAGE PUBLIC DES CLASSEMENTS -->
    <!-- ========================================================= -->
    <div class="p-4 p-md-5 rounded-3 border border-secondary border-opacity-10" style="background-color: #1f2833;">
        <h3 class="fw-bold text-white mb-4" style="font-family: 'Orbitron', sans-serif;">
            <i class="fa-solid fa-trophy me-2" style="color: #ff6b00;"></i> Classement Officiel
        </h3>

        <!-- CAS A : SESSION MÈRE / PHASE (CUMULÉ) -->
        <?php if ($session->isPhase()): ?>
            <?php if (empty($cumulatedRanks)): ?>
                <p class="text-secondary text-center py-4 mb-0">Aucun résultat d'épreuve n'a encore été publié pour cette phase.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-dark table-striped align-middle mb-0">
                        <thead>
                            <tr class="text-secondary small text-uppercase" style="font-family: 'Orbitron', sans-serif;">
                                <th style="width: 120px;">Rang Général</th>
                                <th><?= $session->isTeam() ? 'Équipe' : 'Joueur' ?></th>
                                <th class="text-end" style="width: 220px;">Points Cumulés</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $rankCounter = 1; foreach ($cumulatedRanks as $participantKey => $totalRankSum): ?>
                                <tr>
                                    <td>
                                        <?php if ($rankCounter === 1): ?><span class="badge bg-warning text-dark fs-6 rounded-pill px-3">🥇 1er</span>
                                        <?php elseif ($rankCounter === 2): ?><span class="badge bg-light text-dark fs-6 rounded-pill px-3">🥈 2e</span>
                                        <?php elseif ($rankCounter === 3): ?><span class="badge text-white fs-6 rounded-pill px-3" style="background-color: #cd7f32;">🥉 3e</span>
                                        <?php else: ?><span class="fw-bold text-white fs-5 ms-2">#<?= $rankCounter ?></span><?php endif; ?>
                                    </td>
                                    <td class="fw-bold text-white fs-5" style="font-family: 'Orbitron', sans-serif;">
                                        <?php 
                                            if ($session->isTeam()) {
                                                echo htmlspecialchars($participantKey);
                                            } else {
                                                $p = $dataManager->getPlayerById($participantKey);
                                                echo htmlspecialchars($p ? $p->getPseudo() : 'Joueur inconnu');
                                            }
                                        ?>
                                    </td>
                                    <td class="text-end fw-bold text-warning fs-5" style="font-family: 'Orbitron', sans-serif;">
                                        <?= $totalRankSum ?> <span class="small text-secondary">pts</span>
                                    </td>
                                </tr>
                            <?php $rankCounter++; endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

        <!-- CAS B : SOUS-SESSION / ÉPREUVE -->
        <?php else: ?>
            <?php if (empty($existingScores)): ?>
                <p class="text-secondary text-center py-4 mb-0">Aucun résultat n'a encore été publié pour cette épreuve.</p>
            <?php else: ?>
                <?php 
                    $displayedTeams = [];
                    usort($existingScores, fn($a, $b) => $a->getRank() <=> $b->getRank()); 
                ?>
                <div class="table-responsive">
                    <table class="table table-dark table-striped align-middle mb-0">
                        <thead>
                            <tr class="text-secondary small text-uppercase" style="font-family: 'Orbitron', sans-serif;">
                                <th style="width: 100px;">Place</th>
                                <th><?= $session->isTeam() ? 'Équipe' : 'Joueur' ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($existingScores as $score): ?>
                                <?php 
                                    if ($session->isTeam()) {
                                        $tName = $score->getTeamName();
                                        if (empty($tName) || in_array($tName, $displayedTeams)) continue;
                                        $displayedTeams[] = $tName;
                                    }
                                ?>
                                <tr>
                                    <td>
                                        <?php if ($score->getRank() === 1): ?><span class="badge bg-warning text-dark fs-6 rounded-pill px-3">🥇 1er</span>
                                        <?php elseif ($score->getRank() === 2): ?><span class="badge bg-light text-dark fs-6 rounded-pill px-3">🥈 2e</span>
                                        <?php elseif ($score->getRank() === 3): ?><span class="badge text-white fs-6 rounded-pill px-3" style="background-color: #cd7f32;">🥉 3e</span>
                                        <?php else: ?><span class="fw-bold text-white fs-5 ms-2">#<?= $score->getRank() ?></span><?php endif; ?>
                                    </td>
                                    <td class="fw-bold text-white fs-5" style="font-family: 'Orbitron', sans-serif;">
                                        <?php 
                                            if ($session->isTeam()) {
                                                echo htmlspecialchars($score->getTeamName());
                                            } else {
                                                $player = $dataManager->getPlayerById($score->getPlayerId());
                                                echo htmlspecialchars($player ? $player->getPseudo() : 'Joueur inconnu');
                                            }
                                        ?>
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
    .hover-orange:hover { color: #ff6b00 !important; }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>