<?php
$pageTitle = "Règlement Officiel";
require_once __DIR__ . '/header.php';
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
                <li class="nav-item"><a class="nav-link text-white" href="index.php?page=rules">Règlement</a></li>
                <li class="nav-item"><a class="nav-link text-white-50" href="index.php?page=players">Joueurs</a></li>
                <li class="nav-item"><a class="nav-link text-white-50" href="index.php?page=lobby">Lobby</a></li>
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
            Règlement <span style="color: #ff6b00; text-shadow: 0 0 15px rgba(255, 107, 0, 0.4);">Officiel</span>
        </h1>
        <p class="text-secondary">Bienvenue au Fight 4 Lan. Merci de prendre connaissance des règles ci-dessous.</p>
    </div>

    <!-- AVERTISSEMENT INITIAL -->
    <div class="alert alert-warning bg-dark text-warning border-warning border-opacity-25 mb-4 p-3 rounded-3" role="alert">
        <i class="fa-solid fa-triangle-exclamation me-2"></i>
        Si vous ne respectez pas ces règles, vous recevrez une sanction modérée selon votre « infraction ».
    </div>

    <div class="row g-4">
        
        <!-- SOMMAIRE RAPIDE (STICKY) -->
        <div class="col-lg-3">
            <div class="p-3 rounded-3 border border-secondary border-opacity-10 sticky-top" style="background-color: #1f2833; top: 100px;">
                <h5 class="fw-bold mb-3 text-uppercase small" style="font-family: 'Orbitron', sans-serif; color: #ff6b00;">
                    <i class="fa-solid fa-list-ol me-2"></i>Sommaire
                </h5>
                <div class="nav flex-column nav-pills gap-1">
                    <a href="#art1" class="nav-link text-white-50 text-start py-2 px-3 rounded hover-orange">Art. 1 - Ponctualité & Présence</a>
                    <a href="#art2" class="nav-link text-white-50 text-start py-2 px-3 rounded hover-orange">Art. 2 - Déconnexions & Restarts</a>
                    <a href="#art3" class="nav-link text-white-50 text-start py-2 px-3 rounded hover-orange">Art. 3 - Fair-play & Langage</a>
                    <a href="#art4" class="nav-link text-white-50 text-start py-2 px-3 rounded hover-orange">Art. 4 - Stream Hack</a>
                    <a href="#art5" class="nav-link text-white-50 text-start py-2 px-3 rounded hover-orange">Art. 5 - Anti-Focus & Alliance</a>
                    <a href="#art6" class="nav-link text-white-50 text-start py-2 px-3 rounded hover-orange">Art. 6 - Horaires & Briefings</a>
                    <a href="#art7" class="nav-link text-white-50 text-start py-2 px-3 rounded hover-orange">Art. 7 - Jeux & Remplacements</a>
                    <a href="#art8" class="nav-link text-white-50 text-start py-2 px-3 rounded hover-orange">Art. 8 - Profils & Anticheat</a>
                </div>
            </div>
        </div>

        <!-- CONTENU DU RÈGLEMENT -->
        <div class="col-lg-9">
            <div class="p-4 p-md-5 rounded-3 border border-secondary border-opacity-10" style="background-color: #1f2833;">
                
                <!-- ARTICLE 1 -->
                <section id="art1" class="mb-5">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="badge border fw-bold px-2 py-1" style="background-color: rgba(255, 107, 0, 0.1); color: #ff6b00; border-color: rgba(255, 107, 0, 0.25) !important;">
                            ARTICLE 1
                        </span>
                        <h3 class="fw-bold mb-0 text-white" style="font-family: 'Orbitron', sans-serif;">Ponctualité & Absence en Jeu</h3>
                    </div>
                    <p class="text-secondary leading-relaxed mb-0">
                        Si vous n'êtes pas présent lors du début d'un jeu, vous ne pourrez pas rejoindre pendant celui-ci et seulement au suivant. Vous écopez donc de la dernière place à celui-ci.
                    </p>
                </section>

                <hr class="border-secondary opacity-10 my-4">

                <!-- ARTICLE 2 -->
                <section id="art2" class="mb-5">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="badge border fw-bold px-2 py-1" style="background-color: rgba(255, 107, 0, 0.1); color: #ff6b00; border-color: rgba(255, 107, 0, 0.25) !important;">
                            ARTICLE 2
                        </span>
                        <h3 class="fw-bold mb-0 text-white" style="font-family: 'Orbitron', sans-serif;">Déconnexions & Restarts</h3>
                    </div>
                    <p class="text-secondary leading-relaxed mb-2">
                        Une déconnexion sur un jeu ne voudra pas directement dire de restart la partie (ce sera au staff de décider si la raison de la déconnexion est suffisante ou non et si le restart engendre un trop gros retard).
                    </p>
                    <p class="text-secondary leading-relaxed mb-0">
                        Une partie ne pourra plus être restart après 5 minutes de jeu.
                    </p>
                </section>

                <hr class="border-secondary opacity-10 my-4">

                <!-- ARTICLE 3 -->
                <section id="art3" class="mb-5">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="badge border fw-bold px-2 py-1" style="background-color: rgba(255, 107, 0, 0.1); color: #ff6b00; border-color: rgba(255, 107, 0, 0.25) !important;">
                            ARTICLE 3
                        </span>
                        <h3 class="fw-bold mb-0 text-white" style="font-family: 'Orbitron', sans-serif;">Fair-Play & Langage</h3>
                    </div>
                    <p class="text-secondary leading-relaxed mb-0">
                        Tous les joueurs sont priés de garder un Fair-play et de garder un langage correct aussi bien pendant la partie de training que dans la compétition elle-même.
                    </p>
                </section>

                <hr class="border-secondary opacity-10 my-4">

                <!-- ARTICLE 4 -->
                <section id="art4" class="mb-5">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="badge border fw-bold px-2 py-1" style="background-color: rgba(255, 107, 0, 0.1); color: #ff6b00; border-color: rgba(255, 107, 0, 0.25) !important;">
                            ARTICLE 4
                        </span>
                        <h3 class="fw-bold mb-0 text-white" style="font-family: 'Orbitron', sans-serif;">Stream Hack</h3>
                    </div>
                    <p class="text-secondary leading-relaxed mb-0">
                        Le F4L ne prendra en aucun cas la responsabilité en cas de Stream hack sauf si celui-ci est fait sur le Stream du cast.
                    </p>
                </section>

                <hr class="border-secondary opacity-10 my-4">

                <!-- ARTICLE 5 -->
                <section id="art5" class="mb-5">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="badge border fw-bold px-2 py-1" style="background-color: rgba(255, 107, 0, 0.1); color: #ff6b00; border-color: rgba(255, 107, 0, 0.25) !important;">
                            ARTICLE 5
                        </span>
                        <h3 class="fw-bold mb-0 text-white" style="font-family: 'Orbitron', sans-serif;">Anti-Focus & Alliances</h3>
                    </div>
                    <p class="text-secondary leading-relaxed mb-0">
                        Un focus sur un joueur dans le but de lui nuire sans raison sera sanctionné. En revanche, le focus stratégique pour des raisons de classement peut être toléré s'il n'y a pas d'abus (il ne faut pas que tout le lobby team sur un seul mec).
                    </p>
                </section>

                <hr class="border-secondary opacity-10 my-4">

                <!-- ARTICLE 6 -->
                <section id="art6" class="mb-5">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="badge border fw-bold px-2 py-1" style="background-color: rgba(255, 107, 0, 0.1); color: #ff6b00; border-color: rgba(255, 107, 0, 0.25) !important;">
                            ARTICLE 6
                        </span>
                        <h3 class="fw-bold mb-0 text-white" style="font-family: 'Orbitron', sans-serif;">Horaires & Présence en Préparation</h3>
                    </div>
                    <p class="text-secondary leading-relaxed mb-2">
                        Tous les jeux commenceront aux heures annoncées par les staffs, pas 1 minute après. À vous de vous arranger pour être à l'heure.
                    </p>
                    <p class="text-secondary leading-relaxed mb-0">
                        Votre présence en préparation entre chaque étape est obligatoire car les informations des jeux de l'étape et comment ils vont être joués vont y être annoncées.
                    </p>
                </section>

                <hr class="border-secondary opacity-10 my-4">

                <!-- ARTICLE 7 -->
                <section id="art7" class="mb-5">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="badge border fw-bold px-2 py-1" style="background-color: rgba(255, 107, 0, 0.1); color: #ff6b00; border-color: rgba(255, 107, 0, 0.25) !important;">
                            ARTICLE 7
                        </span>
                        <h3 class="fw-bold mb-0 text-white" style="font-family: 'Orbitron', sans-serif;">Remplacement & Programme</h3>
                    </div>
                    <p class="text-secondary leading-relaxed mb-2">
                        À part s'il y a un problème direct avec le jeu, aucun changement de jeu ne sera présent dans tout le tournoi.
                    </p>
                    <p class="text-secondary leading-relaxed mb-0">
                        Aucun remplacement de joueur ne sera disponible dans les équipes durant toute la compétition sauf pour raison extrême.
                    </p>
                </section>

                <hr class="border-secondary opacity-10 my-4">

                <!-- ARTICLE 8 -->
                <section id="art8">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="badge border fw-bold px-2 py-1" style="background-color: rgba(255, 107, 0, 0.1); color: #ff6b00; border-color: rgba(255, 107, 0, 0.25) !important;">
                            ARTICLE 8
                        </span>
                        <h3 class="fw-bold mb-0 text-white" style="font-family: 'Orbitron', sans-serif;">Profils & Anticheat</h3>
                    </div>
                    <p class="text-secondary leading-relaxed mb-2">
                        Aucun pseudo ou photo de profil insultante ou à caractère suspicieux ne sera toléré sur ce serveur. Vous recevrez d'abord un avertissement et si ce n'est pas changé dans l'heure, vous serez kick du serveur.
                    </p>
                    <p class="text-secondary leading-relaxed mb-0">
                        Les cheats en tout genre ne seront pas utilisés : quoi que ce soit qui vous donne un avantage en jeu est considéré comme tel.
                    </p>
                </section>

            </div>
        </div>

    </div>
</div>

<style>
    /* Effet sur les liens du sommaire */
    .hover-orange:hover {
        background-color: rgba(255, 107, 0, 0.1) !important;
        color: #ff6b00 !important;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>