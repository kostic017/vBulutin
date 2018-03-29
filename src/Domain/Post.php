<?php

namespace Forum41\Domain;

class Post extends Base {
    private $id;
    private $content;
    private $topicId;
    private $userId;
    private $visible;

    public function __construct() {
        settype($this->id, "int");
        settype($this->content, "string");
        settype($this->topicId, "int");
        settype($this->userId, "int");
        settype($this->visible, "bool");
    }

    public function getId(): int {
        return $this->id;
    }

    public function getContent(): string {
        return $this->content;
    }

    public function getTopicId(): int {
        return $this->topicId;
    }

    public function getUserId(): int {
        return $this->userId;
    }
}
