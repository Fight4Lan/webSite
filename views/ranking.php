<?php
$pageTitle = "Phases & Classements";
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../data/DataManager.php';

$dataManager = new DataManager();

$message = "";
$error = "";
$isAdmin = isset($_SESSION['admin']) && $_SESSION['admin'] === true;

// -------------------------------------------------------------
// TRAITEMENT DU FORMULAIRE DE CRÉATION DE SESSION / PHASE
// -------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $isAdmin) {
    if ($_POST['action'] === 'creer_session') {
        $name = trim($_POST['name'] ?? '');
        $gameValue = $_POST['game'] ?? '';
        $description = trim($_POST['description'] ?? '');
        $parentId = !empty($_POST['parent_id']) ? $_POST['parent_id'] : null;
        $lobbyId = !empty($_POST['lobby_id']) ? $_POST['lobby_id'] : null;
        $isTeam = isset($_POST['isTeam']);

        if (!empty($name)) {
            $gameEnum = !empty($gameValue) ? Game::from($gameValue) : null;

            // Si c'est une sous-session rattachée à une phase, elle peut hériter de son mode équipe
            if ($parentId !== null) {
                $parentSession = $dataManager->getSessionById($parentId);
                if ($parentSession) {
                    $isTeam = $parentSession->isTeam();
                }
            }

            $newSession = new Session(
                name: htmlspecialchars($name),
                game: $gameEnum,
                description: htmlspecialchars($description),
                isTeam: $isTeam,
                parentId: $parentId,
                lobbyId: $lobbyId
            );

            if ($dataManager->addSession($newSession)) {
                $message = "La session/phase <strong>" . htmlspecialchars($name) . "</strong> a été créée !";
            } else {
                $error = "Erreur lors de l'enregistrement.";
            }
        } else {
            $error = "Le nom est obligatoire.";
        }
    }

    // SUPPRESSION DE SESSION
    if ($_POST['action'] === 'supprimer_session') {
        $sessionId = $_POST['session_id'] ?? '';
        if (!empty($sessionId)) {
            if ($dataManager->removeSessionById($sessionId)) {
                $message = "La session/phase a été supprimée avec succès.";
            } else {
                $error = "Impossible de supprimer la session.";
            }
        }
    }
}

// Chargement des données
$allSessions = $dataManager->getSessions();
$allLobbies  = $dataManager->getLobbies();

