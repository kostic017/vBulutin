<?php
    $rows = qGetRowsByTableName($tableName);
    $columnsInfo = qGetColumnsInfo($tableName);
?>

<table class="table-fill" data-name="<?=$tableName?>">

    <tr>
        <th>
            <button class="icon icon-clear" title="Clear" name="<?=$tableName?>_clear"></button>
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
            <button type="submit" class="icon icon-insert" title="Insert" name="<?=$tableName?>_insert"></button>
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

            <?php require "datarow.php"; ?>

        </tr>
    <?php endforeach; ?>

</table>

<script>
    $(function() {
        // $("button.icon-clear, button.icon-delete").on("click", () => confirm("Sigurno želite da izvršite brisanje?"));
        $("button.icon-clear, button.icon-delete").on("click", function() {
            return confirm("Sigurno želite da izvršite brisanje?");
        });
    });
</script>
