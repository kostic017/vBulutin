<?php

    function dbEscape($string) {
        global $db;
        return mysqli_real_escape_string($db, $string);
    }

    function dbClose() {
        global $db;
        if (isset($db)) {
            mysqli_close($db);
        }
    }

    function isThereAResult($sql) {
        global $db;
        return mysqli_num_rows(executeQuery($sql)) > 0;
    }

    function executeQuery($sql) {
        global $db;
        if ($result = mysqli_query($db, $sql)) {
            return $result;
        } else {
            $message = DEBUG ?
                sprintf("Database query failed: <b>%s</b>. %s (%d)",
                    h($sql), h(mysqli_error($db)), h(mysqli_errno($db))) :
                "Database query failed.";
            dbClose();
            exit($message);
        }
    }

    function executeAndFetchAssoc($sql, $count = FETCH::ONE) {
        $ret = [];
        $result = executeQuery($sql);
        if ($count === FETCH::ONE) {
            $ret[0] = mysqli_fetch_assoc($result);
        } elseif ($count === FETCH::ALL) {
            // Posle mozemo da pronadjemo element sa odredjenim ID-om u O(1).
            // Array ( [***] => Array ( [id] => *** ... ) ... )
            while ($row = mysqli_fetch_assoc($result)) {
                if (isset($row["id"])) {
                    $ret[$row["id"]] = $row;
                } else {
                    $ret[] = $row;
                }
            }
        }
        mysqli_free_result($result);
        return ($count === FETCH::ONE) ? $ret[0] : $ret;
    }
