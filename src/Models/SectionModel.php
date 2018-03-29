<?php

namespace Forum41\Models;

use Forum41\Domain\Section;
use Forum41\Exceptions\DbException;
use Forum41\Exceptions\NotFoundException;

class SectionModel extends BaseModel {

    public function getById(int $id) {
        return $this->get_by_id($id, "sections", self::CLASS_SECTION);
    }

    public function getAll(array $sort = ["id" => "ASC"]): array {
        return $this->get_all($sort, "sections", self::CLASS_SECTION);
    }

    // ************************************************************************** //

    function insert(array $data) {
        $data["position"] = $this->calculateNextPosition();

        $sql = "INSERT INTO sections (title, description, position, visible) VALUES (";
        $sql .= ":title, :description, :position, :visible";
        $sql .= ")";

        $sth = $this->db->prepare($sql);

        if (!$sth->execute($data)) {
            throw new DbException($sth->errorInfo()[2]);
        }
    }

    function update($id, $title, $description, $visible) {
        $sql = "UPDATE sections SET ";
        $sql .= "   title=:title, ";
        $sql .= "   description=:description, ";
        $sql .= "   visible=:visible ";
        $sql .= "WHERE id=:id ";

        $sth = $this->db->prepare($sql);

        if (!$sth->execute($data)) {
            throw new DbException($sth->errorInfo()[2]);
        }
    }

    function calculateNextPosition() {
        $sql = "SELECT MAX(position) AS position ";
        $sql .= "FROM sections ";

        $sth = $this->db->query($sql);

        $calcPos = $sth->fetch();

        if (empty($calcPos)) {
            throw new NotFoundException();
        }

        return $calcPos["position"] + 1;
    }

    // function qDeleteSection($id) {
    //     // Sekcije nakon obrisane se pomeraju za jedno mesto navise.

    //     dbEscape($id);

    //     $sql = "SELECT position ";
    //     $sql .= "FROM sections ";
    //     $sql .= "WHERE id='{$id}' ";
    //     if ($section = executeAndFetchAssoc($sql)) {
    //         executeQuery("DELETE FROM sections WHERE id='{$id}' LIMIT 1");

    //         $sql = "SELECT id, position ";
    //         $sql .= "FROM sections ";
    //         $sql .= "WHERE position > {$section["position"]} ";
    //         $sectionsAfter = executeAndFetchAssoc($sql, FETCH::ALL);

    //         foreach ($sectionsAfter ?? [] as $section) {
    //             $sectionId = $section["id"];
    //             $sectionPosition = $section["position"] - 1;
    //             executeQuery("UPDATE sections SET position='{$sectionPosition}' WHERE id='{$sectionId}'");
    //         }
    //     }
    // }

}
