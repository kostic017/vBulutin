<?php
    require_once "shared/scripts/php/main.php";
    
    if (isPostRequest()) {
        $posts = execAndFetchAssoc("SELECT * FROM posts", FETCH::ALL);
        foreach ($posts as $post) {
            executeQuery("UPDATE topics SET started='{$post["posted"]}', updated='{$post["posted"]}' WHERE id='{$post["topics_id"]}'");
        }
    }
?>

<form method="post">
    <input type="submit" value="Go">
</form>