<?php
    require_once __DIR__ . "/includes.php";

    if (isPostRequest() && isset($_POST["job"])) {
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

                    $options = ["columnName" => $columnName, "order" => $order];

                    switch ($tableName) {
                        case "forums":
                            $tableData = qGetForums(false, $options);
                            break;
                        case "sections":
                            $tableData = qGetSections($options);
                            break;
                    }

                    $dom = getNewDom();
                    appendDataRows($dom, $tableName, ["data" => $tableData]);
                    echo $dom->saveHTML();
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
