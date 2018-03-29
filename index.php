<?php
declare(strict_types=1);

require_once __DIR__ . "/vendor/autoload.php";

$forumM = new Forum41\Models\ForumModel();
$postM = new Forum41\Models\PostModel();
$sectionM = new Forum41\Models\SectionModel();
$topicM = new Forum41\Models\TopicModel();
$userM = new Forum41\Models\UserModel();

try {
    var_dump($forumM->insert([
        "title" => "Novi 1",
        "description" => "ASdada",
        "visible" => '0',
        "parentId" => "",
        "sectionId" => ""
    ]));
} catch (Exception $e) {
    echo "Excepton: " . $e->getMessage();
}
