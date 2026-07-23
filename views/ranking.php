<?php
$pageTitle = "Sessions & Classements";
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../data/DataManager.php';

$dataManager = new DataManager();

$message = "";
$error = "";

// -------------------------------------------------------------
// TRAITEMENT DU FORMULAIRE DE CRÉATION DE SESSION (ADMIN)
// -------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
        $error = "Action non autorisée. Vous devez être administrateur.";
    } else {
        if ($_POST['action'] === 'creer_session') {
            $name = trim($_POST['name'] ?? '');
            $gameValue = $_POST['game'] ?? '';
            $description = trim($_POST['description'] ?? '');
            $isTeam = isset($_POST['isTeam']);
            $hasLobbies = isset($_POST['hasLobbies']);

            if (!empty($name) && !empty($gameValue)) {
                try {
                    $gameEnum = Game::from($gameValue);
                    
                    $newSession = new Session(
                        name: htmlspecialchars($name),
                        game: $gameEnum,
                        description: htmlspecialchars($description),
                        isTeam: $isTeam,
                        hasLobbies: $hasLobbies
                    );

                    if ($dataManager->addSession($newSession)) {
                        $message = "La session <strong>" . htmlspecialchars($name) . "</strong> a été créée avec succès !";
                    } else {
                        $error = "Une erreur est survenue lors de l'enregistrement de la session.";
                    }
                } catch (\ValueError $e) {
                    $error = "Le jeu sélectionné est invalide.";
                }
            } else {
                $error = "Le nom de la session et le jeu sont obligatoires.";
            }
        }

        // SUPPRESSION D'UNE SESSION
        if ($_POST['action'] === 'supprimer_session') {
            $sessionId = $_POST['session_id'] ?? '';
            if (!empty($sessionId)) {
                if ($dataManager->removeSessionById($sessionId)) {
                    $message = "La session et ses scores ont été supprimés avec succès.";
                } else {
                    $error = "Impossible de supprimer la session sélectionnée.";
                }
            }
        }
    }
}

