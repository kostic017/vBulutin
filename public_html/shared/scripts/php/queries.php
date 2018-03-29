<?php

    function qCountTableRows($tableName) {
        dbEscape($tableName);

        $sql = "SELECT COUNT(*) as count ";
        $sql .= "FROM {$tableName} ";

        if ($res = executeAndFetchAssoc($sql)) {
            return $res["count"];
        }

        return null;
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

    function qClearTable($tableName) {
        dbEscape($tableName);
        executeQuery("DELETE FROM {$tableName}");
        executeQuery("ALTER TABLE {$tableName} AUTO_INCREMENT=1");
    }

    function qGetRowsByTableName($tableName, $sort = SORT::DEFAULT_VALUE) {
        dbEscape($tableName);

        $sql = "SELECT * ";
        $sql .= "FROM {$tableName} ";
        $sql .= $this->orderByStatement($sort);

        return executeAndFetchAssoc($sql, FETCH::ALL);
    }

    function qGetRowById($rowId, $tableName) {
        dbEscape($rowId, $tableName);

        $sql = "SELECT * ";
        $sql .= "FROM {$tableName} ";
        $sql .= "WHERE id='{$rowId}' ";

        return executeAndFetchAssoc($sql);
    }



