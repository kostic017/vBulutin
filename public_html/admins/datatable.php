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
                    <?=$columnInfo["name"]?>
                </a>
                <span class="icon icon-sort"></span>
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
        <?php require "datarow.php"; ?>
    <?php endforeach; ?>

</table>
