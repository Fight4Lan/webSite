<?php
$pageTitle = "Gestion des Lobbies & Équipes";
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../data/DataManager.php';

$dataManager = new DataManager();

$message = "";
$error = "";
$isAdmin = isset($_SESSION['admin']) && $_SESSION['admin'] === true;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isAdmin) {
    $action = $_POST['action'] ?? '';

    // 1. CRÉATION D'UN LOBBY (AVEC SES JOUEURS)
    if ($action === 'creer_lobby') {
        $lobbyName = trim($_POST['lobby_name'] ?? '');
        $selectedPlayers = $_POST['lobby_players'] ?? [];

        if (!empty($lobbyName)) {
            $newLobby = new Lobby(
                name: htmlspecialchars($lobbyName),
                playerIds: $selectedPlayers
            );

            if ($dataManager->addLobby($newLobby)) {
                $message = "Le lobby <strong>" . htmlspecialchars($lobbyName) . "</strong> a été créé !";
            } else {
                $error = "Erreur lors de la création du lobby.";
            }
        } else {
            $error = "Le nom du lobby est obligatoire.";
        }
    }

    // 2. CRÉATION D'UNE ÉQUIPE AU SEIN D'UN LOBBY
    if ($action === 'creer_equipe_lobby') {
        $lobbyId = $_POST['lobby_id'] ?? '';
        $teamName = trim($_POST['team_name'] ?? '');
        $selectedPlayers = $_POST['team_players'] ?? [];

        if (!empty($lobbyId) && !empty($teamName) && !empty($selectedPlayers)) {
            $lobby = $dataManager->getLobbyById($lobbyId);
            if ($lobby) {
                $newTeam = new Team(
                    name: htmlspecialchars($teamName),
                    playerIds: $selectedPlayers
                );
                
                // Enregistrement de l'équipe et association au lobby
                $dataManager->addTeam($newTeam);
                $lobby->addTeamId($newTeam->getId());
                $dataManager->updateLobby($lobby);

                $message = "L'équipe <strong>" . htmlspecialchars($teamName) . "</strong> a été ajoutée au lobby !";
            }
        } else {
            $error = "Veuillez choisir un lobby, un nom d'équipe et au moins un joueur.";
        }
    }

    // 3. SUPPRESSIONS
    if ($action === 'supprimer_lobby') {
        $lobbyId = $_POST['lobby_id'] ?? '';
        if ($dataManager->removeLobbyById($lobbyId)) {
            $message = "Lobby supprimé.";
        }
    }
}

