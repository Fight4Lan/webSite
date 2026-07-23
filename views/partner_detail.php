<?php
$pageTitle = "Fiche Partenaire";
require_once __DIR__ . '/header.php';

// Récupération de l'ID du partenaire depuis l'URL
$partnerId = $_GET['id'] ?? 'despia-uhc';

// Liste complète des partenaires avec leurs informations
$partners = [
    'despia' => [
        'name'         => 'Despia UHC',
        'pp'           => 'img/partners/logo_despia.jpg',
        'discord_link' => 'https://discord.gg/rMBFmDxrd6',
        'pub'          => "# ✨ DESPIA UHC – Serveur Minecraft Bedrock :\n👥 Plus de **1000 membres** !\n*🎮 Des UHC uniques inspirés des plus grands animés*\n\n📌 **Univers disponibles** :\n\n👺 **Démon Slayer**\n🛡️ **Attack on Titan**\n⛓️ **Chainsaw Man**\n🏴‍☠️ **One Piece**\n\n🔧 **Fonctionnalités exclusives :**\n\n🔊 **Tchat vocal de proximité** comme Mumble\n⚖️ Gameplay **équilibré** et **compétitif** avec des **mises à jour régulières**\n📆 Host réguliers encadrés par un **staff réactif** et à votre **écoute**\n\n*🚀 Que tu sois joueur compétitif ou passionné d’animés,\nDespia UHC t’offre une expérience inédite sur Minecraft Bedrock.*"
    ],
    'azk' => [
        'name'         => 'AZK',
        'pp'           => 'img/partners/logo_azk.png',
        'discord_link' => 'https://discord.gg/AZK',
        'pub'          => "✨ **Bienvenue chez AZK !** ✨\n\n🔥 **Team AZK** : là où la passion pour **Roblox** rencontre un univers **multigaming** fun et plein de défis ! 💥\n\n**----- ASSOCIATION AZK -----**\n\n🚀 **Pourquoi nous rejoindre ?**\nImagine ce serveur comme une **aventure magique** ✨😉\nChez AZK, on est bien plus qu’une simple team de jeu : **on est une vraie famille** ! 💫\n\n🌍 Explore des mondes en constante évolution\n🔥 Relève des défis palpitants\n💪 Progresse aux côtés de joueurs passionnés\n\n🎮 **Pour tous les niveaux !**\nQue tu sois débutant(e) ou vétéran, chacun a sa place ici !\n🫂 Entraide, fun et esprit d’équipe sont nos maîtres mots.\n\n🤝 **Prêt(e) à vivre des moments inoubliables ?**\nRejoins notre aventure sur Discord :\n\n✨ Ta nouvelle team t’attend ! 💥"
    ],
    'gala' => [
        'name'         => 'Galactite UHC',
        'pp'           => 'img/partners/logo_gala.jpg',
        'discord_link' => 'https://discord.gg/YcQSfFyuFD',
        'pub'          => "# 🚀︱Galactite Bedrock︱🚀\n## Embarquez pour une odyssée galactique ! 🌌\n────────────────────\n\n## 🎮 *Qu’est-ce que Galactite ?*\nGalactite est un **serveur Minecraft Bedrock UHC** fondé par **DarkoZz & Mathieu** en 2019, réouvert en 2024 avec une ambition claire :\n→ Proposer un **serveur original, qualitatif et pensé pour les joueurs Bedrock**.\n\n## 🛠️ *Network ouvert 24h/24*\n- → ⭐ **Accumule des __Astres__ et transforme-les en __Étoiles__**\n- → 🧑‍💻 **__Crée__ tes propres parties __gratuitement__ grâce à tes Étoiles**\n- → 🛍️ **Boutique du Network : __cosmétiques, grades, monnaie__**\n- → 📆 **__Événements__ communautaires & __tournois__ uniques**\n- → 🧠 **Staff à l'écoute & règlement transparent**\n\n## 🎤 *Chat de Proximité*\nGalactite intègre un **système de chat de proximité** fonctionnant comme Mumble\n➡️ **Mais directement via une page web**, sans installation.\n\nCe système permet :\n- de parler **uniquement avec les joueurs proches de vous**,\n- d’avoir un volume qui varie selon la distance,\n- de vivre une expérience **immersive**, idéale pour les UHC à rôles.\n\n> ⚠️ Le chat de proximité est actuellement en **version expérimentale**.\n\n## 🧪 *Modes de jeu disponibles*\nInspirés des serveurs Java, adaptés pour Bedrock :\n- 🐺 • **Loup‑Garou UHC** – Meetup / Minage\n- 🛡️ • **Attack On Titans UHC** – Meetup\n- 👺 • **Demon Slayer UHC** – Meetup\n- 💨 • **Naruto UHC (Soon)** – Meetup\n- 📚 • **Death Note UHC v3** – Minage\n- 👥 • **Team Swappers UHC** – Meetup\n- ☁️ • **Player Market UHC (Soon)** – Meetup\n- 🍎 • **UHC Classique & similaires** – Minage\n→ Et bien d’autres en développement…\n\n## ✨ Et plein de nouveautés à venir ! 📣\n> *\"L’univers est grand, mais notre soif de découverte l’est encore plus.\"*\n> – Carl Sagan"
    ]
];