// Récupération de toutes les sessions
$sessions = $dataManager->getSessions();
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
            Sessions & <span style="color: #ff6b00; text-shadow: 0 0 15px rgba(255, 107, 0, 0.4);">Classements</span>
        </h1>
        <p class="text-secondary">Consulte les résultats et classements de chaque épreuve de la LAN</p>
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

        <!-- FORMULAIRE DE CRÉATION DE SESSION (ADMIN) -->
        <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
            <div class="col-lg-4">
                <div class="p-4 rounded-3 border border-warning border-opacity-25" style="background-color: #1f2833;">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4 class="fw-bold mb-0" style="font-family: 'Orbitron', sans-serif; color: #ff6b00;">
                            Créer une Session
                        </h4>
                        <span class="badge border border-warning text-warning" style="background: rgba(255, 193, 7, 0.1);">Admin</span>
                    </div>

                    <form method="POST" action="">
                        <input type="hidden" name="action" value="creer_session">

                        <!-- NOM DE LA SESSION -->
                        <div class="mb-3">
                            <label class="form-label small text-white fw-bold">Nom de l'épreuve *</label>
                            <input type="text" name="name" class="form-control bg-dark text-white border-secondary border-opacity-25" required placeholder="Ex: Phase de Poules - Match 1">
                        </div>

                        <!-- JEU CONCERNÉ (ENUM) -->
                        <div class="mb-3">
                            <label class="form-label small text-white fw-bold">Jeu *</label>
                            <select name="game" class="form-select bg-dark text-white border-secondary border-opacity-25" required>
                                <option value="" disabled selected>-- Choisir un jeu --</option>
                                <?php foreach (Game::cases() as $game): ?>
                                    <option value="<?= htmlspecialchars($game->value) ?>">
                                        <?= htmlspecialchars($game->value) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- DESCRIPTION -->
                        <div class="mb-3">
                            <label class="form-label small text-secondary mb-1">Description / Format</label>
                            <textarea name="description" rows="2" class="form-control bg-dark text-white border-secondary border-opacity-25" placeholder="Ex: Format BO3, carte Rainbow Road..."></textarea>
                        </div>

                        <!-- OPTIONS DE FORMAT -->
                        <div class="p-3 rounded bg-dark border border-secondary border-opacity-10 mb-3">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="isTeam" id="isTeam">
                                <label class="form-check-input-label text-white small fw-bold" for="isTeam">
                                    <i class="fa-solid fa-users me-1 text-info"></i> Format Équipe
                                </label>
                                <div class="text-secondary micro-text">Cochez si les résultats sont attribués par équipe.</div>
                            </div>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="hasLobbies" id="hasLobbies">
                                <label class="form-check-input-label text-white small fw-bold" for="hasLobbies">
                                    <i class="fa-solid fa-layer-group me-1 text-warning"></i> Réparti en Lobbies / Poules
                                </label>
                                <div class="text-secondary micro-text">Cochez si la session nécessite plusieurs poules.</div>
                            </div>
                        </div>

                        <button type="submit" class="btn w-100 fw-bold text-white" style="font-family: 'Orbitron', sans-serif; background: linear-gradient(45deg, #ff6b00, #ff4500); border: none;">
                            <i class="fa-solid fa-plus me-2"></i> Enregistrer l'épreuve
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <!-- LISTE DES SESSIONS -->
        <div class="<?= (isset($_SESSION['admin']) && $_SESSION['admin'] === true) ? 'col-lg-8' : 'col-lg-10' ?>">
            <?php if (empty($sessions)): ?>
                <div class="p-5 rounded-3 text-center border border-secondary border-opacity-10" style="background-color: #1f2833;">
                    <i class="fa-solid fa-trophy fs-1 text-secondary mb-3"></i>
                    <h4 class="fw-bold text-white mb-2" style="font-family: 'Orbitron', sans-serif;">Aucune session configurée</h4>
                    <p class="text-secondary mb-0">Les sessions de tournois et leurs classements apparaîtront ici.</p>
                </div>
            <?php else: ?>
                <div class="row g-3">
                    <?php foreach ($sessions as $session): ?>
                        <div class="col-12">
                            <div class="p-4 rounded-3 border border-secondary border-opacity-10 position-relative overflow-hidden" 
                                 style="background-color: #1f2833; transition: border-color 0.3s ease;">
                                
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                    <div>
                                        <!-- BADGES ET METADATAS -->
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <span class="badge border border-warning text-warning" style="background: rgba(255, 193, 7, 0.1);">
                                                <i class="fa-solid fa-gamepad me-1"></i> <?= htmlspecialchars($session->getGame()->value) ?>
                                            </span>

                                            <?php if ($session->isTeam()): ?>
                                                <span class="badge border border-info text-info" style="background: rgba(13, 202, 240, 0.1);">
                                                    <i class="fa-solid fa-users me-1"></i> Équipe
                                                </span>
                                            <?php else: ?>
                                                <span class="badge border border-secondary text-secondary bg-dark">
                                                    <i class="fa-solid fa-user me-1"></i> Solo
                                                </span>
                                            <?php endif; ?>

                                            <?php if ($session->hasLobbies()): ?>
                                                <span class="badge border border-primary text-primary" style="background: rgba(13, 110, 253, 0.1);">
                                                    <i class="fa-solid fa-layer-group me-1"></i> Poules/Lobbies
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <!-- TITRE & DESCRIPTION -->
                                        <h3 class="fw-bold text-white mb-1" style="font-family: 'Orbitron', sans-serif;">
                                            <?= htmlspecialchars($session->getName()) ?>
                                        </h3>
                                        <?php if (!empty($session->getDescription())): ?>
                                            <p class="text-secondary small mb-0"><?= htmlspecialchars($session->getDescription()) ?></p>
                                        <?php endif; ?>
                                    </div>

                                    <!-- BOUTONS D'ACTION (VOIR SCORES & SUPPRESSION) -->
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="index.php?page=session_scores&id=<?= urlencode($session->getId()) ?>" 
                                           class="btn fw-bold text-white px-4 py-2" 
                                           style="font-family: 'Orbitron', sans-serif; background: rgba(255, 107, 0, 0.15); border: 1px solid #ff6b00;">
                                            <i class="fa-solid fa-list-ol me-2"></i> Voir les scores
                                        </a>

                                        <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
                                            <button type="button" 
                                                    class="btn btn-outline-danger border-0" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteSessionModal_<?= $session->getId() ?>"
                                                    title="Supprimer la session">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>

                                            <!-- MODAL SUPPRESSION -->
                                            <div class="modal fade" id="deleteSessionModal_<?= $session->getId() ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content bg-dark text-white border border-secondary">
                                                        <div class="modal-header border-secondary">
                                                            <h5 class="modal-title fw-bold" style="font-family: 'Orbitron', sans-serif; color: #ff6b00;">
                                                                <i class="fa-solid fa-triangle-exclamation me-2"></i>Confirmation
                                                            </h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body text-start">
                                                            Êtes-vous sûr de vouloir supprimer la session <strong><?= htmlspecialchars($session->getName()) ?></strong> ? <br>
                                                            <small class="text-danger">Attention : tous les scores enregistrés pour cette épreuve seront également effacés.</small>
                                                        </div>
                                                        <div class="modal-footer border-secondary">
                                                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                            <form method="POST" action="" class="d-inline">
                                                                <input type="hidden" name="action" value="supprimer_session">
                                                                <input type="hidden" name="session_id" value="<?= $session->getId() ?>">
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

                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<style>
    .micro-text {
        font-size: 0.75rem;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>