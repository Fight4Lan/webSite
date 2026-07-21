<?php
$pageTitle = "Règlement Officiel";
require_once __DIR__ . '/header.php';
?>

<div class="container py-5 mt-5">

    <!-- TITRE DE LA PAGE -->
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold text-uppercase" style="font-family: 'Orbitron', sans-serif;">
            Règlement <span style="color: #ff6b00; text-shadow: 0 0 15px rgba(255, 107, 0, 0.4);">Officiel</span>
        </h1>
        <p class="text-secondary">Règles générales, fair-play et conditions de participation à la Fight4Lan</p>
    </div>

    <div class="row g-4">
        
        <!-- SOMMAIRE RAPIDE (STICKY) -->
        <div class="col-lg-3">
            <div class="p-3 rounded-3 border border-secondary border-opacity-10 sticky-top" style="background-color: #1f2833; top: 100px;">
                <h5 class="fw-bold mb-3 text-uppercase small" style="font-family: 'Orbitron', sans-serif; color: #ff6b00;">
                    <i class="fa-solid fa-list-ol me-2"></i>Sommaire
                </h5>
                <div class="nav flex-column nav-pills gap-1">
                    <a href="#art1" class="nav-link text-white-50 text-start py-2 px-3 rounded hover-orange">Art. 1 - Fair-play & Fair-use</a>
                    <a href="#art2" class="nav-link text-white-50 text-start py-2 px-3 rounded hover-orange">Art. 2 - Équipement & Réseau</a>
                    <a href="#art3" class="nav-link text-white-50 text-start py-2 px-3 rounded hover-orange">Art. 3 - Retards & Absences</a>
                    <a href="#art4" class="nav-link text-white-50 text-start py-2 px-3 rounded hover-orange">Art. 4 - Triche & Sanctions</a>
                    <a href="#art5" class="nav-link text-white-50 text-start py-2 px-3 rounded hover-orange">Art. 5 - Consommables & Pizza</a>
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
                        <h3 class="fw-bold mb-0 text-white" style="font-family: 'Orbitron', sans-serif;">Fair-play & Esprit LAN</h3>
                    </div>
                    <p class="text-secondary leading-relaxed">
                        Chaque participant s'engage à respecter ses adversaires, les organisateurs et le matériel mis à disposition. Le BM (*Bad Mouthing*) intempestif, le spam vocal et les insultes en match sont passibles d'un avertissement direct. 
                    </p>
                    <div class="p-3 rounded border border-secondary border-opacity-10 bg-dark text-white-50 small">
                        <i class="fa-solid fa-circle-info text-warning me-2"></i> 
                        <em>Rappel : Le trashtalk amical est toléré uniquement s'il reste dans le cadre du respect et de la bonne humeur.</em>
                    </div>
                </section>

                <hr class="border-secondary opacity-10 my-4">

                <!-- ARTICLE 2 -->
                <section id="art2" class="mb-5">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="badge border fw-bold px-2 py-1" style="background-color: rgba(255, 107, 0, 0.1); color: #ff6b00; border-color: rgba(255, 107, 0, 0.25) !important;">
                            ARTICLE 2
                        </span>
                        <h3 class="fw-bold mb-0 text-white" style="font-family: 'Orbitron', sans-serif;">Équipement & Bande Passante</h3>
                    </div>
                    <p class="text-secondary leading-relaxed">
                        Il est strictement interdit d'effectuer de gros téléchargements (Steam, torrents, mises à jour Windows) pendant la durée officielle des tournois afin de préserver le ping de l'assemblée.
                    </p>
                    <ul class="text-secondary small ms-3 space-y-1">
                        <li>Les joueurs doivent venir avec leur propre câble Ethernet de minimum 5 mètres.</li>
                        <li>Seules les multiprises fournies ou validées par le staff sont autorisées sur les tables principales.</li>
                    </ul>
                </section>

                <hr class="border-secondary opacity-10 my-4">

                <!-- ARTICLE 3 -->
                <section id="art3" class="mb-5">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="badge border fw-bold px-2 py-1" style="background-color: rgba(255, 107, 0, 0.1); color: #ff6b00; border-color: rgba(255, 107, 0, 0.25) !important;">
                            ARTICLE 3
                        </span>
                        <h3 class="fw-bold mb-0 text-white" style="font-family: 'Orbitron', sans-serif;">Gestion du Temps & Retards</h3>
                    </div>
                    <p class="text-secondary leading-relaxed">
                        L'horaire annoncé sur l'emploi du temps fait foi. Tout joueur ou toute équipe non présente dans le lobby <strong>10 minutes</strong> après le lancement de la phase de draft/préparation sera déclaré(e) forfait pour la manche en cours (Forfait 0-1).
                    </p>
                </section>

                <hr class="border-secondary opacity-10 my-4">

                <!-- ARTICLE 4 -->
                <section id="art4" class="mb-5">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="badge border fw-bold px-2 py-1" style="background-color: rgba(255, 107, 0, 0.1); color: #ff6b00; border-color: rgba(255, 107, 0, 0.25) !important;">
                            ARTICLE 4
                        </span>
                        <h3 class="fw-bold mb-0 text-white" style="font-family: 'Orbitron', sans-serif;">Anticheat & Exploits</h3>
                    </div>
                    <p class="text-secondary leading-relaxed">
                        L'utilisation de scripts, logiciels tiers de triche (aimbot, wallhack, macros complexes non autorisées) ou l'exploitation abusive d'un bug majeur reconnu par l'éditeur entraînera une <strong>disqualification immédiate</strong> du joueur et la suppression de ses points au classement général.
                    </p>
                </section>

                <hr class="border-secondary opacity-10 my-4">

                <!-- ARTICLE 5 -->
                <section id="art5">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="badge border fw-bold px-2 py-1" style="background-color: rgba(255, 107, 0, 0.1); color: #ff6b00; border-color: rgba(255, 107, 0, 0.25) !important;">
                            ARTICLE 5
                        </span>
                        <h3 class="fw-bold mb-0 text-white" style="font-family: 'Orbitron', sans-serif;">Nourriture & Boissons sur le Setup</h3>
                    </div>
                    <p class="text-secondary leading-relaxed">
                        Pour des raisons évidentes de sécurité du matériel informatique, les canettes ouvertes et assiettes de pizza doivent être maintenues à une distance raisonnable des claviers et tapis de souris. Un espace pause/buffet est prévu à cet effet.
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