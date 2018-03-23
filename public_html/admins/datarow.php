<?php foreach ($row as $columnName => $value): ?>
    <td data-value="<?=$value?>">
        <div>
            <?php
                echo $value;
                switch ($columnName) {
                    case "visibility":
                        $visibility = calculateForumVisibilityValue($row);
                        echo "<span class='icon icon-{$visibility["value"]}' ";
                        echo "title='{$visibility["reason"]}'></span>";
                    break;
                    case "parentid":
                        if ($parent = qGetRowById($value, "forums")) {
                            echo " ({$parent["title"]})";
                        }
                    break;
                    case "sections_id":
                        if ($section = qGetRowById($value, "sections")) {
                            echo " ({$section["title"]})";
                        }
                    break;
                }
            ?>
        </div>
    </td>
<?php endforeach; ?>
