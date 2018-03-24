<?php

    function qGetAllForums($rootOnly = false, $sort = SORT::DEFAULT_VALUE) {
        $sql = "SELECT * ";
        $sql .= "FROM forums ";
        if ($rootOnly) {
            $sql .= "WHERE parentid IS NULL ";
        }
        $sql .= orderByStatement($sort);

        return executeAndFetchAssoc($sql, FETCH::ALL);
    }

    function qGetColumnsInfo($table) {
        dbEscape($table);

        $sql = "SELECT ";
        $sql .= "   column_name as name, ";
        $sql .= "   data_type as type, ";
        $sql .= "   extra, ";
        $sql .= "   is_nullable ";
        $sql .= "FROM information_schema.columns ";
        $sql .= "WHERE table_schema='" . DB_NAME . "' ";
        $sql .= "   AND table_name='{$table}' ";

        return executeAndFetchAssoc($sql, FETCH::ALL);
    }

    function gInsertForum($title, $description, $visible, $parentId, $sectionsId) {
        dbEscape($title, $description, $visible, $parentId, $sectionsId);

        if (isNotBlank($parentId)) {
            // ako ima roditelja onda pripada istoj sekciji kao on
            if ($parent = qGetRowById($parentId, "forums")) {
                $sectionsId = $parent["sections_id"];
            }
            $parentId = q($parentId);
        } else {
            $parentId = "NULL";
        }

        $position = qGetNewForumPosition($parentId, $sectionsId);

        $sql = "INSERT INTO forums (title, description, position, visible, parentid, sections_id) VALUES (";
        $sql .= "'{$title}', '{$description}', '{$position}', '{$visible}', {$parentId}, '{$sectionsId}'";
        $sql .= ")";

        executeQuery($sql);
    }

    function qGetNewForumPosition($parentId, $sectionsId) {
        $sql = "SELECT MAX(position) as position ";
        $sql .= "FROM forums ";
        if ($parentId === "NULL") {
            $sql .= "WHERE sections_id='{$sectionsId}' AND parentid IS NULL";
        } else {
            $sql .= "WHERE parentid={$parentId} ";
        }
        return executeAndFetchAssoc($sql)["position"] + 1;
    }

    function qInsertSection($title, $description, $visible) {
        dbEscape($title, $description, $visible);

        $position = qGetNewSectionPosition();

        $sql = "INSERT INTO sections (title, description, position, visible) VALUES (";
        $sql .= "'{$title}', '{$description}', '{$position}', '{$visible}'";
        $sql .= ")";

        executeQuery($sql);
    }

    function qGetNewSectionPosition() {
        $sql = "SELECT MAX(position) AS position ";
        $sql .= "FROM sections ";
        return executeAndFetchAssoc($sql)["position"] + 1;
    }

    function qDeleteSection($id) {
        // Sekcije nakon obrisane se pomeraju za jedno mesto navise.

        dbEscape($id);

        $sql = "SELECT position ";
        $sql .= "FROM sections ";
        $sql .= "WHERE id='{$id}' ";
        if ($section = executeAndFetchAssoc($sql)) {
            executeQuery("DELETE FROM sections WHERE id='{$id}' LIMIT 1");

            $sql = "SELECT id, position ";
            $sql .= "FROM sections ";
            $sql .= "WHERE position > {$section["position"]} ";
            $sectionsAfter = executeAndFetchAssoc($sql, FETCH::ALL);

            foreach ($sectionsAfter ?? [] as $section) {
                $sectionId = $section["id"];
                $sectionPosition = $section["position"] - 1;
                executeQuery("UPDATE sections SET position='{$sectionPosition}' WHERE id='{$sectionId}'");
            }
        }
    }

    function qDeleteForum($id) {
        // Ako forum ima dece, forumi nakon njega se pomeraju nanize da bi
        // se oslobodilo mesto za decu koja ce ostati bez roditelja. Inace
        // ce se forumi nakon njega pomeriti za po jedno mesto navise.

        dbEscape($id);

        $sql = "SELECT position, parentid ";
        $sql .= "FROM forums ";
        $sql .= "WHERE id='{$id}' ";

        if ($forum = executeAndFetchAssoc($sql)) {
            $position = $forum["position"];
            $parentId = $forum["parentid"];

            if ($parentId === "NULL") {
                $sql = "SELECT id, position ";
                $sql .= "FROM forums ";
                $sql .= "WHERE parentid='{$parentId}' ";
                $children = executeAndFetchAssoc($sql, FETCH::ALL);

                executeQuery("UPDATE forums SET parentid=NULL WHERE parentid='{$id}'");

                $sql = "SELECT id, position ";
                $sql .= "FROM forums ";
                $sql .= "WHERE position > {$position} AND parentid IS NULL ";
                $forumsAfter = executeAndFetchAssoc($sql, FETCH::ALL);

                if (($childrenCount = count($children)) > 0) {
                    foreach ($forumsAfter ?? [] as $forum) {
                        $forumId = $forum["id"];
                        $forumPosition = $forum["position"] + $childrenCount;
                        executeQuery("UPDATE forums SET position='{$forumPosition}' WHERE id='{$forumId}'");
                    }

                    foreach ($children ?? [] as $child) {
                        $childId = $child["id"];
                        $childPosition = $position++;
                        executeQuery("UPDATE forums SET position='{$childPosition}' WHERE id='{$childId}'");
                    }
                } else {
                    foreach ($forumsAfter ?? [] as $forum) {
                        $forumId = $forum["id"];
                        $forumPosition = $forum["position"] - 1;
                        executeQuery("UPDATE forums SET position='{$forumPosition}' WHERE id='{$forumId}'");
                    }
                }
            }

            executeQuery("DELETE FROM forums WHERE id='{$id}' LIMIT 1");
        }
    }

    function qUpdateForum($id, $title, $description, $visible, $sectionsId) {
        dbEscape($id, $title, $description, $visible, $sectionsId);

        $sql = "UPDATE forums SET ";
        $sql .= "   title='{$title}', ";
        $sql .= "   description='{$description}', ";
        $sql .= "   visible='{$visible}', ";
        $sql .= "   sections_id='{$sectionsId}' ";
        $sql .= "WHERE id='{$id}' ";

        executeQuery($sql);
    }

    function qUpdateCell($tableName, $rowId, $colName, $newValue) {
        dbEscape($tableName, $rowId, $colName, $newValue);
        if ($newValue !== "NULL") {
            $newValue = q($newValue);
        }

        $sql = "UPDATE {$tableName} SET ";
        $sql .= "   {$colName}={$newValue} ";
        $sql .= "WHERE id='{$rowId}' ";

        executeQuery($sql);
    }

    function qUpdateSection($id, $title, $description, $visible) {
        dbEscape($id, $title, $description, $visible);

        $sql = "UPDATE sections SET ";
        $sql .= "   title='{$title}', ";
        $sql .= "   description='{$description}', ";
        $sql .= "   visible='{$visible}' ";
        $sql .= "WHERE id='{$id}' ";

        executeQuery($sql);
    }

    function qClearTable($tableName) {
        dbEscape($tableName);
        executeQuery("DELETE FROM {$tableName}");
        executeQuery("ALTER TABLE {$tableName} AUTO_INCREMENT=1");
    }

    function qGetSectionByForumId($id) {
        dbEscape($id);

        $sql = "SELECT sections_id ";
        $sql .= "FROM forums ";
        $sql .= "WHERE id='{$id}' ";

        if ($forum = executeAndFetchAssoc($sql)) {
            return $forum["sections_id"];
        }

        return null;
    }
