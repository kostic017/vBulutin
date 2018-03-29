<?php

namespace Forum41\Domain;

class Forum extends Base {
    private $id;
    private $title;
    private $description;
    private $position;
    private $visible;
    private $parentId;
    private $sectionId;

    public function __construct() {
        settype($this->id, "int");
        settype($this->title, "string");
        settype($this->description, "string");
        settype($this->position, "int");
        settype($this->visible, "bool");
        settype($this->parentId, "int");
        settype($this->sectionId, "int");
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

    public function getParentId(): int {
        return $this->parentId;
    }

    public function getSectionId(): int {
        return $this->sectionId;
    }
}
