<?php
    $rows = qGetRowsByTableName($tableName);
    $columnsInfo = qGetColumnsInfo($tableName);
?>

<table class="table-fill" data-name="<?=$tableName?>">

    <tr>
        <th>
            <button type="submit" class="icon icon-clear" title="Clear"
                    name="clear" value="<?=$tableName?>" formnovalidate></button>
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
            <button type="submit" class="icon icon-insert"
                    title="Insert" name="insert" value="<?=$tableName?>"></button>
        </td>
        <?php foreach ($columnsInfo as $columnInfo): ?>
            <td><?=getInsertControlForColumn($columnInfo)?></td>
        <?php endforeach; ?>
    </tr>


    <?php foreach ($rows ?? [] as $row): ?>
        <tr data-id="<?=$row["id"]?>">

            <td>
                <button type="submit" class="icon icon-update" title="Update"
                        name="update" value="<?=$tableName . "_" . $row["id"]?>" formnovalidate></button>
                <button type="submit" class="icon icon-delete" title="Delete"
                        name="delete" value="<?=$tableName . "_" . $row["id"]?>" formnovalidate></button>
            </td>

            <?php require "datarow.php"; ?>

        </tr>
    <?php endforeach; ?>

</table>
