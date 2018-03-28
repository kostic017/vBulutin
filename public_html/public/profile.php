<?php
    require_once "header.php";

    if (!$profileId = $_GET["id"] ?? null) {
        redirectTo("index.php");
    }

    if (!isset($_SESSION["userId"])) {
        redirectBack("Morate biti prijavljeni da bi videli profile članova.");
    }

    $errors = [];
    $profile = qGetRowById($profileId, "users");

    function selectSex($value) {
        global $profile;
        if (isNotBlank($profile["sex"])) {
            if ($value === $profile["sex"]) {
                echo "selected";
            }
        }
    }

    if (isset($_POST["change-invisible"])) {
        qUpdateUserInvisibility($_SESSION["userId"], $_POST["invisible"]);

        $_SESSION["redirect"] = [
            "url" => $_SERVER["REQUEST_URI"],
            "message" => "Podešavanja vidljivosti su promenjena."
        ];

        redirectTo("redirect.php");
    }

    if (isset($_POST["change-email"])) {
        if (qIsEmailTaken($_POST["email"])) {
            $errors[] = "Već je registrovan korisnik sa ovim emajlom.";
        } else {
            $token = qUpdateUserEmail($_SESSION["userId"], $_POST["email"]);
            sendEmailConfirmation($_POST["email"], $token);

            $message = "Promenili smo ti email adresu ali prvo moraš da je potvrdiš. ";
            $message .= "Poslali smo ti mejl na <b>{$_POST["email"]}</b>.";

            $_SESSION["redirect"] = [
                "url" => "login.php",
                "message" => $message
            ];

            qLogoutUser($_SESSION["userId"]);
            redirectTo("redirect.php");
        }
    }

    if (isset($_POST["change-password"])) {
        if ($_POST["password1"] !== $_POST["password2"]) {
            $errors[] = "Lozinke se ne poklapaju.";
        } else {
            qUpdateUserPassword($_SESSION["userId"], $_POST["password1"]);
            qLogoutUser($_SESSION["userId"]);

            $_SESSION["redirect"] = [
                "url" => "login.php",
                "message" => "Lozinka ti je promenjena, ali morćeš ponovo da se prijaviš."
            ];

            redirectTo("redirect.php");
        }
    }

    if (isset($_POST["change-data"])) {
        qUpdateUserData($_SESSION["userId"], $_POST["sex"], $_POST["birthdate"],
            $_POST["birthplace"], $_POST["residence"], $_POST["job"], $_POST["avatar"]);

        $_SESSION["redirect"] = [
            "url" => $_SERVER["REQUEST_URI"],
            "message" => "Podaci su ti promenjeni."
        ];

        redirectTo("redirect.php");
    }
?>

<main>

    <?php if ($profileId === $_SESSION["userId"]): ?>

        <?php printErrors($errors); ?>

        <p><label>
            Korisničko ime:<br>
            <input type="text" disabled value="<?=$profile["username"]?>" title="Samo administrator može da promeni.">
        </label></p>

        <form action="" method="post" class="inputform">
            <fieldset>
                <p><label>
                    <input type="hidden" name="invisible" value="0">
                    <input type="checkbox" name="invisible" <?=$profile["invisible"] === "1" ? "checked" : ""?> value="1">
                    hoću da sam nevidljiv<br>
                </label></p>
                <button type="submit" name="change-invisible">Promeni</button>
            </fieldset>
        </form>

        <form action="" method="post" class="inputform">
            <fieldset>
                <p><label>
                    E-mail:<br>
                    <input type="email" name="email" required value="<?=$profile["email"]?>"> <span class="required-star">*</span>
                </label></p>
                <button type="submit" name="change-email">Promeni</button>
            </fieldset>
        </form>

        <form action="" method="post" class="inputform">
            <fieldset>
                <p><label>
                    Lozinka:<br>
                    <input type="password" name="password1" required> <span class="required-star">*</span>
                </label></p>

                <p><label>
                    Ponovi lozinku:<br>
                    <input type="password" name="password2" required> <span class="required-star">*</span>
                </label></p>
                <button type="submit" name="change-password">Promeni</button>
            </fieldset>
        </form>


        <form action="" method="post" class="inputform">
            <fieldset>
                <p><label>
                    Pol:<br>
                    <select name="sex">
                        <option value="" <?php selectSex(""); ?>></option>
                        <option value="male" <?php selectSex("male"); ?>>Muški</option>
                        <option value="female" <?php selectSex("female"); ?>>Ženski</option>
                        <option value="shemale" <?php selectSex("shemale"); ?>>Trandža</option>
                    </select>
                </label></p>

                <p><label>
                    Datum rоđenja:<br>
                    <input type="date" name="birthdate" value="<?=$profile["birthdate"]?>">
                </label></p>

                <p><label>
                    Mesto rođenja:<br>
                    <input type="text" name="birthplace" value="<?=$profile["birthplace"]?>">
                </label></p>

                <p><label>
                    Mesto prebivanja:<br>
                    <input type="text" name="residence" value="<?=$profile["residence"]?>">
                </label></p>

                <p><label>
                    Zanimanje:<br>
                    <input type="text" name="job" value="<?=$profile["job"]?>">
                </label></p>

                <p><label>
                    Profilna slika:<br>
                    <?php if (isNotBlank($profile["avatar"])): ?>
                        <img src="<?=$profile["avatar"]?>" alt="<?=$profile["username"]?>" class="avatar avatar-medium">
                    <?php endif; ?>
                    <input type="text" name="avatar" value="<?=$profile["avatar"]?>" placeholder="URL do slike">
                </label></p>

                <button type="submit" name="change-data">Promeni</button>
            </fieldset>
        </form>

    <?php else: ?>

    <?php endif; ?>
</main>

<?php require_once "footer.php"; ?>
