<?php

namespace Forum41\Models;

use Forum41\Domain\Post;
use Forum41\Domain\Topic;
use Forum41\Exceptions\DbException;
use Forum41\Exceptions\NotFoundException;

class TopicModel extends BaseModel {

    public function getById(int $id) {
        return $this->get_by_id($id, "topics", self::CLASS_TOPIC);
    }

    public function getAll(array $sort = ["id" => "ASC"]): array {
        return $this->get_all($sort, "topics", self::CLASS_TOPIC);
    }

    public function getAllFromLastVisit(string $lastVisitDT, int $limit = 0): array {
        $sql = "SELECT * ";
        $sql .= "FROM topics ";
        $sql .= "WHERE startedDT <= :lastVisitDT ";
        if ($limit > 0) {
            $sql .= "LIMIT :limit ";
        }

        $sth = $this->db->prepare($sql);
        $sth->bindValue("lastVisitDT", $lastVisitDT);
        $sth->bindValue("limit", $limit);
        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_CLASS, self::CLASS_TOPIC);
    }

    public function getAllByForumid(int $id, array $sort = ["latestPostDT" => "DESC"]): array {
        $sql = "SELECT * ";
        $sql .= "FROM topics ";
        $sql .= "WHERE forumId=:id ";
        $sql .= $this->orderByStatement($sort);

        $sth = $this->db->prepare($sql);
        $sth->bindValue("id", $id);
        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_CLASS, self::CLASS_TOPIC);
    }

    // ************************************************************************** //

    public function getFirstPost(int $topicId): Post  {
        $sql = "SELECT * ";
        $sql .= "FROM posts ";
        $sql .= "WHERE topicId=:topicId ";
        $sql .= "AND postedDT=( ";
        $sql .= "   SELECT MIN(postedDT) ";
        $sql .= "   FROM posts ";
        $sql .= "   WHERE topicId=:topicId ";
        $sql .= ") ";

        $sth = $this->db->prepare($sql);
        $sth->bindValue("topicId", $topicId);
        $sth->execute();

        $rows = $sth->fetchAll(PDO::FETCH_CLASS, self::CLASS_POST);

        if (empty($rows)) {
            throw new NotFoundException();
        }

        return $rows[0];
    }

    public function getLastPost(int $topicId): Tables\Post {
        $sql = "SELECT * ";
        $sql .= "FROM posts ";
        $sql .= "WHERE topicId=:topicId ";
        $sql .= "AND postedDT=( ";
        $sql .= "   SELECT MAX(postedDT) ";
        $sql .= "   FROM posts ";
        $sql .= "   WHERE topicId=:topicId ";
        $sql .= ") ";

        $sth = $this->db->prepare($sql);
        $sth->bindValue("topicId", $topicId);
        $sth->execute();

        $rows = $sth->fetchAll(PDO::FETCH_CLASS, self::CLASS_TOPIC);

        if (empty($rows)) {
            throw new NotFoundException();
        }

        return $rows[0];
    }

    public function getFirstPoster(int $topicId): Tables\User {
        $firstPost = $this->getFirstPost($topicId);
        return $this->getById($firstPost->getUserId(), "users");
    }

    public function getLastPoster($topicId) {
        $lastPost = $this->getLastPost($topicId);
        return $this->getById($lastPost->getUserId(), "users");
    }

    // ************************************************************************** //

    public function createNewTopic(string $title, string $content, int $forumId, int $userId): int {
        $sql = "INSERT INTO topics (title, forumId) VALUES (";
        $sql .= ":title, :forumId";
        $sql .= ")";

        $sth = $this->db->prepare($sql);
        $sth->bindValue("title", $title);
        $sth->bindValue("forumId", $forumId);

        if (!$sth->execute()) {
            throw new DbException($sth->errorInfo()[2]);
        }

        $topicId = $this->db->lastInsertId();
        $post = $postM->insert($topicId, $userId, $content);
    }

    public function countPosts($topicId) {
        $sql = "SELECT COUNT(*) AS count ";
        $sql .= "FROM posts ";
        $sql .= "WHERE topicId=:topicId ";

        $sth = $this->db->prepare($sql);
        $sth->bindValue("topicId", $topicId);
        $sth->execute();

        return $sth->fetch();
    }

    // ************************************************************************** //

    public function didUserReadThis(int $userId, int $topicId): bool {
        $flag = $this->isReadingMarked($userId, $topicId);
        if ($topic = $this->getById($topicId, "topics")) {
            // Na svakih gc_days se cisti tabela readTopics, pa bi ispalo kao da korisnik
            // nije procitao temu. Zato ja kazem da je svaka tema starija od 30 dana procitana.
            $flag = $flag || dateDifference($topic["latestPostDT"]) > Config::get("db")["gc_days"];
        }
        return $flag;
    }

    public function isReadingMarked($userId, $topicId): bool {
        $sql = "SELECT id ";
        $sql .= "FROM readTopics ";
        $sql .= "WHERE userId=:userId ";
        $sql .= "    AND topicId=:topicId ";

        $sth = $this->db->prepare($sql);
        $sth->bindValue("userId", $userId);
        $sth->bindValue("topicId", $topicId);
        $sth->execute();

        $rows = $sth->fetchAll();

        return !empty($rows);
    }

    public function markTopicAsRead($userId, $topicId): void {
        $timeDT = getDatetimeForMysql();

        if (!isReadingMarked($userId, $topicId)) {
            $sql = "INSERT INTO readTopics (userId, topicId, timeDT) VALUES (";
            $sql .= ":userId, :topicId, :timeDT";
            $sql .= ")";

            $sth = $this->db->prepare($sql);
            $sth->bindValue("userId", $userId);
            $sth->bindValue("topicId", $topicId);
            $sth->bindValue("timeDT", $timeDT);

            if (!$sth->execute()) {
                throw new DbException($sth->errorInfo()[2]);
            }
        }
    }

}
