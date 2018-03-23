<?php
    function getInsertControlForColumn($columnInfo) {
        $dom = getNewDom();

        if (isEqualToAnyWord("parentid sections_id", $columnInfo["name"])) {
            $control = getSelectmenuBasedOnArray($dom, $columnInfo["name"]);
        } else {
            $control = $dom->createElement("input");

            switch ($columnInfo["type"]) {
                case "int":
                    $control->setAttribute("type", "number");
                break;
                case "varchar":
                    $control->setAttribute("type", "text");
                break;
                case "tinyint":
                    $control->setAttribute("value", "1");
                    $control->setAttribute("checked", "");
                    $control->setAttribute("type", "checkbox");

                    // ako polje nije cekirano, nece se proslediti nista u POST te
                    // cu imati nedefinisanu promenljivu. ovako ce se proslediti 0
                    $hidden = $dom->createElement("input");
                    $hidden->setAttribute("value", "0");
                    $hidden->setAttribute("type", "hidden");
                    $hidden->setAttribute("name", $columnInfo["name"]);
                    $dom->appendChild($hidden);
                break;
            }

            if ($columnInfo["name"] === "position" || hasString($columnInfo["extra"] ?? "", "auto_increment")) {
                $control->setAttribute("disabled", "disabled");
            }
        }

        if ($columnInfo["is_nullable"] === "NO" && $columnInfo["type"] !== "tinyint") {
            $control->setAttribute("required", "");
        }

        $control->setAttribute("name", $columnInfo["name"]);
        $dom->appendChild($control);

        return $dom->saveHTML();
    }

    function getSelectmenuBasedOnArray($dom, $columnName) {
        $selectMenu = $dom->createElement("select");

        // placeholder
        $option = $dom->createElement("option", "");
        $option->setAttribute("value", "");
        $selectMenu->appendChild($option);

        if ($columnName === "parentid") {
            $rows = qGetAllForums(true);
        } else {
            $rows = qGetRowsByTableName("sections");
            $selectMenu->setAttribute("required", "");
        }

        foreach ($rows ?? [] as $row) {
            $option = $dom->createElement("option", "{$row["id"]} ({$row["title"]})");
            $option->setAttribute("value", $row["id"]);
            $selectMenu->appendChild($option);
        }

        return $selectMenu;
    }

    function calculateForumVisibilityValue($row) {
        $reason = "Tako kako je podešeno.";

        if ($row["visibility"] === "1") {
            $visible = "visible";

            // ako sekcija nije vidljiva, nije ni forum
            if ($section = qGetRowById($row["sections_id"], "sections")) {
                if ($section["visible"] !== "1") {
                    $reason = "Sekcija nije vidljiva.";
                    $visible = "invisible";
                }
            }

            // ako roditeljski forum nije vidljiv, nije ni ovaj forum
            if ($parentForum = qGetRowById($row["parentid"], "forums")) {
                if ($parentForum["visible"] !== "1") {
                    $reason = "Roditeljski forum nije vidljiv.";
                    $visible = "invisible";
                }
            }
        } else {
            $visible = "invisible";
        }

        return [
            "value" => $visible,
            "reason" => $reason
        ];
    }
