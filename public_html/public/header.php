<?php
    require_once "../shared/scripts/php/main.php";
    require_once "scripts/php/includes.php";

    if (isset($_POST["logout"])) {
        unset($_SESSION["user_id"]);
    }

    if (isEqualToAnyWord("topic forum section", FILENAME)) {
        if (!isNotBlank($id = $_GET["id"] ?? "")) {
            redirectTo("index.php");
        }
    }
?>
<?php require_once "../shared/templates/header.php"; ?>
    <link rel="stylesheet" href="schemes/scheme.css">
    <link rel="stylesheet" href="/shared/libraries/emojione/emojione.min.css">
    <link rel="stylesheet" href="/shared/libraries/inscryb-markdown-editor/inscrybmde.min.css">
    <link rel="stylesheet" href="/shared/libraries/emojionearea/emojionearea.min.css">

    <script src="/shared/libraries/emojionearea/emojionearea.min.js"></script>
    <script src="/shared/libraries/inscryb-markdown-editor/inscrybmde.min.js"></script>
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
                    <?php if (isset($_SESSION["user_id"])): ?>
                        <li><a href="" id="btn-messages"><span data-newmessages="0">Nema novih poruka</span></a></li>
                        <li><a href="" id="btn-profile"><span>Moj profil</span></a></li>
                        <li> <a href="#" id="logout">Odjavi se</a></li>
                    <?php else: ?>
                        <li><a href="login.php?page=<?=$_SERVER["REQUEST_URI"]?>">Prijavi se</a></li>
                        <li><a href="register.php">Registruj se</a></li>
                    <?php endif; ?>
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

        <form id="logout-form" method="post" action="">
            <input type="hidden" name="logout" value="">
        </form>

        <script>
            $("#logout").on("click", function() {
                $("#logout-form").submit();
            });
        </script>

        <div class="content-container">
