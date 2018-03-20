<?php
    require_once __DIR__ . "/header.php";

    if (!isset($_SESSION["redirect"])) {
        redirectTo("index.php");
    }

    $data = $_SESSION["redirect"];

    $url = $data["url"];
    $message = $data["message"];

    $title = $url;
    if (strpos($url, "index") || $url == "/public/") {
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
    $(function () {
        redirectTo("<?=$url?>", <?=REDIRECT_TIMEOUT?>);
    });
</script>

<?php
    unset($_SESSION["redirect"]);
    require_once __DIR__ . "/footer.php";
?>
