<?php

class Player
{
    private string $id;
    private string $pseudo;
    private string $dss;
    private string $ow;
    private string $apex;
    private string $chess;
    private string $brawlhalla;

    /**
     * @var array<string, int|float> Map associant un ID de session à un classement
     */
    private array $classements;

    public function __construct(
        string $pseudo = '',
        string $dss = '',
        string $ow = '',
        string $apex = '',
        string $chess = '',
        string $brawlhalla = '',
        array $classements = [],
        ?string $id = null
    ) {
        $this->id = $id ?? uniqid('plr_', true);
        $this->pseudo = $pseudo;
        $this->dss = $dss;
        $this->ow = $ow;
        $this->apex = $apex;
        $this->chess = $chess;
        $this->brawlhalla = $brawlhalla;
        $this->classements = $classements;
    }

    // ==========================================
    // GETTERS & SETTERS
    // ==========================================

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;
        return $this;
    }

    public function getDss(): string
    {
        return $this->dss;
    }

    public function setDss(string $dss): self
    {
        $this->dss = $dss;
        return $this;
    }

    public function getOw(): string
    {
        return $this->ow;
    }

    public function setOw(string $ow): self
    {
        $this->ow = $ow;
        return $this;
    }

    public function getApex(): string
    {
        return $this->apex;
    }

    public function setApex(string $apex): self
    {
        $this->apex = $apex;
        return $this;
    }

    public function getChess(): string
    {
        return $this->chess;
    }

    public function setChess(string $chess): self
    {
        $this->chess = $chess;
        return $this;
    }

    public function getBrawlhalla(): string
    {
        return $this->brawlhalla;
    }

    public function setBrawlhalla(string $brawlhalla): self
    {
        $this->brawlhalla = $brawlhalla;
        return $this;
    }

    // ==========================================
    // GESTION DU CLASSEMENT (MAP ID => NOMBRES)
    // ==========================================

    public function getClassements(): array
    {
        return $this->classements;
    }

    public function setClassements(array $classements): self
    {
        $this->classements = $classements;
        return $this;
    }

    /**
     * Définit ou met à jour le classement pour un ID donné
     */
    public function setClassementPourId(string $keyId, float|int $valeur): self
    {
        $this->classements[$keyId] = $valeur;
        return $this;
    }

    /**
     * Récupère le classement associé à un ID (ou null si inexistant)
     */
    public function getClassementParId(string $keyId): float|int|null
    {
        return $this->classements[$keyId] ?? null;
    }

    // ==========================================
    // MÉTHODES DE CONVERSION (POUR FICHIERS JSON)
    // ==========================================

    /**
     * Convertit l'objet Player en tableau associatif
     */
    public function toArray(): array
    {
        return [
            'id'          => $this->id,
            'pseudo'      => $this->pseudo,
            'DSS'         => $this->dss,
            'OW'          => $this->ow,
            'Apex'        => $this->apex,
            'Chess'       => $this->chess,
            'Brawmhalla'  => $this->brawlhalla,
            'classements' => $this->classements
        ];
    }

    /**
     * Crée une instance de Player depuis un tableau associatif (ex: json_decode)
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['pseudo'] ?? '',
            $data['DSS'] ?? '',
            $data['OW'] ?? '',
            $data['Apex'] ?? '',
            $data['Chess'] ?? '',
            $data['Brawmhalla'] ?? $data['Brawlhalla'] ?? '',
            $data['classements'] ?? [],
            $data['id'] ?? null
        );
    }
}