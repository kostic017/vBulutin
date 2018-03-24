<?php
    require_once __DIR__ . "/includes.php";

    if (isset($_POST["job"])) {

        switch ($_POST["job"]) {

            case "parent_section":
                echo qGetSectionByForumId($_POST["id"]);
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
                    require "../../table-row.php";
                }
            break;

            case "positioning_save":
                foreach ($_POST["data"] ?? [] as $sectionId => $sectionData) {
                    qUpdateCell("sections", $sectionId, "position", $sectionData["position"]);
                    foreach ($sectionData["forums"] ?? [] as $rootIndex => $rootForum) {
                        qUpdateCell("forums", $rootForum["id"], "parentId", "NULL");
                        qUpdateCell("forums", $rootForum["id"], "sectionId", $sectionId);
                        qUpdateCell("forums", $rootForum["id"], "position", $rootIndex + 1);
                        foreach ($rootForum["children"] ?? [] as $childIndex => $childForum) {
                            qUpdateCell("forums", $childForum["id"], "parentId", $rootForum["id"]);
                            qUpdateCell("forums", $childForum["id"], "sectionId", $sectionId);
                            qUpdateCell("forums", $childForum["id"], "position", $childIndex + 1);
                        }
                    }
                }
            break;

        }

    }