// Séparation des Sessions Mères (Phases parentes)
$phases = array_filter($allSessions, fn(Session $s) => $s->getParentId() === null);
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

    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold text-uppercase" style="font-family: 'Orbitron', sans-serif;">
            Phases & <span style="color: #ff6b00; text-shadow: 0 0 15px rgba(255, 107, 0, 0.4);">Classements</span>
        </h1>
        <p class="text-secondary">Consulte les résultats cumulés des phases et le détail par épreuve</p>
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

        <!-- FORMULAIRE DE CRÉATION DE PHASE / SOUS-SESSION (ADMIN) -->
        <?php if ($isAdmin): ?>
            <div class="col-lg-4">
                <div class="p-4 rounded-3 border border-warning border-opacity-25" style="background-color: #1f2833;">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4 class="fw-bold mb-0" style="font-family: 'Orbitron', sans-serif; color: #ff6b00;">
                            Nouvelle Épreuve / Phase
                        </h4>
                        <span class="badge border border-warning text-warning" style="background: rgba(255, 193, 7, 0.1);">Admin</span>
                    </div>

                    <form method="POST" action="">
                        <input type="hidden" name="action" value="creer_session">

                        <div class="mb-3">
                            <label class="form-label small text-white fw-bold">Nom *</label>
                            <input type="text" name="name" class="form-control bg-dark text-white border-secondary border-opacity-25" required placeholder="Ex: Phase 1 ou Épreuve Speedstorm">
                        </div>

                        <!-- SELECTION DE LA SESSION MÈRE -->
                        <div class="mb-3">
                            <label class="form-label small text-white fw-bold">Rattacher à une Session Mère / Phase</label>
                            <select name="parent_id" class="form-select bg-dark text-white border-secondary border-opacity-25">
                                <option value="">-- Aucune (Créer une Session Mère) --</option>
                                <?php foreach ($phases as $p): ?>
                                    <option value="<?= $p->getId() ?>">Phase : <?= htmlspecialchars($p->getName()) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="text-secondary micro-text mt-1">Laissez vide si vous créez un groupe de jeux (Format ZLAN).</div>
                        </div>

                        <!-- SELECTION DU LOBBY ASSOCIÉ -->
                        <div class="mb-3">
                            <label class="form-label small text-white fw-bold">Lobby associé à cette épreuve</label>
                            <select name="lobby_id" class="form-select bg-dark text-white border-secondary border-opacity-25">
                                <option value="">-- Aucun / Tous les joueurs --</option>
                                <?php foreach ($allLobbies as $l): ?>
                                    <option value="<?= $l->getId() ?>"><?= htmlspecialchars($l->getName()) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="text-secondary micro-text mt-1">Sélectionnez le lobby dont les participants s'affrontent.</div>
                        </div>

                        <!-- JEU (Optionnel si Phase Mère) -->
                        <div class="mb-3">
                            <label class="form-label small text-white fw-bold">Jeu (Pour sous-session)</label>
                            <select name="game" class="form-select bg-dark text-white border-secondary border-opacity-25">
                                <option value="">-- Aucun (Session Mère / Multi-jeux) --</option>
                                <?php foreach (Game::cases() as $game): ?>
                                    <option value="<?= htmlspecialchars($game->value) ?>"><?= htmlspecialchars($game->value) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small text-secondary mb-1">Description</label>
                            <textarea name="description" rows="2" class="form-control bg-dark text-white border-secondary border-opacity-25" placeholder="Description courte..."></textarea>
                        </div>

                        <div class="p-3 rounded bg-dark border border-secondary border-opacity-10 mb-3">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" name="isTeam" id="isTeam">
                                <label class="form-check-input-label text-white small fw-bold" for="isTeam">
                                    <i class="fa-solid fa-users me-1 text-info"></i> Format Équipe
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn w-100 fw-bold text-white" style="font-family: 'Orbitron', sans-serif; background: linear-gradient(45deg, #ff6b00, #ff4500); border: none;">
                            <i class="fa-solid fa-plus me-2"></i> Enregistrer
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <!-- LISTE DES PHASES ET LEURS ÉPREUVES -->
        <div class="<?= $isAdmin ? 'col-lg-8' : 'col-lg-10' ?>">
            <?php if (empty($phases)): ?>
                <p class="text-secondary text-center py-5">Aucune phase n'est configurée pour le moment.</p>
            <?php else: ?>
                <div class="d-flex flex-column gap-4">
                    <?php foreach ($phases as $phase): ?>
                        <?php 
                            $subSessions = array_filter($allSessions, fn(Session $s) => $s->getParentId() === $phase->getId());
                        ?>
                        <div class="p-4 rounded-3 border border-secondary border-opacity-25" style="background-color: #1f2833;">
                            
                            <!-- EN-TÊTE DE LA PHASE MÈRE -->
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3 pb-3 border-bottom border-secondary border-opacity-25">
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="badge border border-warning text-warning" style="background: rgba(255, 193, 7, 0.1);">
                                            <i class="fa-solid fa-trophy me-1"></i> Phase Mère (Cumulée)
                                        </span>
                                        <?php if ($phase->isTeam()): ?>
                                            <span class="badge border border-info text-info"><i class="fa-solid fa-users me-1"></i> Mode Équipe</span>
                                        <?php endif; ?>
                                    </div>
                                    <h3 class="fw-bold text-white mb-0" style="font-family: 'Orbitron', sans-serif;">
                                        <?= htmlspecialchars($phase->getName()) ?>
                                    </h3>
                                    <?php if (!empty($phase->getDescription())): ?>
                                        <div class="text-secondary small mt-1"><?= htmlspecialchars($phase->getDescription()) ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="d-flex align-items-center gap-2">
                                    <a href="index.php?page=session_scores&id=<?= urlencode($phase->getId()) ?>" class="btn fw-bold text-white px-3" style="font-family: 'Orbitron', sans-serif; background: rgba(255, 107, 0, 0.2); border: 1px solid #ff6b00;">
                                        <i class="fa-solid fa-chart-simple me-2"></i> Classement Cumulé
                                    </a>

                                    <?php if ($isAdmin): ?>
                                        <form method="POST" action="" onsubmit="return confirm('Supprimer cette Phase et ses sous-sessions ?');">
                                            <input type="hidden" name="action" value="supprimer_session">
                                            <input type="hidden" name="session_id" value="<?= $phase->getId() ?>">
                                            <button type="submit" class="btn btn-outline-danger border-0"><i class="fa-solid fa-trash-can"></i></button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- ÉPREUVES / SOUS-SESSIONS RATTACHÉES -->
                            <div class="ps-2">
                                <div class="text-secondary small fw-bold text-uppercase mb-2" style="font-family: 'Orbitron', sans-serif;">
                                    Épreuves de cette phase (<?= count($subSessions) ?>) :
                                </div>

                                <?php if (empty($subSessions)): ?>
                                    <p class="text-secondary small italic mb-0">Aucun jeu rattaché à cette phase pour l'instant.</p>
                                <?php else: ?>
                                    <div class="row g-2">
                                        <?php foreach ($subSessions as $sub): ?>
                                            <?php $associatedLobby = $sub->getLobbyId() ? $dataManager->getLobbyById($sub->getLobbyId()) : null; ?>
                                            <div class="col-md-6">
                                                <div class="p-3 rounded bg-dark border border-secondary border-opacity-10 d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <div class="fw-bold text-white small" style="font-family: 'Orbitron', sans-serif;">
                                                            <?= htmlspecialchars($sub->getName()) ?>
                                                        </div>
                                                        <div class="text-warning micro-text">
                                                            <i class="fa-solid fa-gamepad me-1"></i><?= htmlspecialchars($sub->getGame() ? $sub->getGame()->value : 'Jeu non spécifié') ?>
                                                        </div>
                                                        <?php if ($associatedLobby): ?>
                                                            <div class="text-info micro-text">
                                                                <i class="fa-solid fa-layer-group me-1"></i><?= htmlspecialchars($associatedLobby->getName()) ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <a href="index.php?page=session_scores&id=<?= urlencode($sub->getId()) ?>" class="btn btn-sm btn-outline-light text-nowrap">
                                                        Saisir / Voir <i class="fa-solid fa-arrow-right ms-1"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<style>
    .micro-text { font-size: 0.75rem; }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>