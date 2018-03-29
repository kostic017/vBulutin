<?php

namespace Forum41\Models;

use PDO;
use Forum41\Domain\Forum;
use Forum41\Exceptions\DbException;
use Forum41\Exceptions\NotFoundException;

class ForumModel extends BaseModel {

    public function getById(int $id) {
        return $this->get_by_id($id, "forums", self::CLASS_FORUM);
    }

    public function getAll(array $sort = ["id" => "ASC"]): array {
        return $this->get_all($sort, "forums", self::CLASS_FORUM);
    }

    public function getAllBySectionId(int $sectionId, bool $rootOnly = false, array $sort = ["id" => "ASC"]): array {
        $sql = "SELECT * ";
        $sql .= "FROM forums ";
        $sql .= "WHERE sectionId=:sectionId ";
        if ($rootOnly) {
            $sql .= "AND parentId IS NULL ";
        }
        $sql .= self::orderByStatement($sort);

        $sth = $this->db->prepare($sql);
        $sth->bindValue("sectionId", $sectionId);
        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_CLASS, self::CLASS_FORUM);
    }

    public function getAllByParentId(int $parentId, array $sort = ["id" => "ASC"]): array {
        $sql = "SELECT * ";
        $sql .= "FROM forums ";
        $sql .= "WHERE parentId=:parentId ";
        $sql .= self::orderByStatement($sort);

        $sth = $this->db->prepare($sql);
        $sth->bindValue("parentId", $parentId);
        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_CLASS, self::CLASS_FORUM);
    }

    // ************************************************************************** //

    private function calculateNextPosition($parentId, $sectionId): int {
        $sql = "SELECT MAX(position) as position ";
        $sql .= "FROM forums ";
        if ($parentId === "NULL") {
            $sql .= "WHERE sectionId=:sectionId AND parentId IS NULL";
        } else {
            $sql .= "WHERE parentId=:parentId ";
        }

        $sth = $this->db->prepare($sql);

        if ($parentId === "NULL") {
            $sth->bindValue("sectionId", $sectionId);
        } else {
            $sth->bindValue("parentId", $parentId);
        }

        $sth->execute();

        $calcPos = $sth->fetch();
        return $calcPos["position"] + 1;
    }

    public function insert(array $data): int {
        if ($data["parentId"] !== "") {
            // ako ima roditelja onda pripada istoj sekciji kao on
            if ($parent = $this->getById($data["parentId"])) {
                $data["sectionId"] = $parent->getSectionId();
            }
        } else {
            $data["parentId"] = "NULL";
        }

        $data["position"] = $this->calculateNextPosition($data["parentId"], $data["sectionId"]);

        $sql = "INSERT INTO forums (title, description, position, visible, parentId, sectionId) VALUES (";
        $sql .= ":title, :description, :position, :visible, :parentId, :sectionId";
        $sql .= ")";

        $sth = $this->db->prepare($sql);

        if (!$sth->execute($data)) {
            throw new DbException($sth->errorInfo()[2]);
        }

        return $this->db->lastInsertId();
    }

    public function update(array $data): void {
        $sql = "UPDATE forums SET ";
        $sql .= "   title=:title, ";
        $sql .= "   description=:description, ";
        $sql .= "   visible=:visible, ";
        $sql .= "   sectionId=:sectionId ";
        $sql .= "WHERE id=:id ";

        $sth = $this->db->prepare($sql);
        if (!$sth->execute($data)) {
            throw new DbException($sth->errorInfo()[2]);
        }
    }

    // ************************************************************************** //

    // topic model???
    public function didUserReadThis(int $userId, int $forumId): bool {
        // U readTopics pamtimo koji korisnik je procitao koju temu. Forum moze imati dosta
        // tema i korisnika, pa broj redova u ovoj tabeli moze biti ogroman. Odluceno je da
        // se svaka tema koja nije azurirana vise od gc_days dana smatra procitanom od strane
        // svih korisnika, te nema potrebe cuvati informacije o takvim temama u tabeli.

        // Teme direktno u ovom forumu.

        $sql = "SELECT id ";
        $sql .= "FROM topics ";
        $sql .= "WHERE forumId=:forumId ";
        $sql .= "    AND visible='1' ";
        $sql .= "    AND DATEDIFF(CURDATE(), latestPostDT) <= " . Config::get("db")["gc_days"];

        $sth = $this->db->prepare($sql);
        $sth->bindValue("forumId", $forumId);
        $sth->execute();

        $topics = $sth->fetchAll();

        foreach ($topics as $topic) {
            if (!$topicM->didUserReadThis($userId, $topic["id"])) {
                return false;
            }
        }

        // Teme u child forumima.

        $sql = "SELECT id ";
        $sql .= "FROM forums ";
        $sql .= "WHERE parentId=:parentId ";
        $sql .= "    AND visible='1' ";

        $sth = $this->db->prepare($sql);
        $sth->bindValue("parentId", $forumId);
        $sth->execute();

        $children = $sth->fetchAll();

        foreach ($children as $child) {
            if (!$this->didUserReadThis($userId, $child["id"])) {
                return false;
            }
        }

        return true;
    }

    public function countTopicsInForum(int $forumId, bool $countChildren = false): int {
        $sql = "SELECT COUNT(*) AS count ";
        $sql .= "FROM topics ";
        $sql .= "WHERE forumId=:forumId ";

        $sth = $this->db->prepare($sql);
        $sth->bindValue("forumId", $forumId);
        $sth->execute();

        $row = $sth->fetch();

        if (empty($row)) {
            throw new NotFoundException();
        }

        $count = $row["count"];

        if ($countChildren) {
            $children = $this->getAllByParentId($forumId);

            foreach ($children as $child) {
                $count += $this->countTopicsInForum($child["id"]);
            }
        }

        return $count;
    }

    // topic model??
    public function countPostsInForum(int $forumId, bool $countChildren = false): int {
        $count = 0;

        if ($countChildren) {
            $childForums = $this->getAllByParentId($forumId);
            foreach ($childForums as $childForum) {
                $count += $this->countPostsInForum($childForum["id"]);
            }
        }

        $topics = $topicM->getByForumId($forumId);
        foreach ($topics as $topic) {
            $count += $topicM->countPostsInTopic($topic["id"]);
        }

        return $count;
    }

    // function qDeleteForum($id) {
    //     // Ako forum ima dece, forumi nakon njega se pomeraju nanize da bi
    //     // se oslobodilo mesto za decu koja ce ostati bez roditelja. Inace
    //     // ce se forumi nakon njega pomeriti za po jedno mesto navise.

    //     dbEscape($id);

    //     $sql = "SELECT position, parentId ";
    //     $sql .= "FROM forums ";
    //     $sql .= "WHERE id='{$id}' ";

    //     if ($forum = executeAndFetchAssoc($sql)) {
    //         $position = $forum["position"];
    //         $parentId = $forum["parentId"];

    //         if ($parentId === "NULL") {
    //             $sql = "SELECT id, position ";
    //             $sql .= "FROM forums ";
    //             $sql .= "WHERE parentId='{$parentId}' ";
    //             $children = executeAndFetchAssoc($sql, FETCH::ALL);

    //             executeQuery("UPDATE forums SET parentId=NULL WHERE parentId='{$id}'");

    //             $sql = "SELECT id, position ";
    //             $sql .= "FROM forums ";
    //             $sql .= "WHERE position > {$position} AND parentId IS NULL ";
    //             $forumsAfter = executeAndFetchAssoc($sql, FETCH::ALL);

    //             if (($childrenCount = count($children)) > 0) {
    //                 foreach ($forumsAfter ?? [] as $forum) {
    //                     $forumId = $forum["id"];
    //                     $forumPosition = $forum["position"] + $childrenCount;
    //                     executeQuery("UPDATE forums SET position='{$forumPosition}' WHERE id='{$forumId}'");
    //                 }

    //                 foreach ($children ?? [] as $child) {
    //                     $childId = $child["id"];
    //                     $childPosition = $position++;
    //                     executeQuery("UPDATE forums SET position='{$childPosition}' WHERE id='{$childId}'");
    //                 }
    //             } else {
    //                 foreach ($forumsAfter ?? [] as $forum) {
    //                     $forumId = $forum["id"];
    //                     $forumPosition = $forum["position"] - 1;
    //                     executeQuery("UPDATE forums SET position='{$forumPosition}' WHERE id='{$forumId}'");
    //                 }
    //             }
    //         }

    //         executeQuery("DELETE FROM forums WHERE id='{$id}' LIMIT 1");
    //     }
    // }

}
