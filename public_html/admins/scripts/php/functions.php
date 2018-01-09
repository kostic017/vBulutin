<?php
    function getSelectmenuBasedOnArray($dom, $columnName) {
        $selectMenu = $dom->createElement("select");
        $selectMenu->setAttribute("class", "jui-selectmenu");

        if ($columnName === "parentid") {
            $rows = qGetForums(true);
            // placeholder
            $option = $dom->createElement("option", "");
            $option->setAttribute("value", "");
            $selectMenu->appendChild($option);
        } else {
            $rows = qGetSections();
        }

        foreach ($rows as $row) {
            $option = $dom->createElement("option", "{$row["id"]} ({$row["title"]})");
            $option->setAttribute("value", $row["id"]);
            $selectMenu->appendChild($option);
        }

        return $selectMenu;
    }

    function getInsertControlForColumn($dom, $columnInfo) {
        if (isEqualToAnyWord("parentid sections_id", $columnInfo["name"])) {
            $control = getSelectmenuBasedOnArray($dom, $columnInfo["name"]);
        } else {
            $control = $dom->createElement("input");

            switch ($columnInfo["type"]) {
                case "int": {
                    $control->setAttribute("type", "number");
                    $control->setAttribute("class", "jui-spinner");
                } break;
                case "varchar": {
                    $control->setAttribute("type", "text");
                } break;
                case "tinyint": {
                    $control->setAttribute("type", "checkbox");
                    $control->setAttribute("checked", "");
                } break;
            }

            if ($columnInfo["name"] === "position" || hasString($columnInfo["extra"] ?? "", "auto_increment")) {
                $control->setAttribute("disabled", "disabled");
            }
        }

        if ($columnInfo["is_nullable"] === "NO") {
            $control->setAttribute("data-required", "");
        }

        $control->setAttribute("name", $columnInfo["name"]);

        return $control;
    }

    function getSubmitButton($dom, $action, $tableName, $id = "") {
        $button = $dom->createElement("button");

        $button->setAttribute("value", $id);
        $button->setAttribute("class", "icon icon-{$action}");
        $button->setAttribute("title", ucfirst($action));
        $button->setAttribute("name", "{$tableName}_{$action}");

        if ($action === "update") {
            $button->setAttribute("type", "button");
            $button->setAttribute("onclick", "updateRowAction('{$tableName}', '{$id}')");
        } else {
            $button->setAttribute("type", "submit");
            if (isEqualToAnyWord("delete clear", $action)) {
                $button->setAttribute("onclick", "return areYouSure()");
            }
        }

        return $button;
    }

    // $table["parent"]:
    //  - definisano: pripaja redove podataka datoj tabeli
    //  - nije definisano: pripaja redove podataka datom DOM-u
    // $table["data"]:
    //  - definisano: koristi date podatke
    //  - nije definisano: podatke cita iz baze
    function appendDataRows($dom, $tableName, $table) {
        $forums = qGetForums();
        $sections = qGetSections();

        $appendTarget = $table["parent"] ?? $dom;

        if (isset($table["data"])) {
            $tableData = $table["data"];
        } else {
            switch ($tableName) {
                case "forums": $tableData = $forums; break;
                case "sections": $tableData = $sections; break;
            }
        }

        foreach ($tableData as $row) {
            $dataRow = $dom->createElement("tr");
            $dataRow->setAttribute("data-id", $row["id"]);

            // Edit & Delete
            $dataCell = $dom->createElement("td");
            $updateButton = getSubmitButton($dom, "update", $tableName, $row["id"]);
            $deleteButton = getSubmitButton($dom, "delete", $tableName, $row["id"]);

            $dataCell->appendChild($updateButton);
            $dataCell->appendChild($deleteButton);

            $dataRow->appendChild($dataCell);

            // Podaci
            foreach ($row as $columnName => $columnValue) {
                $dataCell = $dom->createElement("td", $columnValue);
                $dataCell->setAttribute("data-value", $columnValue);

                if ($columnName === "visible") {
                    // Red moze da ima neku svoju vrednost za visibility, medjutim kada je u pitanju forum moze da se
                    // desi da roditeljski forum nije vidljiv ili da sekcija kojoj forum pripada nije vidljiva i tada
                    // ni dati forum nece biti vidljiv, cak i ako ima vrednost 1 za visibility. Zato prikazujem i
                    // ikonicu koja oznacava da li je neki element zaista vidljiv ili nije.

                    $reason = "Tako kako je podešeno.";

                    if ($columnValue === "1") {
                        $visible = "visible";

                        // ako sekcija nije vidljiva, nije ni forum
                        if (isset($row["sections_id"]) && isNotBlank($row["sections_id"])) {
                            if ($sections[$row["sections_id"]]["visible"] !== "1") {
                                $reason = "Sekcija nije vidljiva.";
                                $visible = "invisible";
                            }
                        }

                        // ako roditeljski forum nije vidljiv, nije ni ovaj forum
                        if (isset($row["parentid"]) && isNotBlank($row["parentid"])) {
                            if ($forums[$row["parentid"]]["visible"] !== "1") {
                                $reason = "Roditeljski forum nije vidljiv.";
                                $visible = "invisible";
                            }
                        }
                    } else {
                        $visible = "invisible";
                    }

                    $eyeIcon = $dom->createElement("span");
                    $eyeIcon->setAttribute("class", "icon icon-{$visible}");
                    $eyeIcon->setAttribute("title", $reason);

                    $dataCell->appendChild($eyeIcon);
                } else {
                    $title = "";
                    // prikazi naziv foruma/sekcije pored ID-a
                    if (isNotBlank($columnValue) && isEqualToAnyWord("parentid sections_id", $columnName)) {
                        $title = " (" . ($columnName === "parentid" ? $forums[$columnValue]["title"] : $sections[$columnValue]["title"]) . ")";
                    }
                    $dataCell->appendChild($dom->createTextNode($title));
                }

                $dataRow->appendChild($dataCell);
            }
            $appendTarget->appendChild($dataRow);
        }
    }

    function getDataTable($tableName) {
        $dom = getNewDom();

        $columnsInfo = qGetColumnsInfo($tableName);

        $table = $dom->createElement("table");
        $table->setAttribute("class", "table-fill");
        $table->setAttribute("data-name", $tableName);

        // Zaglavlje
        $headingRow = $dom->createElement("tr");

        $headingCell = $dom->createElement("th");
        $clearButton = getSubmitButton($dom, "clear", $tableName);
        $headingCell->appendChild($clearButton);

        $headingRow->appendChild($headingCell);

        foreach ($columnsInfo as $columnInfo) {
            $headingCell = $dom->createElement("th");
            $columnTitle = $dom->createElement("a", $columnInfo["name"]);
            $sortIcon = $dom->createElement("span");

            $columnTitle->setAttribute("href", "javascript:void(0)");
            $columnTitle->setAttribute("class", "btn-sort");
            $columnTitle->setAttribute("data-columnName", $columnInfo["name"]);

            $sortIcon->setAttribute("class", "icon icon-sort");

            $headingCell->appendChild($columnTitle);
            $headingCell->appendChild($sortIcon);

            $headingRow->appendChild($headingCell);
        }

        $table->appendChild($headingRow);

        // Kontrole za unos podataka
        $insertRow = $dom->createElement("tr");
        $insertRow->setAttribute("class", "insert-row");

        $insertCell = $dom->createElement("td");
        $insertButton = getSubmitButton($dom, "insert", $tableName);
        $insertCell->appendChild($insertButton);

        $insertRow->appendChild($insertCell);

        foreach ($columnsInfo as $columnInfo) {
            $insertCell = $dom->createElement("td");
            $insertControl = getInsertControlForColumn($dom, $columnInfo);
            $insertCell->appendChild($insertControl);
            $insertRow->appendChild($insertCell);
        }

        $table->appendChild($insertRow);
        appendDataRows($dom, $tableName, ["parent" => $table]);

        $dom->appendChild($table);
        return $dom->saveHTML();
    }