<?php

namespace Forum41\Domain;

class Section extends Base {
    private $id;
    private $title;
    private $description;
    private $position;
    private $visible;

    public function __construct() {
        settype($this->id, "int");
        settype($this->title, "string");
        settype($this->description, "string");
        settype($this->position, "int");
        settype($this->visible, "bool");
    }

    public function getId(): int {
        return $this->id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getPosition(): int {
        return $this->position;
    }

    public function getVisible(): bool {
        return $this->visible;
    }
}
