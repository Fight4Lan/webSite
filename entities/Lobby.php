<?php
class Lobby
{
    private string $id;
    private string $sessionId;  // L'ID de la session parente
    private string $name;       // Ex: "Lobby A", "Poule 1"
    private array $playerIds;   // Tableau d'IDs de joueurs [ 'plr_1', 'plr_2', ... ]

    public function __construct(string $sessionId, string $name, array $playerIds = [], ?string $id = null)
    {
        $this->id = $id ?? uniqid('lob_', true);
        $this->sessionId = $sessionId;
        $this->name = $name;
        $this->playerIds = $playerIds;
    }

    // Getters & Setters ...
    public function getId(): string { return $this->id; }
    public function getSessionId(): string { return $this->sessionId; }
    public function getName(): string { return $this->name; }
    public function getPlayerIds(): array { return $this->playerIds; }

    public function addPlayerId(string $playerId): void
    {
        if (!in_array($playerId, $this->playerIds)) {
            $this->playerIds[] = $playerId;
        }
    }

    public function toArray(): array
    {
        return [
            'id'        => $this->id,
            'sessionId' => $this->sessionId,
            'name'      => $this->name,
            'playerIds' => $this->playerIds
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['sessionId'] ?? '',
            $data['name'] ?? '',
            $data['playerIds'] ?? [],
            $data['id'] ?? null
        );
    }
}