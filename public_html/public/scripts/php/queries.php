<?php

    function qCreateNewPost($topicId, $userId, $content) {
        $topicId = dbEscape($topicId);
        $userId = dbEscape($userId);
        $posted = getDatetime();
//        $content = dbEscape($content);

        $sql = "INSERT INTO posts (id, content, posted, topics_id, users_id) VALUES (";
        $sql .= "NULL, '{$content}', '{$posted}', '{$topicId}', '{$userId}'";
        $sql .= ")";

        executeQuery($sql);
    }

    function qGetPostsByTopicId($id, $sort = ["posted" => "ASC"]) {
        $id = dbEscape($id);

        $sql = "SELECT * ";
        $sql .= "FROM posts ";
        $sql .= "WHERE topics_id='{$id}'";
        $sql .= orderByStatement($sort);

        return executeAndFetchAssoc($sql, FETCH::ALL);
    }

    function qGetUserByPostId($id) {
        $id = dbEscape($id);

        $sql = "SELECT * ";
        $sql .= "FROM users ";
        $sql .= "WHERE id='{$id}' ";

        return executeAndFetchAssoc($sql);
    }

    function qCheckPasswordForEmail($email, $password) {
        $email = dbEscape($email);
        $password = hashPassword($password);

        $sql = "SELECT id ";
        $sql .= "FROM users ";
        $sql .= "WHERE email='{$email}' ";
        $sql .= "   AND password='{$password}' ";

        return isThereAResult($sql);
    }

    function qConfirmEmail($email) {
        $email = dbEscape($email);

        $sql = "SELECT id ";
        $sql .= "FROM users ";
        $sql .= "WHERE email='{$email}' ";
        $sql .= "   AND emailConfirmed='0' ";

        if (isNotBlank($userId = executeAndFetchAssoc($sql)["id"] ?? "")) {
            $sql = "UPDATE users ";
            $sql .= "SET emailConfirmed='1' ";
            $sql .= "WHERE id='{$userId}'";
            executeQuery($sql);
        }
    }

    function qLoginUser($username, $password) {
        $username = dbEscape($username);
        $password = hashPassword($password);

        $sql = "SELECT id ";
        $sql .= "FROM users ";
        $sql .= "WHERE username='{$username}' ";
        $sql .= "   AND password='{$password}' ";

        return executeAndFetchAssoc($sql)["id"] ?? false;
    }

    function qRegisterUser($data) {
        $username = dbEscape($data["username"]);
        $password = hashPassword($data["password1"]);
        $email = dbEscape($data["email"]);

//        $sex = isNotBlank($data["sex"]) ? q(dbEscape($data["sex"])) : "NULL";
//        $birthdate = isNotBlank($data["birthdate"]) ? q(dbEscape($data["birthdate"])) : "NULL";
//        $avatar = isNotBlank($data["avatar"]) ? q(dbEscape($data["avatar"])) : "NULL";

        $joinedDate = date("Y-m-d");

        $sql = "INSERT INTO users (id, username, password, email, joinedDate, emailConfirmed) VALUES (";
        $sql .= "   NULL, '{$username}', '{$password}', '{$email}', '{$joinedDate}', '0'";
        $sql .= ")";

        executeQuery($sql);
    }

    function qGetUserIdByEmail($email) {
        $email = dbEscape($email);

        $sql = "SELECT id ";
        $sql .= "FROM users ";
        $sql .= "WHERE email='{$email}' ";

        return executeAndFetchAssoc($sql)["id"];
    }

    function qSetNewPassword($userId, $password) {
        $userId = dbEscape($userId);
        $password = hashPassword($password);

        $sql = "UPDATE users ";
        $sql .= "SET password='{$password}' ";
        $sql .= "WHERE id='{$userId}' ";

        executeQuery($sql);
    }

    function qGetUsernameByEmail($email) {
        $email = dbEscape($email);

        $sql = "SELECT username ";
        $sql .= "FROM users ";
        $sql .= "WHERE email='{$email}' ";

        return executeAndFetchAssoc($sql)["username"] ?? "";
    }

    function qGetUserEmailById($userId) {
        $userId = dbEscape($userId);

        $sql = "SELECT email ";
        $sql .= "FROM users ";
        $sql .= "WHERE id='{$userId}' ";

        return executeAndFetchAssoc($sql)["email"];
    }

    function qIsEmailConfirmed($userId) {
        $userId = dbEscape($userId);

        $sql = "SELECT emailConfirmed ";
        $sql .= "FROM users ";
        $sql .= "WHERE id='{$userId}' ";

        return executeAndFetchAssoc($sql)["emailConfirmed"] === "1";
    }

    function qIsEmailTaken($email) {
        $email = dbEscape($email);

        $sql = "SELECT id ";
        $sql .= "FROM users ";
        $sql .= "WHERE email='{$email}' ";

        return isThereAResult($sql);
    }

    function qIsUsernameTaken($username) {
        $username = dbEscape($username);

        $sql = "SELECT id ";
        $sql .= "FROM users ";
        $sql .= "WHERE username='{$username}' ";

        return isThereAResult($sql);
    }

    function qGetTopicsByForumId($id, $sort = ["started" => "ASC"]) {
        $id = dbEscape($id);

        $sql = "SELECT * ";
        $sql .= "FROM topics ";
        $sql .= "WHERE forums_id='{$id}' ";
        $sql .= orderByStatement($sort);

        return executeAndFetchAssoc($sql, FETCH::ALL);
    }

    function qCountPostsInTopic($topicId) {
        $topicId = dbEscape($topicId);

        $sql = "SELECT COUNT(*) AS count ";
        $sql .= "FROM posts ";
        $sql .= "WHERE topics_id='{$topicId}' ";

        return executeAndFetchAssoc($sql)["count"];
    }

    function qCountTopicsInForum($forumId) {
        $forumId = dbEscape($forumId);

        $sql = "SELECT COUNT(*) AS count ";
        $sql .= "FROM topics ";
        $sql .= "WHERE forums_id='{$forumId}' ";

        return executeAndFetchAssoc($sql)["count"];
    }

    function qCountPostsInForum($forumId) {
        $count = 0;
        $topics = qGetTopicsByForumId($forumId);
        foreach ($topics as $topic) {
            $count += qCountPostsInTopic($topic["id"]);
        }
        return $count;
    }

    function qCountTopicsInRootForum($forumId) {
        $count = qCountTopicsInForum($forumId);
        $childForums = qGetForumsByParentId($forumId);
        foreach ($childForums as $childForum) {
            $count += qCountTopicsInForum($childForum["id"]);
        }
        return $count;
    }

    function qCountPostsInRootForum($forumId) {
        $count = qCountPostsInForum($forumId);
        $childForums = qGetForumsByParentId($forumId);
        foreach ($childForums as $childForum) {
            $count += qCountPostsInForum($childForum["id"]);
        }
        return $count;
    }

    function qGetTopicStarterUsername($firstPostId) {
        $firstPostId = dbEscape($firstPostId);

        $sql = "SELECT users_id ";
        $sql .= "FROM posts ";
        $sql .= "WHERE id='{$firstPostId}' ";

        $userId = executeAndFetchAssoc($sql)["users_id"];

        $sql = "SELECT username ";
        $sql .= "FROM users ";
        $sql .= "WHERE id='{$userId}' ";

        return executeAndFetchAssoc($sql)["username"];
    }

    function qGetTopicLastPosterUsername($topicId) {
        $topicId = dbEscape($topicId);

        $sql = "SELECT users_id ";
        $sql .= "FROM posts ";
        $sql .= "WHERE topics_id='{$topicId}' ";
        $sql .= "AND posted=( ";
        $sql .= "   SELECT MAX(posted) ";
        $sql .= "   FROM posts ";
        $sql .= "   WHERE topics_id='{$topicId}' ";
        $sql .= ") ";

        $userId = executeAndFetchAssoc($sql)["users_id"];

        $sql = "SELECT username ";
        $sql .= "FROM users ";
        $sql .= "WHERE id='{$userId}' ";

        return executeAndFetchAssoc($sql)["username"];
    }
