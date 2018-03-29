<?php
    if ($isSidebarSet) {
        echo "<aside>";
        foreach ($configs->sidebar as $sidebarItem) {
            require_once "sidebar/{$sidebarItem}.php";
        }
        echo "</aside>";
    }
?>
                </div>

                <footer>

                    <?php if (FILENAME === "index"): ?>
                        <section data-shclass="forum-stats" class="forum-stats">
                            <ul>
                                <li>
                                    <strong data-shclass="stats-data"><?=qCountTableRows("posts")?></strong>
                                    poruka/e
                                </li>
                                <li>
                                    <strong data-shclass="stats-data"><?=qCountTableRows("users")?></strong>
                                    član(ova)
                                </li>
                                <li>
                                    <strong data-shclass="stats-data">
                                        <?php if ($newestUser = qGetNewestUser()): ?>
                                            <a href=""><?=$newestUser["username"]?></a>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </strong>
                                    najnoviji član
                                </li>
                                <li>
                                    <strong data-shclass="stats-data">2</strong>
                                    najviše osoba na mreži
                                </li>
                            </ul>
                        </section>
                    <?php endif; ?>

                    <section data-shclass="online-users" class="online-users">
                        <?php $onlineUsers = qGetOnlineUsers(); ?>

                        <div class="info">
                            <div>
                                <strong>2 korisnika su na mreži (ne ažurira se istog trena)</strong><br>
                                <small><?=count($onlineUsers)?> član, 1 gost, 0 anonimnih korisnika <a href="">(Pogledaj celu listu)</a></small>
                            </div>
                            <ul>
                                <li><a href="">Moderatorski tim</a></li>
                            </ul>
                        </div>

                        <ul data-shclass="online-users-list" class="list">
                            <?php foreach ($onlineUsers as $onlineUser): ?>
                                <li><a href=""><?=$onlineUser["username"]?></a></li>
                            <?php endforeach; ?>
                            <!-- <li data-shclass="user-admin"><a href="">Kostić</a></li> -->
                        </ul>

                        <div class="chat">
                            <strong>Članovi aktivni na četu</strong><br>
                            <small>Trenutno niko nije aktivan.</small>
                        </div>

                    </section>

                    <section class="footer-links">
                        <div>
                            <a href=""><img src="/public/images/icon-rss.png" alt="RSS" style="width:14px;height:14px;"></a>
                            <ul>
                                <li><a href="javascript:void(0)" id="btn-open-editor" data-active="0">Promeni izgled</a></li>
                                <li><a href="">Označi sve kao pročitano</a></li>
                                <li><a href="">Pomoć</a></li>
                            </ul>
                        </div>
                        <div>Copyright &copy; Nikola Kostić 2017.</div>
                    </section>

                </footer>

            </div>

        </div>

        <div data-shclass="btn-back2top" id="btn-back2top">
            <span>Povratak na vrh</span>
        </div>

        <?php // require_once "sheditor.php"; ?>

    </body>

</html>
