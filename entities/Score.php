<?php
class Score
{
    private string $id;
    private string $sessionId;
    private ?string $lobbyId;   // null si session globale sans lobby
    private string $playerId;   // ID du joueur concerné
    private ?string $teamName;  // Nom de l'équipe temporaire (ex: "Les Tigres") ou null si solo
    private float $rawScore;    // Points bruts marqués (ex: 1500 pts, 12 kills, etc.)
    private int $rank;          // Classement / Rang dans le lobby ou la session (1er, 2e...)

    public function __construct(
        string $sessionId,
        string $playerId,
        float $rawScore = 0.0,
        int $rank = 0,
        ?string $lobbyId = null,
        ?string $teamName = null,
        ?string $id = null
    ) {
        $this->id = $id ?? uniqid('sco_', true);
        $this->sessionId = $sessionId;
        $this->playerId = $playerId;
        $this->rawScore = $rawScore;
        $this->rank = $rank;
        $this->lobbyId = $lobbyId;
        $this->teamName = $teamName;
    }

    // Getters & Setters ...
    public function getId(): string { return $this->id; }
    public function getSessionId(): string { return $this->sessionId; }
    public function getLobbyId(): ?string { return $this->lobbyId; }
    public function getPlayerId(): string { return $this->playerId; }
    public function getTeamName(): ?string { return $this->teamName; }
    public function getRawScore(): float { return $this->rawScore; }
    public function getRank(): int { return $this->rank; }

    public function toArray(): array
    {
        return [
            'id'        => $this->id,
            'sessionId' => $this->sessionId,
            'lobbyId'   => $this->lobbyId,
            'playerId'  => $this->playerId,
            'teamName'  => $this->teamName,
            'rawScore'  => $this->rawScore,
            'rank'      => $this->rank
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['sessionId'] ?? '',
            $data['playerId'] ?? '',
            (float)($data['rawScore'] ?? 0),
            (int)($data['rank'] ?? 0),
            $data['lobbyId'] ?? null,
            $data['teamName'] ?? null,
            $data['id'] ?? null
        );
    }
}