<?php if (isset($_SESSION["userId"])): ?>
    <?php $username = qGetUsernameById($_SESSION["userId"]); ?>

    <?php if ($username): ?>

        <section class="sidebar-welcome">

            <h2 data-shclass="sidebar-title" class="title">Dobro došao <?=$username?>!</h2>

            <div data-shclass="sidebar-content" class="content">
                <?php if (isset($_SESSION["lastVisitDT"])): ?>
                    <p>
                        Poslednji put si posetio forum<br>
                        <b><?=convertMysqlDatetimeToPhpDatetime($_SESSION["lastVisitDT"])?></b>.
                    </p>
                <?php endif; ?>
                <p>Sada je <?=getPHPDateTime()?>.</p>
            </div>

        </section>

    <?php endif; ?>

<?php endif; ?>
