<?php
class Event {
    private int $id;
    private string $title;
    private string $description;
    private int $playerCount;
    private string $startTime;
    private string $endDate;
    private int $createdBy;
    private string $status;

    public function __construct(int $id, string $title, string $description, int $playerCount, string $startTime, string $endDate, int $createdBy, string $status) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->playerCount = $playerCount;
        $this->startTime = $startTime;
        $this->endDate = $endDate;
        $this->createdBy = $createdBy;
        $this->status = $status;
    }

    // Getters
    public function getId(): int { return $this->id; }
    public function getTitle(): string { return $this->title; }
    public function getDescription(): string { return $this->description; }
    public function getPlayerCount(): int { return $this->playerCount; }
    public function getStartTime(): string { return $this->startTime; }
    public function getEndDate(): string { return $this->endDate; }
    public function getCreatedBy(): int { return $this->createdBy; }
    public function getStatus(): string { return $this->status; }

    // Setter (si besoin de modifier un champ)
    public function setStatus(string $status): void { $this->status = $status; }
}