<?php
    header("Content-type: text/css; charset=utf-8");

    $results = scandir(__DIR__);

    foreach ($results as $result) :
        if ($result === "." || $result === ".." || !is_dir(__DIR__ . "/" . $result)) {
            continue;
        }
?>
@font-face {
    font-style  : normal;
    font-weight : normal;
    font-family : "<?=$result?>";
    src         : url("/shared/fonts/<?=$result?>/Regular.woff2") format("woff2"),
    url("/shared/fonts/<?=$result?>/Regular.woff") format("woff");
}

@font-face {
    font-weight : bold;
    font-style  : normal;
    font-family : "<?=$result?>";
    src         : url("/shared/fonts/<?=$result?>/Bold.woff2") format("woff2"),
    url("/shared/fonts/<?=$result?>/Bold.woff") format("woff");
}

<?php endforeach; ?>
