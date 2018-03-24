<?php
    require_once __DIR__ . "/includes.php";

    if (isset($_POST["job"])) {

        switch ($_POST["job"]) {

            case "parent_section":
                echo qGetForumSection($_POST["id"]);
            break;

            case "table_sort":
                $order = $_POST["order"];
                $tableName = $_POST["tableName"];
                $columnName = $_POST["columnName"];

                $options = [$columnName => $order];

                switch ($tableName) {
                    case "forums":
                        $tableData = qGetAllForums(false, $options);
                        break;
                    case "sections":
                        $tableData = qGetRowsByTableName("sections", $options);
                        break;
                }

                foreach ($tableData as $row) {
                    require "../../datarow.php";
                }
            break;

            case "positioning_save":
                foreach ($_POST["data"] ?? [] as $sectionId => $sectionData) {
                    qUpdateCell("sections", $sectionId, "position", $sectionData["position"]);
                    foreach ($sectionData["forums"] ?? [] as $rootIndex => $rootForum) {
                        qUpdateCell("forums", $rootForum["id"], "parentid", "NULL");
                        qUpdateCell("forums", $rootForum["id"], "sections_id", $sectionId);
                        qUpdateCell("forums", $rootForum["id"], "position", $rootIndex + 1);
                        foreach ($rootForum["children"] ?? [] as $childIndex => $childForum) {
                            qUpdateCell("forums", $childForum["id"], "parentid", $rootForum["id"]);
                            qUpdateCell("forums", $childForum["id"], "sections_id", $sectionId);
                            qUpdateCell("forums", $childForum["id"], "position", $childIndex + 1);
                        }
                    }
                }
            break;

        }

    }
