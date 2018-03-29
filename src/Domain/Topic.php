<?php

namespace Forum41\Domain;

class Topic extends Base {
    private $id;
    private $title;
    private $description;
    private $forumId;
    private $visible;

    public function __construct() {
        settype($this->id, "int");
        settype($this->title, "string");
        settype($this->description, "string");
        settype($this->forumId, "int");
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

    public function getForumId(): int {
        return $this->forumId;
    }

    public function getVisible(): bool {
        return $this->visible;
    }
}
