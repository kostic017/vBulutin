<?php require_once __DIR__ . "/scripts/php/includes.php"; ?>

<h2 class="table-title">
    Sekcije
</h2>

<p>Brisanjem sekcije brišete sve forume koje pripadaju toj sekciji kao i teme u tim forumima.</p>

<form method="post" action="">
    <?=getDataTable("sections")?>
</form>

<h2 class="table-title">
    Forumi
</h2>

<form method="post" action="">
    <?=getDataTable("forums")?>
</form>

<div id="overlay"></div>