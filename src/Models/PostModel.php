<?php

namespace Forum41\Models;

use Forum41\Domain\Post;
use Forum41\Exceptions\DbException;
use Forum41\Exceptions\NotFoundException;

class PostModel extends BaseModel {

    public function getById(int $id) {
        return $this->get_by_id($id, "posts", self::CLASS_POST);
    }

    public function getAll(array $sort = ["id" => "ASC"]): array {
        return $this->get_all($sort, "posts", self::CLASS_POST);
    }

    public function getAllByTopicId(int $topicId, array $sort = ["postedDT" => "ASC"]): array {
        $sql = "SELECT * ";
        $sql .= "FROM posts ";
        $sql .= "WHERE topicId=:topicId ";
        $sql .= self::orderByStatement($sort);

        $sth = $this->db->prepare($sql);
        $sth->bindValue("topicId", $topicId);
        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_CLASS, self::CLASS_POST);
    }

    public function getAllFromLastVisit(string $lastVisitDT, int $limit = 0): array {
        $sql = "SELECT * ";
        $sql .= "FROM posts ";
        $sql .= "WHERE postedDT <= :lastVisitDT ";
        if ($limit > 0) {
            $sql .= "LIMIT :limit ";
        }

        $sth = $this->db->prepare($sql);
        $sth->bindValue("lastVisitDT", $lastVisitDT);
        if ($limit > 0) {
            $sth->bindValue("limit", $limit);
        }
        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_CLASS, self::CLASS_POST);
    }

    public function getLastPostByForumId(int $forumId): Post {
        $sql = "SELECT id, latestPostDT ";
        $sql .= "FROM topics ";
        $sql .= "WHERE forumId=:forumId ";
        $sql .= "ORDER BY latestPostDT DESC ";

        $sth = $this->db->prepare($sql);
        $sth->bindValue("forumId", $forumId);
        $sth->execute();

        $lastUpdatedTopic = $sth->fetch();

        if (empty($lastUpdatedTopic)) {
            throw new NotFoundException();
        }

        $lastPoster = $topicM->getLastPoster($lastUpdatedTopic["id"]);

        return [
            "user" => $lastPoster["user"],
            "date" => convertMysqlDatetimeToPhpDate($lastlyUpdatedTopic["latestPostDT"]),
            "time" => convertMysqlDatetimeToPhpTime($lastlyUpdatedTopic["latestPostDT"])
        ];
    }

    // ************************************************************************** //

    public function insert($topicId, $userId, $content): int {
        $postedDT = getDatetimeForMysql();

        $sql = "INSERT INTO posts (content, postedDT, topicId, userId) VALUES (";
        $sql .= ":content, :postedDT, :topicId, :userId";
        $sql .= ")";

        $sth = $this->db->prepare($sql);
        $sth->bindValue("content", $content);
        $sth->bindValue("postedDT", $postedDT);
        $sth->bindValue("topicId", $topicId);
        $sth->bindValue("userId", $userId);

        if (!$sth->execute()) {
            throw new DbException($sth->errorInfo()[2]);
        }
    }

    public function append(int $postId, string $content): void {
        $appendixDate = getDatetimeForMysql();

        $appendix = "\n### ===== DOPUNA {$appendixDate} ===== \n";
        $appendix .= $content;

        $sql = "UPDATE posts SET ";
        $sql .= "content=CONCAT(content, :appendix) ";
        $sql .= "WHERE id=:postId ";

        $sth = $this->db->prepare($sql);
        $sth->bindValue("appendix", $appendix);
        $sth->bindValue("postId", $postId);

        if (!$sth->execute()) {
            throw new DbException($sth->errorInfo()[2]);
        }
    }

}
