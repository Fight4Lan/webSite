<?php

class Lobby
{
    private string $id;
    private string $name;
    private array $playerIds; // IDs des joueurs solos dans ce lobby
    private array $teamIds;   // IDs des équipes dans ce lobby

    public function __construct(
        string $name, 
        array $playerIds = [], 
        array $teamIds = [], 
        ?string $id = null
    ) {
        $this->id = $id ?? uniqid('lob_', true);
        $this->name = $name;
        $this->playerIds = $playerIds;
        $this->teamIds = $teamIds;
    }

    public function getId(): string { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getPlayerIds(): array { return $this->playerIds; }
    public function getTeamIds(): array { return $this->teamIds; }

    public function setName(string $name): void { $this->name = $name; }
    public function setPlayerIds(array $playerIds): void { $this->playerIds = $playerIds; }
    public function setTeamIds(array $teamIds): void { $this->teamIds = $teamIds; }

    /**
     * Ajoute une équipe au lobby si elle n'y est pas déjà
     */
    public function addTeamId(string $teamId): void
    {
        if (!in_array($teamId, $this->teamIds, true)) {
            $this->teamIds[] = $teamId;
        }
    }

    /**
     * Ajoute un joueur au lobby s'il n'y est pas déjà
     */
    public function addPlayerId(string $playerId): void
    {
        if (!in_array($playerId, $this->playerIds, true)) {
            $this->playerIds[] = $playerId;
        }
    }

    public function toArray(): array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'playerIds' => $this->playerIds,
            'teamIds'   => $this->teamIds
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['name'] ?? '',
            $data['playerIds'] ?? [],
            $data['teamIds'] ?? [],
            $data['id'] ?? null
        );
    }
}