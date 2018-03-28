<?php
    if (isset($_SESSION["userId"])) {
        redirectTo("index.php");
    }

    require_once __DIR__ . "/header.php";

    if (isset($_POST["submit"])) {
        $errors = [];

        if (qIsUsernameTaken($_POST["username"])) {
            $errors[] = "Ovo korisničko ime je zauzeto.";
        }

        if (qIsEmailTaken($_POST["email"])) {
            $errors[] = "Već je registrovan korisnik sa ovim emajlom.";
        }

        if ($_POST["password1"] !== $_POST["password2"]) {
            $errors[] = "Lozinke se ne poklapaju.";
        }

        $url = "https://www.google.com/recaptcha/api/siteverify?";
        $url .= "secret=6LeIdEgUAAAAANxBIucs9i1w2gwwyaeqWv0ZoMfc&";
        $url .= "response={$_POST["g-recaptcha-response"]}&";
        $url .= "remoteip={$_SERVER["REMOTE_ADDR"]}";
        $captchaResult = json_decode(file_get_contents($url));

        if (!$captchaResult->success) {
            $errors[] = "Niste potvrdili da ste ljudsko biće.";
        }

        if (!isset($_POST["rules"])) {
            $errors[] = "Niste potvrdili da ste pročitali i da se slažete sa pravilima.";
        }

        if (empty($errors)) {
            $token = qRegisterUser($_POST["username"], $_POST["email"], $_POST["password1"]);
            sendEmailConfirmation($_POST["email"], $token);

            $message = "Nalog ti je napravljen ali prvo moraš da potvrdiš svoju email adresu. ";
            $message .= "Poslali smo ti mejl na <b>{$_POST["email"]}</b>.";

            $_SESSION["redirect"] = [
                "url" => "login.php",
                "message" => $message
            ];

            redirectTo("redirect.php");
        }
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

    <div class="rules">
        <h2>Pravila</h2>
        <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
            dolore
            magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
            commodo
            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
            pariatur.
            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est
            laborum.
        </p>
        <p>
            Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem
            aperiam,
            eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim
            ipsam
            voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui
            ratione
            voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur,
            adipisci velit,
            sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut
            enim ad minima
            veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi
            consequatur? Quis
            autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum
            qui dolore
            eum fugiat quo voluptas nulla pariatur?
        </p>
    </div>

    <form class="inputform" method="post" action="">

        <p><label>
            Korisničko ime:<br>
            <input type="text" name="username" required value="<?=$_POST["username"] ?? ""?>">
            <span class="required-star">*</span>
        </label></p>

        <p><label>
            E-mail:<br>
            <input type="email" name="email" required value="<?=$_POST["email"] ?? ""?>">
            <span class="required-star">*</span>
        </label></p>

        <p><label>
            Lozinka:<br>
            <input type="password" name="password1" required> <span class="required-star">*</span>
        </label></p>

        <p><label>
            Ponovi lozinku:<br>
            <input type="password" name="password2" required> <span class="required-star">*</span>
        </label></p>

        <div class="g-recaptcha" data-sitekey="6LeIdEgUAAAAAEsVfuW9Ts9hRtGxvJaiZniLhwcA"></div>

        <p><label>
            <input type="checkbox" name="rules" required">
            Pročitao sam i prihvatam pravila.
            <span class="required-star">*</span>
        </label></p>

        <button type="submit" name="submit">Registruj se</button>

    </form>

</main>

<?php require_once "footer.php"; ?>
