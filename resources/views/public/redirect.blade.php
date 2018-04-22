<?php
    require_once __DIR__ . "/header.php";

    if (!isset($_SESSION["redirect"])) {
        redirectTo("index.php");
    }

    $url = $_SESSION["redirect"]["url"];
    $message = $_SESSION["redirect"]["message"];

    $title = $url;
    if (hasSubstring($url, "index") || $url == "/public/") {
        $title = "Početna strana";
    } elseif (hasSubstring($url, "login")) {
        $title = "Prijavi se";
    } elseif (hasSubstring($url, "register")) {
        $title = "Registruj se.";
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
