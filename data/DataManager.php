<?php

require_once __DIR__ . '/../entities/Game.php';
require_once __DIR__ . '/../entities/Player.php';
require_once __DIR__ . '/../entities/Team.php';
require_once __DIR__ . '/../entities/Lobby.php';
require_once __DIR__ . '/../entities/Session.php';
require_once __DIR__ . '/../entities/Score.php';

class DataManager
{
    private string $playersPath;
    private string $teamsPath;
    private string $lobbiesPath;
    private string $sessionsPath;
    private string $scoresPath;

    public function __construct()
    {
        $this->playersPath  = __DIR__ . '/players.json';
        $this->teamsPath    = __DIR__ . '/teams.json';
        $this->lobbiesPath  = __DIR__ . '/lobbies.json';
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

    public function getPlayerById(string $id): ?Player
    {
        foreach ($this->getPlayers() as $player) {
            if ($player->getId() === $id) {
                return $player;
            }
        }
        return null;
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

    // ==========================================
    // 2. GESTION DES ÉQUIPES (TEAMS)
    // ==========================================

    /**
     * @return Team[]
     */
    public function getTeams(): array
    {
        if (!file_exists($this->teamsPath)) {
            return [];
        }

        $data = json_decode(file_get_contents($this->teamsPath), true);
        if (!is_array($data)) {
            return [];
        }

        $teams = [];
        foreach ($data as $teamData) {
            $teams[] = Team::fromArray($teamData);
        }

        return $teams;
    }

    /**
     * @param Team[] $teams
     */
    private function saveTeams(array $teams): bool
    {
        $data = array_map(fn(Team $t) => $t->toArray(), $teams);
        return file_put_contents($this->teamsPath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
    }

    public function addTeam(Team $team): bool
    {
        $teams = $this->getTeams();
        $teams[] = $team;
        return $this->saveTeams($teams);
    }

    public function getTeamById(string $id): ?Team
    {
        foreach ($this->getTeams() as $team) {
            if ($team->getId() === $id) {
                return $team;
            }
        }
        return null;
    }

    public function removeTeamById(string $id): bool
    {
        $teams = $this->getTeams();
        $initialCount = count($teams);

        $filtered = array_filter($teams, fn(Team $t) => $t->getId() !== $id);

        if (count($filtered) === $initialCount) {
            return false;
        }

        return $this->saveTeams(array_values($filtered));
    }

    // ==========================================
    // 3. GESTION DES LOBBIES / POULES (LOBBIES)
    // ==========================================

    /**
     * @return Lobby[]
     */
    public function getLobbies(): array
    {
        if (!file_exists($this->lobbiesPath)) {
            return [];
        }

        $data = json_decode(file_get_contents($this->lobbiesPath), true);
        if (!is_array($data)) {
            return [];
        }

        $lobbies = [];
        foreach ($data as $lobbyData) {
            $lobbies[] = Lobby::fromArray($lobbyData);
        }

        return $lobbies;
    }

    /**
     * @param Lobby[] $lobbies
     */
    private function saveLobbies(array $lobbies): bool
    {
        $data = array_map(fn(Lobby $l) => $l->toArray(), $lobbies);
        return file_put_contents($this->lobbiesPath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
    }

    public function addLobby(Lobby $lobby): bool
    {
        $lobbies = $this->getLobbies();
        $lobbies[] = $lobby;
        return $this->saveLobbies($lobbies);
    }

    public function getLobbyById(string $id): ?Lobby
    {
        foreach ($this->getLobbies() as $lobby) {
            if ($lobby->getId() === $id) {
                return $lobby;
            }
        }
        return null;
    }

    public function updateLobby(Lobby $updatedLobby): bool
    {
        $lobbies = $this->getLobbies();
        $found = false;

        foreach ($lobbies as $key => $lobby) {
            if ($lobby->getId() === $updatedLobby->getId()) {
                $lobbies[$key] = $updatedLobby;
                $found = true;
                break;
            }
        }

        if (!$found) {
            return false;
        }

        return $this->saveLobbies($lobbies);
    }

    public function removeLobbyById(string $id): bool
    {
        $lobbies = $this->getLobbies();
        $initialCount = count($lobbies);

        $filtered = array_filter($lobbies, fn(Lobby $l) => $l->getId() !== $id);

        if (count($filtered) === $initialCount) {
            return false;
        }

        return $this->saveLobbies(array_values($filtered));
    }

    // ==========================================
    // 4. GESTION DES SESSIONS (SESSIONS)
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

    public function updateSession(Session $updatedSession): bool
    {
        $sessions = $this->getSessions();
        $found = false;

        foreach ($sessions as $key => $session) {
            if ($session->getId() === $updatedSession->getId()) {
                $sessions[$key] = $updatedSession;
                $found = true;
                break;
            }
        }

        if (!$found) {
            return false;
        }

        return $this->saveSessions($sessions);
    }

    public function removeSessionById(string $id): bool
    {
        $sessions = $this->getSessions();
        $initialCount = count($sessions);

        $filtered = array_filter($sessions, fn(Session $s) => $s->getId() !== $id);

        if (count($filtered) === $initialCount) {
            return false;
        }

        return $this->saveSessions(array_values($filtered));
    }

    // ==========================================
    // 5. GESTION DES SCORES (SCORES)
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

    public function saveOrUpdateScore(Score $newScore): bool
    {
        $scores = $this->getScores();
        $found = false;

        foreach ($scores as $key => $score) {
            if ($score->getSessionId() === $newScore->getSessionId() && 
                $score->getPlayerId() === $newScore->getPlayerId()) {
                $scores[$key] = $newScore;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $scores[] = $newScore;
        }

        return $this->saveScores($scores);
    }

    /**
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
     * @return Score[]
     */
    public function getScoresByLobbyId(string $lobbyId): array
    {
        return array_values(array_filter(
            $this->getScores(),
            fn(Score $s) => $s->getLobbyId() === $lobbyId
        ));
    }
}