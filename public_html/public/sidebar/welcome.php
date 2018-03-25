<?php if (isset($_SESSION["user_id"])): ?>
    <?php $username = qGetUsernameById($_SESSION["user_id"]); ?>

    <?php if ($username): ?>

        <section class="sidebar sidebar-welcome">

            <h2 data-shclass="sidebar-title" class="title">Dobro došao <?=$username?>!</h2>

            <div data-shclass="sidebar-content" class="content">
                <?php if (isset($_SESSION["lastVisitDT"])): ?>
                    <p>
                        Poslednji put si posetio forum<br>
                        <b><?=convertMysqlDatetimeToPhpDatetime($_SESSION["lastVisitDT"])?></b>.
                    </p>
                <?php endif; ?>
                <p>Sada je <?=strftime("%d %B %Y - %H:%M")?>.</p>
            </div>

        </section>

    <?php endif; ?>

<?php endif; ?>
