<?php
    if (isset($_POST["tellafriend"])) {
        $username = qGetUsernameById($_SESSION["userId"]);
        $subject = FORUM_NAME . ": Poziv za učlanjenje ({$username})";
        sendEmail($_POST["email"], $subject, $_POST["content"]);

        redirectBack("Poziv za učlanjenje je uspešno poslat.");
    }
?>

<?php if (isset($_SESSION["userId"])): ?>
    <section class="sidebar-tellafriend">

        <h2 data-shclass="sidebar-title" class="title">Pozovi prijatelja</h2>

        <div data-shclass="sidebar-content" class="content">

            <form method="post" action="">
                <p>
                    <label>
                        Email adresa tvog prijatelja:
                        <input type="email" name="email">
                    </label>
                </p>

                <p>
                    <label>
                        Poruka:<br>
                        <textarea name="content"><?php
                            echo "Dođi da vidiš kako strava forum!!! Pozz..." . PHP_EOL;
                            echo "http://{$_SERVER["SERVER_NAME"]}/";
                        ?></textarea>
                    </label>
                </p>

                <p>
                    <small>Ne skladištimo ove podatke.</small>
                </p>

                <p>
                    <button type="submit" name="tellafriend">Pošalji</button>
                </p>
            </form>

        </div>

    </section>
<?php endif; ?>