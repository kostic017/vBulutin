<?php

    function qGetRowsByTableName($tableName, $sort = SORT::DEFAULT_VALUE) {
        dbEscape($tableName);

        $sql = "SELECT * ";
        $sql .= "FROM {$tableName} ";
        $sql .= orderByStatement($sort);

        return executeAndFetchAssoc($sql, FETCH::ALL);
    }

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
        $sql .= "WHERE sectionId='{$sectionId}' ";
        if ($rootOnly) {
            $sql .= "AND parentId IS NULL ";
        }
        $sql .= orderByStatement($sort);

        return executeAndFetchAssoc($sql, FETCH::ALL);
    }

    function qGetForumsByParentId($parentId, $sort = SORT::DEFAULT_VALUE) {
        dbEscape($parentId);

        $sql = "SELECT * ";
        $sql .= "FROM forums ";
        $sql .= "WHERE parentId='{$parentId}' ";
        $sql .= orderByStatement($sort);

        return executeAndFetchAssoc($sql, FETCH::ALL);
    }

