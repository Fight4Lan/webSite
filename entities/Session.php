<?php
require_once __DIR__ . '/Game.php';

class Session
{
    private string $id;
    private string $name;
    private ?Game $game;
    private string $description;
    private bool $isTeam;
    private ?string $parentId; // ID de la Session Mère / Phase
    private ?string $lobbyId;  // ID du Lobby rattaché

    public function __construct(
        string $name,
        ?Game $game = null,
        string $description = '',
        bool $isTeam = false,
        ?string $parentId = null,
        ?string $lobbyId = null,
        ?string $id = null
    ) {
        $this->id = $id ?? uniqid('sess_', true);
        $this->name = $name;
        $this->game = $game;
        $this->description = $description;
        $this->isTeam = $isTeam;
        $this->parentId = $parentId;
        $this->lobbyId = $lobbyId;
    }

    public function getId(): string { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getGame(): ?Game { return $this->game; }
    public function getDescription(): string { return $this->description; }
    public function isTeam(): bool { return $this->isTeam; }
    public function getParentId(): ?string { return $this->parentId; }
    public function getLobbyId(): ?string { return $this->lobbyId; }
    public function isPhase(): bool { return $this->game === null; }

    public function toArray(): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'game'        => $this->game ? $this->game->value : null,
            'description' => $this->description,
            'isTeam'      => $this->isTeam,
            'parentId'    => $this->parentId,
            'lobbyId'     => $this->lobbyId
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['name'] ?? '',
            !empty($data['game']) ? Game::from($data['game']) : null,
            $data['description'] ?? '',
            $data['isTeam'] ?? false,
            $data['parentId'] ?? null,
            $data['lobbyId'] ?? null,
            $data['id'] ?? null
        );
    }
}