<?php

    function qGetColumnsInfo($table) {
        $table = dbEscape($table);

        $sql = "SELECT ";
        $sql .= "   column_name as name, ";
        $sql .= "   data_type as type, ";
        $sql .= "   extra, ";
        $sql .= "   is_nullable ";
        $sql .= "FROM information_schema.columns ";
        $sql .= "WHERE table_schema='" . DB_NAME . "' ";
        $sql .= "   AND table_name='{$table}' ";

        return execAndFetchAssoc($sql, FETCH::ALL);
    }

    function qUpdateForum($newData) {
        $id = dbEscape($newData["forums_update"]);
        $title = dbEscape($newData["title"]);
        $description = dbEscape($newData["description"]);
        $visible = isset($newData["visible"]) ? "1" : "0";
        $sectionsId = dbEscape($newData["sections_id"]);

        $sql = "UPDATE forums SET ";
        $sql .= "   title='{$title}', ";
        $sql .= "   description='{$description}', ";
        $sql .= "   visible='{$visible}', ";
        $sql .= "   sections_id='{$sectionsId}' ";
        $sql .= "WHERE id='{$id}' ";

        executeQuery($sql);
    }

    function qUpdateForumCell($id, $colName, $newValue) {
        $id = dbEscape($id);
        $colName = dbEscape($colName);
        if ($newValue !== "NULL") {
            $newValue = q(dbEscape($newValue));
        }

        $sql = "UPDATE forums SET ";
        $sql .= "   {$colName}={$newValue} ";
        $sql .= "WHERE id='{$id}' ";

        executeQuery($sql);
    }

    function qUpdateSection($newData) {
        $id = dbEscape($newData["sections_update"]);
        $title = dbEscape($newData["title"]);
        $description = dbEscape($newData["description"]);
        $visible = isset($newData["visible"]) ? "1" : "0";

        $sql = "UPDATE sections SET ";
        $sql .= "   title='{$title}', ";
        $sql .= "   description='{$description}', ";
        $sql .= "   visible='{$visible}' ";
        $sql .= "WHERE id='{$id}' ";

        executeQuery($sql);
    }

    function qDeleteForum($id) {
        // Ako forum ima dece, forumi nakon njega se pomeraju nanize da bi
        // se oslobodilo mesto za decu koja ce ostati bez roditelja. Inace
        // ce se forumi nakon njega pomeriti za po jedno mesto navise.

        $id = dbEscape($id);

        $sql = "SELECT position, parentid ";
        $sql .= "FROM forums ";
        $sql .= "WHERE id='{$id}' ";
        $res = execAndFetchAssoc($sql);
        $position = $res["position"];
        $parentId = $res["parentid"];

        if ($parentId === "NULL") {
            $sql = "SELECT id, position ";
            $sql .= "FROM forums ";
            $sql .= "WHERE parentid='{$parentId}' ";
            $children = execAndFetchAssoc($sql, FETCH::ALL);

            executeQuery("UPDATE forums SET parentid=NULL WHERE parentid='{$id}'");

            $sql = "SELECT id, position ";
            $sql .= "FROM forums ";
            $sql .= "WHERE position > {$position} AND parentid=NULL ";
            $forumsAfter = execAndFetchAssoc($sql, FETCH::ALL);

            if (($childrenCount = count($children)) > 0) {
                foreach ($forumsAfter as $forum) {
                    $forumId = $forum["id"];
                    $forumPosition = $forum["position"] + $childrenCount;
                    executeQuery("UPDATE forums SET position='{$forumPosition}' WHERE id='{$forumId}'");
                }

                foreach ($children as $child) {
                    $childId = $child["id"];
                    $childPosition = $position++;
                    executeQuery("UPDATE forums SET position='{$childPosition}' WHERE id='{$childId}'");
                }
            } else {
                foreach ($forumsAfter as $forum) {
                    $forumId = $forum["id"];
                    $forumPosition = $forum["position"] - 1;
                    executeQuery("UPDATE forums SET position='{$forumPosition}' WHERE id='{$forumId}'");
                }
            }
        }

        executeQuery("DELETE FROM forums WHERE id='{$id}' LIMIT 1");
    }

    function qDeleteSection($id) {
        // Sekcije nakon obrisane se pomeraju za jedno mesto navise.

        $id = dbEscape($id);

        $sql = "SELECT position ";
        $sql .= "FROM sections ";
        $sql .= "WHERE id='{$id}' ";
        $position = execAndFetchAssoc($sql)["position"];

        executeQuery("DELETE FROM sections WHERE id='{$id}' LIMIT 1");

        $sql = "SELECT id, position ";
        $sql .= "FROM sections ";
        $sql .= "WHERE position > {$position} ";
        $sectionsAfter = execAndFetchAssoc($sql, FETCH::ALL);

        foreach ($sectionsAfter as $section) {
            $sectionId = $section["id"];
            $sectionPosition = $section["position"] - 1;
            executeQuery("UPDATE sections SET position='{$sectionPosition}' WHERE id='{$sectionId}'");
        }
    }

    function qClearTable($tableName) {
        $tableName = dbEscape($tableName);
        executeQuery("DELETE FROM {$tableName}");
        executeQuery("ALTER TABLE {$tableName} AUTO_INCREMENT=1");
    }

    function qGetForums($rootOnly = false, $sort = SORT::DEFAULT_VALUE) {
        $sortColName = dbEscape($sort["columnName"]);
        $sortOrder = dbEscape($sort["order"]);

        $sql = "SELECT * ";
        $sql .= "FROM forums ";
        if ($rootOnly) {
            $sql .= "WHERE parentid IS NULL ";
        }
        $sql .= "ORDER BY {$sortColName} {$sortOrder} ";

        return execAndFetchAssoc($sql, FETCH::ALL);
    }

    function qGetForumSection($id) {
        $id = dbEscape($id);

        $sql = "SELECT sections_id ";
        $sql .= "FROM forums ";
        $sql .= "WHERE id='{$id}' ";

        $res = execAndFetchAssoc($sql);
        return $res["sections_id"];
    }

    function qGetNewForumPosition($parentId, $sectionsId) {
        $sql = "SELECT MAX(position) as position ";
        $sql .= "FROM forums ";
        if ($parentId === "NULL") {
            $sql .= "WHERE sections_id='{$sectionsId}' AND parentid IS NULL";
        } else {
            $sql .= "WHERE parentid='{$parentId}' ";
        }
        return execAndFetchAssoc($sql)["position"] + 1;
    }

    function qGetNewSectionPosition() {
        $sql = "SELECT MAX(position) AS position ";
        $sql .= "FROM sections ";
        return execAndFetchAssoc($sql)["position"] + 1;
    }

    function gInsertForum($forumData) {
        $title = dbEscape($forumData["title"]);
        $description = dbEscape($forumData["description"]);
        $visible = isset($forumData["visible"]) ? "1" : "0";

        if (isNotBlank($forumData["parentid"])) {
            // ako ima roditelja onda pripada istoj sekciji kao on
            $parentId = dbEscape($forumData["parentid"]);
            $parent = qGetRowById($forumData["parentid"], "forums");
            $sectionsId = dbEscape($parent["sections_id"]);
        } else {
            $parentId = "NULL";
            $sectionsId = dbEscape($forumData["sections_id"]);
        }

        $position = qGetNewForumPosition($parentId, $sectionsId);

        $sql = "INSERT INTO forums (title, description, position, visible, parentid, sections_id) VALUES (";
        $sql .= "'{$title}', '{$description}', {$position}, {$visible}, {$parentId}, {$sectionsId}";
        $sql .= ")";

        executeQuery($sql);
    }

    function qInsertSection($data) {
        $title = dbEscape($data["title"]);
        $description = dbEscape($data["description"]);
        $visible = isset($data["visible"]) ? "1" : "0";

        $position = qGetNewSectionPosition();

        $sql = "INSERT INTO sections (title, description, position, visible) VALUES (";
        $sql .= "'{$title}', '{$description}', {$position}, {$visible}";
        $sql .= ")";

        executeQuery($sql);
    }