$allPlayers = $dataManager->getPlayers();
$allLobbies = $dataManager->getLobbies();
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
                <li class="nav-item"><a class="nav-link text-white" href="index.php?page=lobby">Lobby</a></li>
                <li class="nav-item"><a class="nav-link text-white-50" href="index.php?page=ranking">Classements</a></li>
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
            Lobbies & <span style="color: #ff6b00;">Équipes</span>
        </h1>
        <p class="text-secondary">Crée tes lobbies, affecte les joueurs et forme les équipes internes</p>
    </div>

    <?php if (!empty($message)): ?>
        <div class="alert alert-success bg-dark text-success border-success mb-4"><?= $message ?></div>
    <?php endif; ?>

    <?php if ($isAdmin): ?>
        <div class="row g-4 mb-5">
            
            <!-- FORMULAIRE 1 : CRÉER UN LOBBY -->
            <div class="col-lg-6">
                <div class="p-4 rounded-3 border border-primary border-opacity-25 bg-dark h-100">
                    <h4 class="fw-bold mb-3 text-white" style="font-family: 'Orbitron', sans-serif; color: #ff6b00;">
                        1. Créer un Lobby
                    </h4>
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="creer_lobby">
                        <div class="mb-3">
                            <label class="form-label small text-white fw-bold">Nom du Lobby *</label>
                            <input type="text" name="lobby_name" class="form-control bg-dark text-white border-secondary border-opacity-25" required placeholder="Ex: Poule A">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-white fw-bold">Sélectionner les Joueurs du Lobby</label>
                            <div class="p-2 rounded bg-black bg-opacity-50 border border-secondary border-opacity-25" style="max-height: 150px; overflow-y: auto;">
                                <?php foreach ($allPlayers as $player): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="lobby_players[]" value="<?= $player->getId() ?>" id="lp_<?= $player->getId() ?>">
                                        <label class="form-check-label text-white small" for="lp_<?= $player->getId() ?>">
                                            <?= htmlspecialchars($player->getPseudo()) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold">Créer le Lobby</button>
                    </form>
                </div>
            </div>

            <!-- FORMULAIRE 2 : CRÉER UNE ÉQUIPE AU SEIN D'UN LOBBY -->
            <div class="col-lg-6">
                <div class="p-4 rounded-3 border border-info border-opacity-25 bg-dark h-100">
                    <h4 class="fw-bold mb-3 text-white" style="font-family: 'Orbitron', sans-serif; color: #0dcaf0;">
                        2. Créer une Équipe dans un Lobby
                    </h4>
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="creer_equipe_lobby">
                        <div class="mb-3">
                            <label class="form-label small text-white fw-bold">Choisir le Lobby *</label>
                            <select name="lobby_id" class="form-select bg-dark text-white border-secondary border-opacity-25" required>
                                <option value="">-- Sélectionner un Lobby --</option>
                                <?php foreach ($allLobbies as $l): ?>
                                    <option value="<?= $l->getId() ?>"><?= htmlspecialchars($l->getName()) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-white fw-bold">Nom de l'Équipe *</label>
                            <input type="text" name="team_name" class="form-control bg-dark text-white border-secondary border-opacity-25" required placeholder="Ex: Team Alpha">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-white fw-bold">Membres de l'équipe *</label>
                            <div class="p-2 rounded bg-black bg-opacity-50 border border-secondary border-opacity-25" style="max-height: 120px; overflow-y: auto;">
                                <?php foreach ($allPlayers as $player): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="team_players[]" value="<?= $player->getId() ?>" id="tp_<?= $player->getId() ?>">
                                        <label class="form-check-label text-white small" for="tp_<?= $player->getId() ?>">
                                            <?= htmlspecialchars($player->getPseudo()) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-info text-white w-100 fw-bold">Ajouter l'Équipe au Lobby</button>
                    </form>
                </div>
            </div>

        </div>
    <?php endif; ?>

    <!-- AFFICHAGE DES LOBBIES ET LEUR COMPOSITION -->
    <div class="row g-4">
        <?php foreach ($allLobbies as $lobby): ?>
            <div class="col-md-6">
                <div class="p-4 rounded-3 border border-secondary border-opacity-25 bg-dark h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom border-secondary border-opacity-25 pb-2">
                        <h4 class="fw-bold text-white mb-0" style="font-family: 'Orbitron', sans-serif; color: #ff6b00;">
                            <i class="fa-solid fa-layer-group me-2"></i><?= htmlspecialchars($lobby->getName()) ?>
                        </h4>
                        <?php if ($isAdmin): ?>
                            <form method="POST" action="" onsubmit="return confirm('Supprimer ce lobby ?');">
                                <input type="hidden" name="action" value="supprimer_lobby">
                                <input type="hidden" name="lobby_id" value="<?= $lobby->getId() ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger border-0"><i class="fa-solid fa-trash-can"></i></button>
                            </form>
                        <?php endif; ?>
                    </div>

                    <!-- ÉQUIPES DU LOBBY -->
                    <div class="mb-3">
                        <span class="small text-info fw-bold">Équipes dans ce lobby :</span>
                        <?php if (empty($lobby->getTeamIds())): ?>
                            <div class="text-secondary small italic">Aucune équipe formée.</div>
                        <?php else: ?>
                            <div class="d-flex flex-column gap-2 mt-1">
                                <?php foreach ($lobby->getTeamIds() as $tId): ?>
                                    <?php $team = $dataManager->getTeamById($tId); if (!$team) continue; ?>
                                    <div class="p-2 rounded border border-secondary border-opacity-10 bg-black bg-opacity-25">
                                        <div class="fw-bold text-info small"><i class="fa-solid fa-users me-1"></i><?= htmlspecialchars($team->getName()) ?></div>
                                        <div class="micro-text text-secondary">
                                            Membres: 
                                            <?php foreach ($team->getPlayerIds() as $pId): ?>
                                                <?php $p = $dataManager->getPlayerById($pId); if ($p): ?>
                                                    <span class="text-white-50 me-1"><?= htmlspecialchars($p->getPseudo()) ?></span>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- JOUEURS SOLOS DU LOBBY -->
                    <div>
                        <span class="small text-white fw-bold">Joueurs attribués à ce lobby :</span>
                        <div class="d-flex flex-wrap gap-1 mt-1">
                            <?php foreach ($lobby->getPlayerIds() as $pId): ?>
                                <?php $p = $dataManager->getPlayerById($pId); if (!$p) continue; ?>
                                <span class="badge bg-black border border-secondary text-white"><?= htmlspecialchars($p->getPseudo()) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>

                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>