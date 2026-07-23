<?php

// Chargement de toutes les entités depuis /entities/
require_once __DIR__ . '/../entities/Game.php';
require_once __DIR__ . '/../entities/Player.php';
require_once __DIR__ . '/../entities/Lobby.php';
require_once __DIR__ . '/../entities/Session.php';
require_once __DIR__ . '/../entities/Score.php';

class DataManager
{
    private string $playersPath;
    private string $sessionsPath;
    private string $scoresPath;

    public function __construct()
    {
        // Chemins absolus vers les fichiers JSON du dossier /data/
        $this->playersPath  = __DIR__ . '/players.json';
        $this->sessionsPath = __DIR__ . '/sessions.json';
        $this->scoresPath   = __DIR__ . '/scores.json';
    }

    // ==========================================
    // 1. GESTION DES JOUEURS (PLAYERS)
    // ==========================================

    /**
     * @return Player[]
     */
    public function getPlayers(): array
    {
        if (!file_exists($this->playersPath)) {
            return [];
        }

        $data = json_decode(file_get_contents($this->playersPath), true);
        if (!is_array($data)) {
            return [];
        }

        $players = [];
        foreach ($data as $playerData) {
            $players[] = Player::fromArray($playerData);
        }

        return $players;
    }

    /**
     * @param Player[] $players
     */
    private function savePlayers(array $players): bool
    {
        $data = array_map(fn(Player $p) => $p->toArray(), $players);
        return file_put_contents($this->playersPath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
    }

    public function addPlayer(Player $player): bool
    {
        $players = $this->getPlayers();
        $players[] = $player;
        return $this->savePlayers($players);
    }

    public function removePlayerById(string $id): bool
    {
        $players = $this->getPlayers();
        $initialCount = count($players);

        $filtered = array_filter($players, fn(Player $p) => $p->getId() !== $id);

        if (count($filtered) === $initialCount) {
            return false;
        }

        return $this->savePlayers(array_values($filtered));
    }

    public function getPlayerById(string $id): ?Player
    {
        foreach ($this->getPlayers() as $player) {
            if ($player->getId() === $id) {
                return $player;
            }
        }
        return null;
    }

    // ==========================================
    // 2. GESTION DES SESSIONS & LOBBIES
    // ==========================================

    /**
     * @return Session[]
     */
    public function getSessions(): array
    {
        if (!file_exists($this->sessionsPath)) {
            return [];
        }

        $data = json_decode(file_get_contents($this->sessionsPath), true);
        if (!is_array($data)) {
            return [];
        }

        $sessions = [];
        foreach ($data as $sessionData) {
            $sessions[] = Session::fromArray($sessionData);
        }

        return $sessions;
    }

    /**
     * @param Session[] $sessions
     */
    private function saveSessions(array $sessions): bool
    {
        $data = array_map(fn(Session $s) => $s->toArray(), $sessions);
        return file_put_contents($this->sessionsPath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
    }

    public function addSession(Session $session): bool
    {
        $sessions = $this->getSessions();
        $sessions[] = $session;
        return $this->saveSessions($sessions);
    }

    public function getSessionById(string $id): ?Session
    {
        foreach ($this->getSessions() as $session) {
            if ($session->getId() === $id) {
                return $session;
            }
        }
        return null;
    }

    public function removeSessionById(string $id): bool
    {
        $sessions = $this->getSessions();
        $initialCount = count($sessions);

        $filtered = array_filter($sessions, fn(Session $s) => $s->getId() !== $id);

        if (count($filtered) === $initialCount) {
            return false;
        }

        // On supprime aussi tous les scores associés à cette session
        $this->removeScoresBySessionId($id);

        return $this->saveSessions(array_values($filtered));
    }

    /**
     * Permet de sauvegarder des modifications apportées à une session existante (ex: ajout d'un lobby)
     */
    public function updateSession(Session $updatedSession): bool
    {
        $sessions = $this->getSessions();
        $updated = false;

        foreach ($sessions as $index => $session) {
            if ($session->getId() === $updatedSession->getId()) {
                $sessions[$index] = $updatedSession;
                $updated = true;
                break;
            }
        }

        return $updated ? $this->saveSessions($sessions) : false;
    }

    // ==========================================
    // 3. GESTION DES SCORES / RÉSULTATS
    // ==========================================

    /**
     * @return Score[]
     */
    public function getScores(): array
    {
        if (!file_exists($this->scoresPath)) {
            return [];
        }

        $data = json_decode(file_get_contents($this->scoresPath), true);
        if (!is_array($data)) {
            return [];
        }

        $scores = [];
        foreach ($data as $scoreData) {
            $scores[] = Score::fromArray($scoreData);
        }

        return $scores;
    }

    /**
     * @param Score[] $scores
     */
    private function saveScores(array $scores): bool
    {
        $data = array_map(fn(Score $s) => $s->toArray(), $scores);
        return file_put_contents($this->scoresPath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
    }

    /**
     * Récupère les scores pour une session donnée
     * @return Score[]
     */
    public function getScoresBySessionId(string $sessionId): array
    {
        return array_values(array_filter(
            $this->getScores(),
            fn(Score $s) => $s->getSessionId() === $sessionId
        ));
    }

    /**
     * Récupère les scores pour un lobby spécifique
     * @return Score[]
     */
    public function getScoresByLobbyId(string $lobbyId): array
    {
        return array_values(array_filter(
            $this->getScores(),
            fn(Score $s) => $s->getLobbyId() === $lobbyId
        ));
    }

    /**
     * Ajoute ou met à jour le score d'un joueur dans une session
     */
    public function saveOrUpdateScore(Score $score): bool
    {
        $scores = $this->getScores();
        $found = false;

        // Si le score existe déjà pour ce joueur et cette session, on le met à jour
        foreach ($scores as $index => $existingScore) {
            if (
                $existingScore->getSessionId() === $score->getSessionId() &&
                $existingScore->getPlayerId() === $score->getPlayerId()
            ) {
                $scores[$index] = $score;
                $found = true;
                break;
            }
        }

        // Sinon on l'ajoute
        if (!$found) {
            $scores[] = $score;
        }

        return $this->saveScores($scores);
    }

    /**
     * Supprime tous les scores liés à une session
     */
    public function removeScoresBySessionId(string $sessionId): bool
    {
        $scores = $this->getScores();
        $filtered = array_filter($scores, fn(Score $s) => $s->getSessionId() !== $sessionId);
        return $this->saveScores(array_values($filtered));
    }
}