<?php
    require_once "header.php";

    if (isset($_SESSION["userId"])) {
        redirectTo($_SESSION["redirect_back"] ?? "index.php");
    }

    $errors = [];

    if (isset($_POST["login"])) {

        if ($user = qLoginUser($_POST["username"], $_POST["password"])) {
            if ($confirmed = qIsEmailConfirmedByUserId($user["id"])) {
                $_SESSION["userId"] = $user["id"];
                if (isset($user["lastVisitDT"])) {
                    $_SESSION["lastVisitDT"] = $user["lastVisitDT"];
                }

                redirectBack();
            } else {
                $errors[] = "Niste potvrdili svoju email adresu.";
            }
        } else {
            $errors[] = "Korisničko ime ili lozinka pogrešni.";
        }
    }

    if (isset($_POST["forgot"])) {
        $what = ($_POST["what"] === "username") ? "korisničko ime" : "lozinku";
        sendForgottedData($_POST["what"], $_POST["email"]);

        $_SESSION["redirect"] = [
            "url" => "login.php",
            "message" => "Poslali smo ti {$what}, proveri mejl.",
        ];

        redirectTo("redirect.php");
    }
?>

<main>

    <?php printErrors($errors); ?>

    <form class="inputform" method="post" action="">

        <p><label>
            Korisničko ime:<br>
            <input type="text" name="username" required>
            <span class="required-star">*</span>
        </label></p>

        <p><label>
                Lozinka:<br>
                <input type="password" name="password" required>
                <span class="required-star">*</span>
            </label></p>

        <button type="submit" name="login">Prijavi se</button>

    </form>

    <p><a href="#" id="forgot-link">Zaboravio sam podatke!</a></p>

    <form class="inputform" method="post" action="" style="display:none;" id="forgot">
        <p>Šta ste zaboravili?</p>

        <p>
            &nbsp;&nbsp;<label><input type="radio" name="what" value="username"> korisničko ime</label><br>
            &nbsp;&nbsp;<label><input type="radio" name="what" value="password" checked> lozinku (dobićete novu)</label>
        </p>

        <p><label>
            Vaša email adresa:<br>
            <input type="email" name="email" required>
            <span class="required-star">*</span>
        </label></p>

        <button type="submit" name="forgot">Pošalji</button>
    </form>

</main>

<script>
    $("#forgot-link").on("click", function () {
        $("#forgot").slideDown();
    });
</script>

<?php require_once "footer.php"; ?>
