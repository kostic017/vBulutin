<?php

namespace Forum41\Domain;

class User extends Base {
    private $id;
    private $username;
    private $password;
    private $email;
    private $token;
    private $confirmed;
    private $loggedIn;
    private $invisible;
    private $joinedDT;
    private $lastVisitDT;
    private $lastActivityDT;
    private $sex;
    private $job;
    private $avatar;
    private $birthdayD;
    private $birthplace;
    private $residence;

    public function __construct() {
        settype($this->id, "int");
        settype($this->username, "string");
        settype($this->password, "string");
        settype($this->email, "string");
        settype($this->token, "string");
        settype($this->confirmed, "bool");
        settype($this->loggedIn, "bool");
        settype($this->invisible, "bool");
        settype($this->joinedDT, "string");
        settype($this->lastVisitDT, "string");
        settype($this->lastActivityDT, "string");
        settype($this->sex, "string");
        settype($this->job, "string");
        settype($this->avatar, "string");
        settype($this->birthdayD, "string");
        settype($this->birthplace, "string");
        settype($this->residence, "string");
    }

    public function getId(): int {
        return $this->id;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getConfirmed(): bool {
        return $this->confirmed;
    }

    public function getLoggedIn(): bool {
        return $this->loggedIn;
    }

    public function getInvisible(): bool {
        return $this->invisible;
    }

    public function getJoinedDT(): string {
        return $this->joinedDT;
    }

    public function getLastVisitDT(): string {
        return $this->lastVisitDT;
    }

    public function getLastActivityDT(): string {
        return $this->lastActivityDT;
    }

    public function getSex(): string {
        return $this->sex;
    }

    public function getJob(): string {
        return $this->job;
    }

    public function getAvatar(): string {
        return $this->avatar;
    }

    public function getBirthdayD(): string {
        return $this->birthdayD;
    }

    public function getBirthplace(): string {
        return $this->birthplace;
    }

    public function getResidence(): string {
        return $this->residence;
    }

}
