<?php

namespace Forum41\Models;

use Forum41\Domain\User;
use Forum41\Exceptions\DbException;
use Forum41\Exceptions\NotFoundException;

class UserModel extends BaseModel {

    public function getById(int $id) {
        return $this->get_by_id($id, "users", self::CLASS_USER);
    }

    public function getAll(array $sort = ["id" => "ASC"]): array {
        return $this->get_all($sort, "users", self::CLASS_USER);
    }

    public function getByUsername(string $username, string $password = ""): User {
        $password = hashPassword($password);

        $sql = "SELECT * ";
        $sql .= "FROM users ";
        $sql .= "WHERE username=:username ";
        if (!empty($password)) {
            $sql .= "   AND password=:password ";
        }

        $sth = $this->db->prepare($sql);
        $sth->bindValue("username", $username);
        if (!empty($password)) {
            $sth->bindValue("password", $password);
        }
        $sth->execute();

        $users = $sth->fetchAll(PDO::FETCH_CLASS, self::CLASS_USER);

        if (empty($user)) {
            throw new NotFoundException();
        }

        return $users[0];
    }

    public function getByEmail(string $email, string $token = ""): User {
        $sql = "SELECT * ";
        $sql .= "FROM users ";
        $sql .= "WHERE email=:email ";
        if (!empty($token)) {
            $sql .= "AND token=:token";
        }

        $sth = $this->db->prepare($sql);
        $sth->bindValue("email", $email);
        if (!empty($token)) {
            $sth->bindValue("token", $token);
        }
        $sth->execute();

        $users = $sth->fetchAll(PDO::FETCH_CLASS, self::CLASS_USER);

        if (empty($users)) {
            throw new NotFoundException();
        }

        return $users[0];
    }

    public function getNewestUser(): User {
        $sql = "SELECT * ";
        $sql .= "FROM users ";
        $sql .= "ORDER BY joinedDT DESC ";

        $sth = $this->db->prepare($sql);
        $sth->execute();

        $users = $sth->fetchAll(PDO::FETCH_CLASS, self::CLASS_USER);

        if (empty($users)) {
            throw new NotFoundException();
        }

        return $users[0];
    }

    public function getAllOnline(): array {
        $sql = "SELECT username ";
        $sql .= "FROM users ";
        $sql .= "WHERE loggedIn='1' ";

        $rows = $this->db->query($sql);
        return $rows->fetchAll();
    }

    // ************************************************************************** //

    public function setNewPassword(int $id, string $password): void {
        $password = hashPassword($password);

        $sql = "UPDATE users ";
        $sql .= "SET password=:password ";
        $sql .= "WHERE id=:id ";

        $sth = $this->db->prepare($sql);
        $sth->bindValue("id", $id);
        $sth->bindValue("password", $password);

        if (!$sth->execute()) {
            throw new DbException($sth->errorInfo()[2]);
        }
    }

    public function setNewInfo(array $info): void {
        $sql = "UPDATE users ";
        $sql .= "SET sex=:sex, ";
        $sql .= "    birthdayD=:birthdayD, ";
        $sql .= "    birthplace=:birthplace, ";
        $sql .= "    residence=:residence, ";
        $sql .= "    job=:job, ";
        $sql .= "    avatar=:avatar ";
        $sql .= "WHERE id=:id ";

        $sth = $this->db->prepare($sql);

        if (!$sth->execute($info)) {
            throw new DbException($sth->errorInfo()[2]);
        }
    }

    public function setNewEmail(int $id, string $email): string {
        $token = generateToken();

        $sql = "UPDATE users ";
        $sql .= "SET email=:email, ";
        $sql .= "    confirmed='0', ";
        $sql .= "    token=:token ";
        $sql .= "WHERE id=:id ";

        $sth = $this->db->prepare($sql);
        $sth->bindValue("id", $id);
        $sth->bindValue("token", $token);
        $sth->bindValue("email", $email);

        if (!$sth->execute($info)) {
            throw new DbException($sth->errorInfo()[2]);
        }

        return $token;
    }

    // ************************************************************************** //

    public function login(string $username, string $password): User {
        $user = getByUsername($username, $password);
        $now = getDatetimeForMysql();

        $sql = "UPDATE users ";
        $sql .= "SET lastVisitDT=:now', ";
        $sql .= "    lastActivityDT=:now', ";
        $sql .= "    loggedIn='1' ";
        $sql .= "WHERE id=:id ";

        $sth = $this->db->prepare($sql);
        $sth->bindValue("now", $now);
        $sth->bindValue("id", $id);

        if (!$sth->execute()) {
            throw new DbException($sth->errorInfo()[2]);
        }

        return $user; // STA CE MI OVO??
    }

