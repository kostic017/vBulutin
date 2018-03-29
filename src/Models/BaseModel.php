<?php

namespace Forum41\Models;

use PDO;
use Forum41\Core\Db;
use Forum41\Exceptions\NotFoundException;

abstract class BaseModel {
    protected $db;

    protected const CLASS_USER = "\Forum41\Domain\User";
    protected const CLASS_POST = "\Forum41\Domain\Post";
    protected const CLASS_FORUM = "\Forum41\Domain\Forum";
    protected const CLASS_TOPIC = "\Forum41\Domain\Topic";
    protected const CLASS_SECTION = "\Forum41\Domain\Section";

    protected function get_by_id(int $id, string $table, string $className) {
        $sql = "SELECT * ";
        $sql .= "FROM `{$table}` ";
        $sql .= "WHERE id=:id";

        $sth = $this->db->prepare($sql);
        $sth->bindValue("id", $id);
        $sth->execute();

        $rows = $sth->fetchAll(PDO::FETCH_CLASS, $className);

        if (empty($rows)) {
            throw new NotFoundException("Row with id {$id} doesn't exists in `{$table}`.");
        }

        return $rows[0];
    }

    protected function get_all(array $sort, string $table, string $className): array {
        $sql = "SELECT * ";
        $sql .= "FROM `{$table}` ";
        $sql .= self::orderByStatement($sort);

        $sth = $this->db->query($sql);
        return $sth->fetchAll(PDO::FETCH_CLASS, $className);
    }

    protected function orderByStatement(array $sort): string {
        $sql = "ORDER BY ";
        $counter = count($sort);
        foreach ($sort as $column => $direction) {
            $sql .= "{$column} {$direction}";
            $sql .= (--$counter > 0) ? ", " : " ";
        }
        return $sql;
    }

    public function __construct() {
        $this->db = Db::getInstance();
    }
}
