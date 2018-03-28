<?php
    require_once "header.php";

    if (!$profileId = $_GET["id"] ?? null) {
        redirectTo("index.php");
    }

    if (!isset($_SESSION["userId"])) {
        redirectBack("Morate biti prijavljeni da bi videli profile članova.");
    }

    $profile = qGetRowById($profileId, "users");

    function selectSex($value) {
        global $profile;
        if (isNotBlank($profile["sex"])) {
            if ($value === $profile["sex"]) {
                echo "selected";
            }
        }
    }
?>

<main>

    <?php if ($profileId === $_SESSION["userId"]): ?>

        <p><label>
            Korisničko ime:<br>
            <input type="text" disabled value="<?=$profile["username"]?>" title="Samo administrator može da promeni.">
        </label></p>

        <form action="" method="post" class="inputform">
            <fieldset>
                <p><label>
                    <input type="checkbox" <?=$profile["invisible"] === "1" ? "checked" : ""?>>
                    hoću da sam nevidljiv<br>
                    <input type="hidden" name="invisible" value="0">
                </label></p>
                <button type="submit" name="change-invisible">Promeni</button>
            </fieldset>
        </form>

        <form action="" method="post" class="inputform">
            <fieldset>
                <p><label>
                    E-mail:<br>
                    <input type="email" name="email" required value="<?=$profile["email"]?>">
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
                    <input type="text" name="avatar" value="<?=$profile["avatar"]?>">
                    <?php if (isNotBlank($profile["avatar"])): ?>
                        <img src="<?=$profile["avatar"]?>" alt="<?=$profile["username"]?>">
                    <?php endif; ?>
                </label></p>

                <button type="submit" name="change-data">Promeni</button>
            </fieldset>
        </form>

    <?php else: ?>

    <?php endif; ?>
</main>

<?php require_once "footer.php"; ?>
