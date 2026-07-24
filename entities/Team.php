<?php

class Team
{
    private string $id;
    private string $name;
    private array $playerIds; // Ex: ['plr_65a...', 'plr_65b...']

    public function __construct(string $name, array $playerIds = [], ?string $id = null)
    {
        $this->id = $id ?? uniqid('team_', true);
        $this->name = $name;
        $this->playerIds = $playerIds;
    }

    public function getId(): string { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getPlayerIds(): array { return $this->playerIds; }

    public function setName(string $name): void { $this->name = $name; }
    public function setPlayerIds(array $playerIds): void { $this->playerIds = $playerIds; }

    public function addPlayerId(string $playerId): void
    {
        if (!in_array($playerId, $this->playerIds)) {
            $this->playerIds[] = $playerId;
        }
    }

    public function removePlayerId(string $playerId): void
    {
        $this->playerIds = array_values(array_filter($this->playerIds, fn($id) => $id !== $playerId));
    }

    public function toArray(): array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'playerIds' => $this->playerIds
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['name'] ?? '',
            $data['playerIds'] ?? [],
            $data['id'] ?? null
        );
    }
}