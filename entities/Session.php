<?php
require_once __DIR__ . '/Game.php';
require_once __DIR__ . '/Lobby.php';

class Session
{
    private string $id;
    private string $name;
    private Game $game;
    private string $description;
    private bool $isTeam;
    private bool $hasLobbies;

    /**
     * @var Lobby[]
     */
    private array $lobbies; // <--- Attribut pour stocker les lobbies

    public function __construct(
        string $name,
        Game $game,
        string $description = '',
        bool $isTeam = false,
        bool $hasLobbies = false,
        array $lobbies = [], // <--- Ajout dans le constructeur
        ?string $id = null
    ) {
        $this->id = $id ?? uniqid('sess_', true);
        $this->name = $name;
        $this->game = $game;
        $this->description = $description;
        $this->isTeam = $isTeam;
        $this->hasLobbies = $hasLobbies;
        $this->lobbies = $lobbies;
    }

    // ==========================================
    // GETTERS & SETTERS
    // ==========================================

    public function getId(): string { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getGame(): Game { return $this->game; }
    public function getDescription(): string { return $this->description; }
    public function isTeam(): bool { return $this->isTeam; }
    public function hasLobbies(): bool { return $this->hasLobbies; }

    /**
     * @return Lobby[]
     */
    public function getLobbies(): array { return $this->lobbies; }

    /**
     * Ajoute un lobby à la session
     */
    public function addLobby(Lobby $lobby): self
    {
        $this->lobbies[] = $lobby;
        return $this;
    }

    // ==========================================
    // MÉTHODES DE CONVERSION (JSON)
    // ==========================================

    public function toArray(): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'game'        => $this->game->value,
            'description' => $this->description,
            'isTeam'      => $this->isTeam,
            'hasLobbies'  => $this->hasLobbies,
            'lobbies'     => array_map(fn(Lobby $l) => $l->toArray(), $this->lobbies) // Export des lobbies en tableau
        ];
    }

    public static function fromArray(array $data): self
    {
        $lobbies = [];
        if (isset($data['lobbies']) && is_array($data['lobbies'])) {
            foreach ($data['lobbies'] as $lobbyData) {
                $lobbies[] = Lobby::fromArray($lobbyData);
            }
        }

        return new self(
            $data['name'] ?? '',
            Game::from($data['game']),
            $data['description'] ?? '',
            $data['isTeam'] ?? false,
            $data['hasLobbies'] ?? false,
            $lobbies,
            $data['id'] ?? null
        );
    }
}