<?php
    require_once "../shared/scripts/php/main.php";
    require_once "scripts/php/includes.php";

    if (FILENAME !== "login") {
        $_SESSION["redirect_back"] = $_SERVER["REQUEST_URI"];
    }

    if (isset($_POST["logout"])) {
        unset($_SESSION["user_id"]);
        unset($_SESSION["lastVisitDT"]);
    }
?>
<?php require_once "../shared/templates/header.php"; ?>
<link rel="stylesheet" href="schemes/scheme.css.php">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/emojione@3.1.2/extras/css/emojione.min.css"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/emojionearea@3.4.1/dist/emojionearea.min.css">
<link rel="stylesheet" href="/shared/libraries/inscryb-markdown-editor/inscrybmde.min.css">

<script src="https://cdn.jsdelivr.net/npm/emojionearea@3.4.1/dist/emojionearea.min.js"></script>
<script src="/shared/libraries/inscryb-markdown-editor/inscrybmde.min.js"></script>

<script src="scripts/js/script.js"></script>
</head>

<?php
    if (isEqualToAnyWord("topic forum section", FILENAME)) {
        if (!isNotBlank($thisPageId = $_GET["id"] ?? "")) {
            redirectTo("index.php");
        }
    }
?>

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
                        <li><a href="#" id="logout">Odjavi se</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Prijavi se</a></li>
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
            $("#logout").on("click", function () {
                $("#logout-form").submit();
            });
        </script>

        <div class="content-container">
