<?php
    $rows = qGetRowsByTableName($tableName);
    $columnsInfo = qGetColumnsInfo($tableName);
?>

<table class="table-fill" data-name="<?=$tableName?>">

    <tr>
        <th>
            <button value="" class="icon icon-clear" title="Clear" name="<?=$tableName?>_clear"></button>
        </th>
        <?php foreach ($columnsInfo as $columnInfo): ?>
            <th>
                <a href="javascript:void(0)" class="btn-sort" data-columnName="<?=$columnInfo["name"]?>">
                    <?=$columnInfo["name"]?> <span class="icon icon-sort"></span>
                </a>
            </th>
        <?php endforeach; ?>
    </tr>

    <tr class="insert-row">
        <td>
            <button value="" type="submit" class="icon icon-insert"
                    title="Insert" name="<?=$tableName?>_insert"></button>
        </td>
        <?php foreach ($columnsInfo as $columnInfo): ?>
            <td><?=getInsertControlForColumn($columnInfo)?></td>
        <?php endforeach; ?>
    </tr>


    <?php foreach ($rows ?? [] as $row): ?>
        <tr data-id="<?=$row["id"]?>">

            <td>
                <button value="<?=$row["id"]?>" type="submit" class="icon icon-update"
                        title="Update" name="<?=$tableName?>_update"></button>
                <button value="<?=$row["id"]?>" type="submit" class="icon icon-delete"
                        title="Delete" name="<?=$tableName?>_delete"></button>
            </td>

            <?php foreach ($row as $columnName => $value): ?>
                <td data-value="<?=$value?>">
                    <?php
                        echo $value;
                        switch ($columnName) {
                            case "visibility":
                                $visibility = calculateForumVisibilityValue($row);
                                echo "<span class='icon icon-{$visibility["value"]}' ";
                                echo "title='{$visibility["reason"]}'></span>";
                            break;
                            case "parentid":
                                echo $value;
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
                </td>
            <?php endforeach; ?>

        </tr>
    <?php endforeach; ?>

</table>
