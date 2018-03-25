<tr data-id="<?=$row["id"]?>">

    <td>
        <button type="button" class="icon icon-update" title="Update" name="edit" value="<?=$row["id"]?>" formnovalidate></button>
        <button type="submit" class="icon icon-delete" title="Delete" name="delete" value="<?=$row["id"]?>" formnovalidate></button>
    </td>

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
                        case "parentId":
                            if ($parent = qGetRowById($value, "forums")) {
                                echo " ({$parent["title"]})";
                            }
                        break;
                        case "sectionId":
                            if ($section = qGetRowById($value, "sections")) {
                                echo " ({$section["title"]})";
                            }
                        break;
                    }
                ?>
            </div>
        </td>
    <?php endforeach; ?>

</tr>
