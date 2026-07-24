<?php
$pageTitle = "Nos Partenaires";
require_once __DIR__ . '/header.php';

$partners = [
    [
        'id'          => 'despia',
        'name'        => 'Despia UHC',
        'logo'        => 'img/partners/logo_despia.jpg',
    ],
    [
        'id'          => 'azk',
        'name'        => 'AZK',
        'logo'        => 'img/partners/logo_azk.png',
    ],
    [
        'id'          => 'gala',
        'name'        => 'Galactite UHC',
        'logo'        => 'img/partners/logo_gala.jpg',
    ],
    [
        'id'          => 'chinois',
        'name'        => '一緒に より強い',
        'logo'        => 'img/partners/logo_chinois.png',
    ],
    [
        'id'          => 'axilton',
        'name'        => 'Axilton HUB 💜',
        'logo'        => 'img/partners/logo_axilton.png',
    ]
];
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
                <li class="nav-item"><a class="nav-link text-white-50" href="index.php?page=ranking">Classements</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="index.php?page=partners">Partenaires</a></li>
                
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
            Nos <span style="color: #ff6b00; text-shadow: 0 0 15px rgba(255, 107, 0, 0.4);">Partenaires</span>
        </h1>
        <p class="text-secondary">Découvre les structures, serveurs et sponsors qui nous accompagnent</p>
    </div>

    <!-- GRILLE DES PARTENAIRES -->
    <div class="row g-4 justify-content-center">
        <?php foreach ($partners as $partner): ?>
            <div class="col-md-6 col-lg-4">
                <a href="index.php?page=partner_detail&id=<?= urlencode($partner['id']) ?>" class="text-decoration-none">
                    <div class="partner-card h-100 p-4 rounded-3 border border-secondary border-opacity-10 d-flex flex-column align-items-center text-center position-relative overflow-hidden" 
                        style="background-color: #1f2833; transition: transform 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;">

                        <!-- LOGO / PP DU SERVEUR -->
                        <div class="partner-img-wrapper mb-3 rounded-circle overflow-hidden d-flex align-items-center justify-content-center" 
                            style="width: 110px; height: 110px; border: 2px solid #ff6b00; background-color: #0b0c10; box-shadow: 0 0 15px rgba(255, 107, 0, 0.2);">
                            <img src="<?= htmlspecialchars($partner['logo']) ?>" 
                                alt="PP de <?= htmlspecialchars($partner['name']) ?>" 
                                class="img-fluid w-100 h-100 object-fit-cover"
                                onerror="this.onerror=null; this.src='https://via.placeholder.com/110/0b0c10/ff6b00?text=PARTENAIRE';">
                        </div>

                        <!-- NOM DU PARTENAIRE -->
                        <h4 class="fw-bold text-white mb-2" style="font-family: 'Orbitron', sans-serif;">
                            <?= htmlspecialchars($partner['name']) ?>
                        </h4>

                        <!-- BOUTON D'ACCÈS -->
                        <div class="btn btn-sm w-100 fw-bold text-white" style="font-family: 'Orbitron', sans-serif; background: rgba(255, 107, 0, 0.15); border: 1px solid #ff6b00;">
                            Decouvrir le partenaire <i class="fa-solid fa-arrow-right ms-2"></i>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
    /* Effet Hover Néon sur la carte du partenaire */
    .partner-card:hover {
        transform: translateY(-5px);
        border-color: rgba(255, 107, 0, 0.5) !important;
        box-shadow: 0 10px 25px rgba(255, 107, 0, 0.2);
    }
    .partner-card:hover .partner-img-wrapper {
        box-shadow: 0 0 25px rgba(255, 107, 0, 0.5);
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>