// Récupération du partenaire sélectionné ou par défaut
$partner = $partners[$partnerId] ?? $partners['despia-uhc'];

/**
 * Moteur de rendu du Markdown Discord basique vers du HTML propre
 */
function parseDiscordMarkdown(string $text): string {
    $text = htmlspecialchars($text);

    // Titres (# et ##)
    $text = preg_replace('/^# (.*?)$/m', '<h2 class="fw-bold text-white mb-2" style="font-family:\'Orbitron\', sans-serif; color: #ff6b00;">$1</h2>', $text);
    $text = preg_replace('/^## (.*?)$/m', '<h4 class="fw-bold text-white mt-3 mb-2" style="font-family:\'Orbitron\', sans-serif;">$1</h4>', $text);

    // Citations (> )
    $text = preg_replace('/^&gt; (.*?)$/m', '<blockquote class="border-start border-3 border-warning ps-3 text-italic text-white-50 my-2">$1</blockquote>', $text);

    // Formatting Discord : Gras (**), Souligné (__), Italique (*)
    $text = preg_replace('/\*\*(.*?)\*\*/s', '<strong class="text-white fw-bold">$1</strong>', $text);
    $text = preg_replace('/__(.*?)__/s', '<u class="text-white">$1</u>', $text);
    $text = preg_replace('/\*(.*?)\*/s', '<em class="text-white-50">$1</em>', $text);

    // Line breaks
    return nl2br($text);
}
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
    
    <!-- BOUTON RETOUR -->
    <a href="index.php?page=partners" class="btn btn-sm text-secondary border-0 mb-4 hover-orange">
        <i class="fa-solid fa-arrow-left me-2"></i>Retour aux partenaires
    </a>

    <div class="p-4 p-md-5 rounded-3 border border-secondary border-opacity-10" style="background-color: #1f2833; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
        <div class="row align-items-start g-4">
            
            <!-- BLOC GAUCHE : PP + NOM + BOUTON DISCORD -->
            <div class="col-lg-4 text-center">
                <div class="sticky-top" style="top: 100px;">
                    <div class="rounded-circle overflow-hidden d-inline-block p-1 border border-2 mb-3" 
                        style="width: 180px; height: 180px; border-color: #ff6b00 !important; background-color: #0b0c10; box-shadow: 0 0 25px rgba(255, 107, 0, 0.3);">
                        <img src="<?= htmlspecialchars($partner['pp']) ?>" 
                            alt="PP de <?= htmlspecialchars($partner['name']) ?>" 
                            class="img-fluid rounded-circle w-100 h-100 object-fit-cover"
                            onerror="this.onerror=null; this.src='https://via.placeholder.com/180/0b0c10/ff6b00?text=DISCORD';">
                    </div>

                    <h2 class="fw-bold text-white mb-3" style="font-family: 'Orbitron', sans-serif;">
                        <?= htmlspecialchars($partner['name']) ?>
                    </h2>

                    <a href="<?= htmlspecialchars($partner['discord_link']) ?>" 
                        target="_blank" 
                        rel="noopener noreferrer" 
                        class="btn w-100 fw-bold text-white py-3 d-inline-flex align-items-center justify-content-center gap-2" 
                        style="background-color: #5865F2; border: none; font-family: 'Orbitron', sans-serif; box-shadow: 0 4px 15px rgba(88, 101, 242, 0.3);">
                        <i class="fa-brands fa-discord fs-4"></i> Rejoindre le Discord
                    </a>
                </div>
            </div>

            <!-- BLOC DROITE : PUB STYLISÉE STYLE EMBED DISCORD -->
            <div class="col-lg-8">
                <div class="p-4 rounded-3 border-start border-4 text-white-50 fs-5" 
                    style="border-left-color: #5865F2 !important; background-color: #161c24 !important; line-height: 1.8;">
                    <?= parseDiscordMarkdown($partner['pub']) ?>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .hover-orange:hover {
        color: #ff6b00 !important;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>