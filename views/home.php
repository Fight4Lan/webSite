<?php
$pageTitle = "Accueil";
// Puisqu'on est dans le dossier views/, le header est juste à côté !
require_once __DIR__ . '/../header.php';
?>


    <!-- NAV BAR GLOBALE (Style Fight4Lan) -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top border-bottom border-secondary border-opacity-10" style="background-color: rgba(11, 12, 16, 0.85); backdrop-filter: blur(10px);">
        <div class="container">
            <a class="navbar-brand fw-bold text-uppercase tracking-wider" href="#" style="font-family: 'Orbitron', sans-serif;">
                <span style="color: #ff6b00;">Fight4</span>Lan
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto fw-medium text-uppercase small" style="font-family: 'Orbitron', sans-serif;">
                    <li class="nav-item"><a class="nav-link active" href="#">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link text-white-50" href="#">Règlement</a></li>
                    <li class="nav-item"><a class="nav-link text-white-50" href="#">Joueurs</a></li>
                    <li class="nav-item"><a class="nav-link text-white-50" href="#">Lobby</a></li>
                    <li class="nav-item"><a class="nav-link text-white-50" href="#">Classements</a></li>
                    <li class="nav-item"><a class="nav-link text-white-50" href="#">Partenaires</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <div class="d-flex align-items-center pt-5 mt-5" style="background: radial-gradient(circle at 80% 20%, rgba(111, 66, 193, 0.15), transparent 45%); min-height: 70vh;">
        <div class="container text-center text-lg-start my-5 py-5">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <span class="badge mb-3 px-3 py-2" style="font-family: 'Orbitron', sans-serif; background-color: rgba(111,66,193,0.2); color: #bb86fc; border: 1px solid #6f42c1;">3ème ÉDITION</span>
                    <h1 class="display-3 fw-bolder mb-3" style="font-family: 'Orbitron', sans-serif; line-height: 1.1;">
                        REJOINS LE <br><span style="text-shadow: 0 0 20px rgba(0,245,255,0.4); color: #ff6b00;">FIGHT4LAN</span>
                    </h1>
                    <div class="d-sm-flex justify-content-center justify-content-lg-start gap-3">
                        <a href="#tournois" class="btn text-white px-4 py-3 fw-bold shadow-lg mb-2 mb-sm-0" style="font-family: 'Orbitron', sans-serif; background: linear-gradient(45deg, #6f42c1, #4b2394); box-shadow: 0 0 15px rgba(111, 66, 193, 0.4); border: none;"><i class="fa-solid fa-gamepad me-2"></i>Voir les Tournois</a>
                        <a href="#" class="btn btn-outline-secondary text-white px-4 py-3 d-inline-flex align-items-center justify-content-center" style="font-family: 'Orbitron', sans-serif;">Discord <i class="fa-brands fa-discord ms-2"></i></a>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block text-center">
                    <i class="fa-solid fa-trophy" style="font-size: 14rem; color: #6f42c1; filter: drop-shadow(0 0 30px rgba(111,66,193,0.6));"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="container my-5">
        <div class="row g-3 justify-content-center">
            
            <!-- Joueurs -->
            <div class="col-sm-6 col-md-4">
                <div class="p-4 rounded-3 border border-secondary border-opacity-10 text-center" style="background-color: #1f2833; box-shadow: 0 4px 15px rgba(0, 245, 255, 0.05);">
                    <div class="d-flex align-items-center justify-content-center gap-3">
                        <i class="fa-solid fa-user-astronaut fs-2" style="color: #ff6b00; filter: drop-shadow(0 0 8px rgba(237, 158, 31, 0.88));"></i>
                        <div class="text-start">
                            <!-- Tu pourras remplacer le 142 par une variable PHP dynamique plus tard -->
                            <h3 class="fw-bold mb-0" style="font-family: 'Orbitron', sans-serif; color: #ff6b00;">0</h3>
                            <span class="text-secondary small text-uppercase tracking-wider fw-medium">Joueurs Inscrits</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- nb de jeux -->
            <div class="col-sm-6 col-md-4">
                <div class="p-4 rounded-3 border border-secondary border-opacity-10 text-center" style="background-color: #1f2833; box-shadow: 0 4px 15px rgba(111, 66, 193, 0.05);">
                    <div class="d-flex align-items-center justify-content-center gap-3">
                        <i class="fa-solid fa-gamepad fs-2" style="color: #6f42c1; filter: drop-shadow(0 0 8px rgba(111,66,193,0.5));"></i>
                        <div class="text-start">
                            <h3 class="fw-bold mb-0" style="font-family: 'Orbitron', sans-serif; color: #ff6b00;">6</h3>
                            <span class="text-secondary small text-uppercase tracking-wider fw-medium">Jeux</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION TOURNOIS -->
    <div class="container py-5 text-center" id="tournois">
        <h2 class="mb-5 text-uppercase fw-bold tracking-wider" style="font-family: 'Orbitron', sans-serif;">Les Jeux Officiels</h2>
        
        <div class="row g-4 text-start">
            <!-- Carte 1 : Disney Speedstorm -->
            <div class="col-md-4">
                <div class="p-4 h-100 border border-secondary border-opacity-10 rounded-3" style="background-color: #1f2833; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge border fw-bold px-2 py-1" style="background-color: rgba(0, 245, 255, 0.1); color: #ff6b00; border-color: rgba(0, 245, 255, 0.2) !important;">COURSE</span>
                        <span class="text-white-50 small"><i class="fa-solid fa-car me-1"></i> Max 8 / course</span>
                    </div>
                    <h4 class="fw-bold mb-2" style="font-family: 'Orbitron', sans-serif;">Disney Speedstorm</h4>
                    <p class="text-secondary small mb-4">Des dérapages, des bonus survoltés et les héros de ton enfance. Franchis le premier la ligne d'arrivée pour l'emporter.</p>
                    <hr class="border-secondary opacity-25 my-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold fs-5" style="color: #ff6b00;">Jeu #1</span>
                    </div>
                </div>
            </div>

            <!-- Carte 2 : Overwatch -->
            <div class="col-md-4">
                <div class="p-4 h-100 border border-secondary border-opacity-10 rounded-3" style="background-color: #1f2833; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge border fw-bold px-2 py-1" style="background-color: rgba(0, 245, 255, 0.1); color: #ff6b00; border-color: rgba(0, 245, 255, 0.2) !important;">FFA</span>
                        <span class="text-white-50 small"><i class="fa-solid fa-crosshairs me-1"></i> Chacun pour soi</span>
                    </div>
                    <h4 class="fw-bold mb-2" style="font-family: 'Orbitron', sans-serif;">Overwatch</h4>
                    <p class="text-secondary small mb-4">Pas de coéquipiers pour te carry cette fois. Le mode chacun pour soi va révéler qui a le meilleur aim et la meilleure réactivité.</p>
                    <hr class="border-secondary opacity-25 my-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold fs-5" style="color: #ff6b00;">Jeu #2</span>
                    </div>
                </div>
            </div>

            <!-- Carte 3 : Apex Legends -->
            <div class="col-md-4">
                <div class="p-4 h-100 border border-secondary border-opacity-10 rounded-3" style="background-color: #1f2833; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge border fw-bold px-2 py-1" style="background-color: rgba(0, 245, 255, 0.1); color: #ff6b00; border-color: rgba(0, 245, 255, 0.2) !important;">BATTLE ROYALE</span>
                        <span class="text-white-50 small"><i class="fa-solid fa-skull me-1"></i> Battle Royal</span>
                    </div>
                    <h4 class="fw-bold mb-2" style="font-family: 'Orbitron', sans-serif;">Apex Legends</h4>
                    <p class="text-secondary small mb-4">Gère la zone, loote efficacement et utilise tes compétences ultimes au pixel près pour être la dernière squad debout.</p>
                    <hr class="border-secondary opacity-25 my-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold fs-5" style="color: #ff6b00;">Jeu #3</span>
                    </div>
                </div>
            </div>

            <!-- Carte 4 : Chess.com -->
            <div class="col-md-4">
                <div class="p-4 h-100 border border-secondary border-opacity-10 rounded-3" style="background-color: #1f2833; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge border fw-bold px-2 py-1" style="background-color: rgba(0, 245, 255, 0.1); color: #ff6b00; border-color: rgba(0, 245, 255, 0.2) !important;">1 VS 1</span>
                        <span class="text-white-50 small"><i class="fa-solid fa-chess-knight me-1"></i> 1v1 </span>
                    </div>
                    <h4 class="fw-bold mb-2" style="font-family: 'Orbitron', sans-serif;">Chess.com</h4>
                    <p class="text-secondary small mb-4">Le combat intellectuel ultime. Prépare tes ouvertures et prévois tes coups avec 3 coups d'avance sous la pression du chrono.</p>
                    <hr class="border-secondary opacity-25 my-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold fs-5" style="color: #ff6b00;">Jeu #4</span>
                    </div>
                </div>
            </div>

            <!-- Carte 5 : Geoguessr -->
            <div class="col-md-4">
                <div class="p-4 h-100 border border-secondary border-opacity-10 rounded-3" style="background-color: #1f2833; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge border fw-bold px-2 py-1" style="background-color: rgba(0, 245, 255, 0.1); color: #ff6b00; border-color: rgba(0, 245, 255, 0.2) !important;">FFA</span>
                        <span class="text-white-50 small"><i class="fa-solid fa-earth-americas me-1"></i> Chacun pour soi</span>
                    </div>
                    <h4 class="fw-bold mb-2" style="font-family: 'Orbitron', sans-serif;">Geoguessr</h4>
                    <p class="text-secondary small mb-4">Poteaux téléphoniques, lignes au sol, sol de terre battue... Analyse chaque pixel pour trouver où tu es le plus vite possible.</p>
                    <hr class="border-secondary opacity-25 my-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold fs-5" style="color: #ff6b00;">Jeu #5</span>
                    </div>
                </div>
            </div>

            <!-- Carte 6 : Brawlhalla -->
            <div class="col-md-4">
                <div class="p-4 h-100 border border-secondary border-opacity-10 rounded-3" style="background-color: #1f2833; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge border fw-bold px-2 py-1" style="background-color: rgba(0, 245, 255, 0.1); color: #ff6b00; border-color: rgba(0, 245, 255, 0.2) !important;">1 VS 1</span>
                        <span class="text-white-50 small"><i class="fa-solid fa-hand-fist me-1"></i> 1v1</span>
                    </div>
                    <h4 class="fw-bold mb-2" style="font-family: 'Orbitron', sans-serif;">Brawlhalla</h4>
                    <p class="text-secondary small mb-4">Choisis ta légende, ramasse tes armes et éjecte ton adversaire hors de la map. Aucun compromis possible en arène.</p>
                    <hr class="border-secondary opacity-25 my-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold fs-5" style="color: #ff6b00;">Jeu #6</span>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-naga-panel to-naga-panel-2 border border-[rgba(140,151,173,.16)] relative transition-transform duration-[.22s] hover:-translate-y-[5px] clip overflow-hidden">
                <div class="ph relative aspect-[16/10]">
                    <div class="ph-glow"></div>
                    <img src="./../img/logo.png" class="absolute inset-0 w-full h-full object-cover">
                </div>
                <div class="p-4 pb-[18px]">
                    <h3 class="font-disp font-bold text-[18px] uppercase m-0"> Test </h3>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="text-center py-4 mt-5 border-top border-secondary border-opacity-25" style="background-color: #050508;">
        <p class="text-secondary mb-0 small">&copy; 2026 Fight4Lan. Tous droits réservés. Design de l'élite gaming.</p>
    </footer>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>