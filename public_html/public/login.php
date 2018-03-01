<?php
    if (isset($_SESSION["user_id"])) {
        redirectTo("index.php");
    }

    include "header.php";

    if (isset($_POST["login"])) {
        $errors = [];

        if ($userId = qLoginUser($_POST["username"], $_POST["password"])) {
            if ($emailConfirmed = qIsEmailConfirmed($userId)) {
                $_SESSION["user_id"] = $userId;
                $_SESSION["redirect"] = [
                    "url" => "index.php",
                    "message" => "Uspešno ste se prijavili."
                ];
                redirectTo("redirect.php");
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

    <?php if (isset($errors)): ?>
        <div>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?=$error?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form class="logreg" method="post" action="">

        <p><label>
                Korisničko ime:<br>
                <input class="equal-width" type="text" name="username" required>
                <span class="required-star">*</span>
        </label></p>

        <p><label>
                Lozinka:<br>
                <input class="equal-width" type="password" name="password" required>
                <span class="required-star">*</span>
        </label></p>

        <button type="submit" name="login">Prijavi se</button>

    </form>

    <p><a href="#" id="forgot-link">Zaboravio sam podatke!</a></p>

    <form class="logreg" method="post" action="" style="display:none;" id="forgot">
        <p>Šta ste zaboravili?</p>

        <p>
            &nbsp;&nbsp;<label><input type="radio" name="what" value="username"> korisničko ime</label><br>
            &nbsp;&nbsp;<label><input type="radio" name="what" value="password" checked> lozinku (dobićete novu)</label>
        </p>

        <p><label>
                Vaša email adresa:<br>
                <input class="equal-width" type="email" name="email" required>
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

<?php include "footer.php"; ?>
