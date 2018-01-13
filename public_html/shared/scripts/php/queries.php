<?php

    function qGetForumsBySectionId($sectionId, $rootOnly = false, $sort = SORT::DEFAULT_VALUE) {
        $sectionId = dbEscape($sectionId);
        $sortColName = dbEscape($sort["columnName"]);
        $sortOrder = dbEscape($sort["order"]);

        $sql = "SELECT * ";
        $sql .= "FROM forums ";
        $sql .= "WHERE sections_id='{$sectionId}' ";
        if ($rootOnly) {
            $sql .= "AND parentid IS NULL ";
        }
        $sql .= "ORDER BY {$sortColName} {$sortOrder} ";

        return execAndFetchAssoc($sql, FETCH::ALL);
    }

    function qGetForumsByParentId($parentId, $sort = SORT::DEFAULT_VALUE) {
        $parentId = dbEscape($parentId);
        $sortColName = dbEscape($sort["columnName"]);
        $sortOrder = dbEscape($sort["order"]);

        $sql = "SELECT * ";
        $sql .= "FROM forums ";
        $sql .= "WHERE parentid='{$parentId}' ";
        $sql .= "ORDER BY {$sortColName} {$sortOrder} ";

        return execAndFetchAssoc($sql, FETCH::ALL);
    }

    function qGetSections($sort = SORT::DEFAULT_VALUE) {
        $sortColName = dbEscape($sort["columnName"]);
        $sortOrder = dbEscape($sort["order"]);

        $sql = "SELECT * ";
        $sql .= "FROM sections ";
        $sql .= "ORDER BY {$sortColName} {$sortOrder} ";

        return execAndFetchAssoc($sql, FETCH::ALL);
    }