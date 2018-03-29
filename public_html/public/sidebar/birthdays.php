<?php /*
<?php if (isset($_SESSION["userId"])): ?>
    <?php $birthdays = qGetBirthdays(); ?>
    <?php var_dump($birthdays); ?>
    <section class="sidebar-birthdays">

        <h2 data-shclass="sidebar-title" class="title">Rođendani</h2>

        <div data-shclass="sidebar-content" class="content">
            <?php if (empty($birthdays["today"])): ?>
                Nema rođendana danas.<br>
            <?php else: ?>
                <ul>
                    <?php foreach ($birthdays["today"] as $birthday): ?>
                        <li><?php echo "{$birthday["username"]} ({$birthday["years"]})"; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if (empty($birthdays["tomorrow"])): ?>
                Nema rođendana sutra.
            <?php else: ?>
                <ul>
                    <?php foreach ($birthdays["today"] as $birthday): ?>
                        <li><?php echo "{$birthday["username"]} ({$birthday["years"]})"; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

    </section>
<?php endif; ?>
*/