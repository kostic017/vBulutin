<?php require_once "scripts/php/temp.php"; ?>
<?php require_once "../shared/templates/header.php"; ?>
    <link rel="stylesheet" href="schemes/scheme.css">
    <script src="scripts/js/sheditor.js"></script>
</head>

<body <?=$isSidebarSet ? "class='sidebar'" : ""?>>

<div data-shclass="main-container">

    <div id="center-page-container">

        <header>

            <section class="header-top">

                <div class="logo">
                    <a href="index.php"></a>
                </div>

                <form data-shclass="search-form" class="search-form" name="search" action="search.php">
                    <input data-shclass="searchquery" class="searchquery" type="text" placeholder="Pretraga...">
                    <button data-shclass="searchbtn-submitsearch" class="searchbtn -submitsearch" type="submit">
                        Pretraži
                    </button>
                    <button data-shclass="searchbtn-customsearch" class="searchbtn -customsearch" type="button">Napredna
                        pretraga
                    </button>
                </form>

            </section>

            <section data-shclass="main-navigation" class="main-navigation">

                <ul data-shclass="nav-profile">
                    <li><a href="" id="btn-messages"><span data-newmessages="0">Nema novih poruka</span></a></li>
                    <li><a href="" id="btn-profile"><span>Moj profil</span></a></li>
                    <li><a href="">Odjavi se</a></li>
                </ul>

                <ul>
                    <?php foreach ($forumNavigation as $display => $filename): ?>
                        <li data-shclass="nav-forum-<?=(FILENAME === $filename) ? "active" : "nonactive"?>">
                            <a href="<?="{$filename}.php"?>"><?=$display?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>

            </section>

        </header>

        <div class="content-container">
