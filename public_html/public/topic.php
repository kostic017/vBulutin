<?php include "header.php"; ?>

<main>
    <?php include "topbox.php"; ?>

    <div data-shclass="captionbar" class="captionbar">1 reply to this topic</div>

    <section data-shclass="post-container" class="post-container">

        <div data-shclass="post-box" class="post-box">

            <div class="post-head">
                <h2 data-shclass="post-title" class="title"><?=$thisPage["title"]?></h2>
            </div>

            <div data-shclass="post-body" class="post-body">

                <div data-shclass="post-user" class="post-user">
                    <p class="name"><a href="">UserName</a></p>
                    <p class="avatar"><a href=""><img src="/public/images/avatars/default.png" alt="UserName"></a></p>
                    <p data-shclass="user-title user-admin"><a href="sviadmini"><span>Administrators</span></a></p>
                    <p class="titleimg"><a href="sviadmini"><img src="" alt="Administrator"></a></p>
                    <p class="postcount"><span>5 posts</span></p>
                </div>

                <div class="post-main">

                    <div class="content">
                        <small>Napisano 8. februara 2015.</small>
                        <p>Zdravo...<br>Pozzzz...</p>
                    </div>

                    <ul class="buttons">
                        <li><a href="">Prijavi</a></li>
                        <li><a href="">Zakači</a></li>
                        <li><a href="">Izmeni</a></li>
                        <li><a href="">Višestruko citiranje</a></li>
                        <li><a href="">Citiraj</a></li>
                    </ul>
                </div>

            </div>

        </div>

    </section>

    <section class="new-post">
        <form action="" method="post">
            <textarea name="post-content"></textarea>
            <button type="submit" name="new-post">Pošalji odgovor</button>
        </form>
    </section>

    <?php include "permissions.php"; ?>

</main>

<script>
    $(function() {
        sceditor.create($(".new-post textarea")[0], {
            width: "100%",
            height: "350px",
            format: "bbcode",
            resizeWidth: false,
            emoticonsRoot: "/shared/libraries/sceditor/",
            style: "/shared/libraries/sceditor/minified/themes/content/default.min.css"
        });
    });
</script>

<?php include "footer.php"; ?>