    public function register(string $username, string $email, string $password): void {
        $password = hashPassword($password);
        $joinedDT = getDatetimeForMysql();
        $token = generateToken();

        $sql = "INSERT INTO users (id, username, password, email, joinedDT, confirmed, token) VALUES (";
        $sql .= "   NULL, :username, :password, :email, :joinedDT, '0', :token'";
        $sql .= ")";

        $sth = $this->db->prepare($sql);
        $sth->bindValue("username", $username);
        $sth->bindValue("password", $password);
        $sth->bindValue("email", $email);
        $sth->bindValue("joinedDT", $joinedDT);
        $sth->bindValue("confirmed", $confirmed);
        $sth->bindValue("token", $token);

        if (!$sth->execute()) {
            throw new DbException($sth->errorInfo()[2]);
        }
    }

    public function doesPasswordMatchesEmail(string $email, string $password): bool {
        $password = hashPassword($password);

        $sql = "SELECT id ";
        $sql .= "FROM users ";
        $sql .= "WHERE email=:email ";
        $sql .= "   AND password=:password ";

        $sth = $this->db->prepare($sql);
        $sth->bindValue("email", $email);
        $sth->bindValue("password", $password);

        return $sth->execute(); // DA LI OVO RADI?
    }

    public function confirmEmail(string $email, string $token): bool {
        $user = getByEmail($email, $token);

        $sql = "UPDATE users ";
        $sql .= "SET confirmed='1', ";
        $sql .= "    token='' ";
        $sql .= "WHERE id=:id ";

        $sth = $this->db->prepare($sql);
        $sth->bindValue("id", $user["id"]);

        return $sth->execute();
    }

    public function isEmailTaken(string $email): bool {
        try {
            getByEmail($email);
            return true;
        } catch (NotFoundException $e) {
            return false;
        }
    }

    public function isUsernameTaken(string $username): bool {
        try {
            getByUsername($username);
            return true;
        } catch (NotFoundException $e) {
            return false;
        }
    }


    // ************************************************************************** //

    public function getOnlineStatus(int $id): string {
        $sql = "SELECT loggedIn ";
        $sql .= "FROM users ";
        $sql .= "WHERE id=:id ";

        $sth = $this->db->prepare($sql);
        $sth->bindValue("id", $id);
        $sth->execute();

        $row = $sth->fetch();

        if (empty($row)) {
            throw new NotFoundException();
        }

        return $row["loggedIn"] === "1" ? "online" : "offline";
    }

    public function logout(int $id): void {
        $sql = "UPDATE users ";
        $sql .= "SET loggedIn='0' ";
        $sql .= "WHERE id=:id ";

        $sth = $this->db->prepare($sql);
        $sth->bindValue("id", $id);
        $sth->bindValue("lastActivityDT", $lastActivityDT);

        if (!$sth->execute()) {
            throw new DbException($sth->errorInfo()[2]);
        }
    }

    public function updateLastActivity(int $id): void {
        $lastActivityDT = getDatetimeForMysql();

        $sql = "UPDATE users ";
        $sql .= "SET lastActivityDT=:lastActivityDT ";
        $sql .= "WHERE id=:id ";

        $sth = $this->db->prepare($sql);
        $sth->bindValue("id", $id);
        $sth->bindValue("lastActivityDT", $lastActivityDT);

        if (!$sth->execute()) {
            throw new DbException($sth->errorInfo()[2]);
        }
    }

    public function updateInvisibility(int $id, int $invisible): void {
        $sql = "UPDATE users ";
        $sql .= "SET invisible=:invisible ";
        $sql .= "WHERE id=:id ";

        $sth = $this->db->prepare($sql);
        $sth->bindValue("id", $id);
        $sth->bindValue("invisible", $invisible);

        if (!$sth->execute()) {
            throw new DbException($sth->errorInfo()[2]);
        }
    }

    public function countPosts(int $id): int {
        $sql = "SELECT COUNT(*) as count ";
        $sql .= "FROM posts ";
        $sql .= "WHERE userId=:id ";

        $sth = $this->db->prepare($sql);
        $sth->bindValue("id", $id);
        $sth->execute();

        $row = $sth->fetch();

        if (empty($row)) {
            throw new NotFoundException();
        }

        return $row["count"];
    }


    // public function qGetBirthdays() {
    //     // ne treba ceo datum da se poklopi, nego samo dan i mesec...
    // }

}
