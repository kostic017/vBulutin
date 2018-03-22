<?php
    require_once __DIR__ . "/includes.php";

    if (isset($_POST["job"])) {
        switch ($_POST["job"]) {

            case "parent_section":
                {
                    echo qGetForumSection($_POST["id"]);
                }
                break;

            case "sort":
                {
                    $order = $_POST["order"];
                    $columnName = $_POST["columnName"];
                    $tableName = $_POST["tableName"];

                    $options = [$columnName => $order];

                    switch ($tableName) {
                        case "forums":
                            $tableData = qGetAllForums(false, $options);
                            break;
                        case "sections":
                            $tableData = qGetRowsByTableName("sections", $options);
                            break;
                    }

                    foreach ($tableData as $rows) {
                        foreach ($rows as $row) {
                            require "datarow.php";
                        }
                    }
                }
                break;

            case "save_positions":
                {
                    foreach ($_POST["data"] as $sectionId => $forums) {
                        foreach ($forums as $rootIndex => $rootForum) {
                            qUpdateForumCell($rootForum["id"], "parentid", "NULL");
                            qUpdateForumCell($rootForum["id"], "sections_id", $sectionId);
                            qUpdateForumCell($rootForum["id"], "position", $rootIndex + 1);
                            if (isset($rootForum["children"])) {
                                foreach ($rootForum["children"] as $childIndex => $childForum) {
                                    qUpdateForumCell($childForum["id"], "parentid", $rootForum["id"]);
                                    qUpdateForumCell($childForum["id"], "sections_id", $sectionId);
                                    qUpdateForumCell($childForum["id"], "position", $childIndex + 1);
                                }
                            }
                        }
                    }
                }
                break;

        }
    }
