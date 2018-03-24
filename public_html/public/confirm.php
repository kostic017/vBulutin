<?php
    require_once __DIR__ . "/header.php";

    $errors = [];
    $username = "";
    $email = $_GET["email"] ?? "";

    if (isNotBlank($email) && qIsEmailTaken($email)) {
        $username = qGetUsernameByEmail($email);
        if (isset($_POST["confirm"])) {
            if (qCheckPasswordForEmail($email, $_POST["password"])) {
                qConfirmEmail($email);

                $_SESSION["redirect"] = [
                    "url" => "login.php",
                    "message" => "Uspešno ste potvrdili svoju email adresu."
                ];

                redirectTo("redirect.php");
            } else {
                $errors[] = "Pogrešna lozinka.";
            }
        }
    } else {
        redirectTo("register.php");
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

    <p>
        <b>Korisničko ime:</b> <?=$username?><br>
        <b>Email adresa:</b> <?=$email?>
    </p>

    <form class="logreg" method="post" action="">

        <p><label>
                Lozinka:<br>
                <input class="equal-width" type="password" name="password" required>
                <span class="required-star">*</span>
            </label></p>

        <button type="submit" name="confirm">Potvrdi email adresu</button>

    </form>

</main>

<?php require_once __DIR__ . "/footer.php"; ?>
