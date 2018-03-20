<?php
    include __DIR__ . "/header.php";

    if (!isset($_SESSION["redirect"])) {
        redirectTo("index.php");
    }

    $data = $_SESSION["redirect"];

    $url = $data["url"];
    $message = $data["message"];

    $title = $url;
    if (strpos($url, "index")) {
        $title = "Početna strana";
    } elseif (strpos($url, "login")) {
        $title = "Prijavi se";
    }
?>

<main>
    <p><?=$message?></p>
    <p>Klikni na link ako te automatski ne prebacimo tamo: <a href="<?=$url?>"><?=$title?></a></p>
</main>

<script>
    $(function() {
        setTimeout("window.location.href='<?=$url?>';", <?=REDIRECT_TIMEOUT?>);
    });
</script>

<?php
    unset($_SESSION["redirect"]);
    include __DIR__ . "/footer.php";
?>
