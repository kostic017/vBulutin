<?php

    function qGetRowById($rowId, $tableName) {
        dbEscape($rowId, $tableName);

        $sql = "SELECT * ";
        $sql .= "FROM {$tableName} ";
        $sql .= "WHERE id='{$rowId}' ";

        return executeAndFetchAssoc($sql);
    }

    function qGetForumsBySectionId($sectionId, $rootOnly = false, $sort = SORT::DEFAULT_VALUE) {
        dbEscape($sectionId);

        $sql = "SELECT * ";
        $sql .= "FROM forums ";
        $sql .= "WHERE sections_id='{$sectionId}' ";
        if ($rootOnly) {
            $sql .= "AND parentid IS NULL ";
        }
        $sql .= orderByStatement($sort);

        return executeAndFetchAssoc($sql, FETCH::ALL);
    }

    function qGetForumsByParentId($parentId, $sort = SORT::DEFAULT_VALUE) {
        dbEscape($parentId);

        $sql = "SELECT * ";
        $sql .= "FROM forums ";
        $sql .= "WHERE parentid='{$parentId}' ";
        $sql .= orderByStatement($sort);

        return executeAndFetchAssoc($sql, FETCH::ALL);
    }

    function qGetSections($sort = SORT::DEFAULT_VALUE) {
        $sql = "SELECT * ";
        $sql .= "FROM sections ";
        $sql .= orderByStatement($sort);

        return executeAndFetchAssoc($sql, FETCH::ALL);
    }
