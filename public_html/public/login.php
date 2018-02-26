<?php
    if (isset($_SESSION["user_id"])) {
        redirectTo("index.php");
    }
    
    include "header.php";
    
    if (isset($_POST["login"])) {
        $greske = [];
        
        if ($userId = qLoginUser($_POST)) {
            if ($emailConfirmed = qIsEmailConfirmed($userId)) {
                $_SESSION["user_id"] = $userId;
            } else {
                $greske[] = "Niste potvrdili svoju email adresu.";
                $userEmail = qGetUserEmail($userId);
            }
        } else {
            $greske[] = "Korisničko ime ili lozinka pogrešni.";
        }
    }
    
    if (isset($_POST["confirm"])) {
        $token = sendEmailConfirmation($_POST["email"]);
        qUpdateEmailAndToken($_POST["userId"], $_POST["email"], $token);
    }
?>

<main>

    <?php if (isset($greske)): ?>
        <div>
            <ul>
                <?php foreach ($greske as $greska): ?>
                    <li><?=$greska?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form class="logreg" method="post" action="">
        
        <label>
            Korisničko ime:<br>
            <input class="equal-width" type="text" name="username" required> <span class="required-star">*</span>
        </label>
        
        <label>
            Lozinka:<br>
            <input class="equal-width" type="password" name="password1" required> <span class="required-star">*</span>
        </label>
        
        <button type="submit" name="login">Prijavi se</button>
        
    </form>
    
    <?php if (isset($_POST["login"]) && !$emailConfirmed): ?>
        <form class="logreg" method="post" action="">
        
            <label>
                Vaša email adresa:
                <input class="equal-width" type="email" name="email" value="<?=$userEmail?>" required>  <span class="required-star">*</span>
            </label>
            
            <input type="hidden" name="userId" value="<?=$userId?>">
            <button type="submit" name="confirm">Ponovo pošalji mejl za potvrdu</button>
            
        </form>
    <?php endif; ?>
    
</main>

<?php include "footer.php"; ?>
