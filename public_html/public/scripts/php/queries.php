<?php

    /// FORUMS ///

    function qCountTopicsInForum($forumId) {
        dbEscape($forumId);

        $sql = "SELECT COUNT(*) AS count ";
        $sql .= "FROM topics ";
        $sql .= "WHERE forums_id='{$forumId}' ";

        return executeAndFetchAssoc($sql)["count"];
    }

    function qCountPostsInForum($forumId) {
        $count = 0;
        if ($topics = qGetTopicsByForumId($forumId)) {
            foreach ($topics as $topic) {
                $count += qCountPostsInTopic($topic["id"]);
            }
        }
        return $count;
    }

    function qCountTopicsInRootForum($forumId) {
        $count = qCountTopicsInForum($forumId);
        if ($childForums = qGetForumsByParentId($forumId)) {
            foreach ($childForums as $childForum) {
                $count += qCountTopicsInForum($childForum["id"]);
            }
        }
        return $count;
    }

    function qCountPostsInRootForum($forumId) {
        $count = qCountPostsInForum($forumId);
        if ($childForums = qGetForumsByParentId($forumId)) {
            foreach ($childForums as $childForum) {
                $count += qCountPostsInForum($childForum["id"]);
            }
        }
        return $count;
    }

    /// TOPICS ///

    function qCreateNewTopic($forumId, $userId, $title, $content) {
        dbEscape($forumId, $title, $userId, $content);

        $sql = "INSERT INTO topics (id, title, forums_id) VALUES (";
        $sql .= "NULL, '{$title}', '{$forumId}'";
        $sql .= ")";

        executeQuery($sql);
        $topicId = getInsertId();

        $post = qCreateNewPost($topicId, $userId, $content);

        $sql = "UPDATE topics SET ";
        $sql .= "   firstpost_id = '{$post["id"]}', ";
        $sql .= "   started = '{$post["posted"]}', ";
        $sql .= "   updated = '{$post["posted"]}' ";
        $sql .= "WHERE id='{$topicId}' ";

        executeQuery($sql);
    }

    function qGetTopicsByForumId($id, $sort = ["updated" => "DESC"]) {
        dbEscape($id);

        $sql = "SELECT * ";
        $sql .= "FROM topics ";
        $sql .= "WHERE forums_id='{$id}' ";
        $sql .= orderByStatement($sort);

        return executeAndFetchAssoc($sql, FETCH::ALL);
    }

    function qGetTopicStarterUsername($topicId) {
        dbEscape($topicId);

        $sql = "SELECT firstpost_id ";
        $sql .= "FROM topics ";
        $sql .= "WHERE id='{$topicId}'";

        if ($forum = executeAndFetchAssoc($sql)) {
            $sql = "SELECT users_id ";
            $sql .= "FROM posts ";
            $sql .= "WHERE id='{$forum["id"]}' ";

            if ($post = executeAndFetchAssoc($sql)) {
                $sql = "SELECT username ";
                $sql .= "FROM users ";
                $sql .= "WHERE id='{$post["users_id"]}' ";

                if ($user = executeAndFetchAssoc($sql)) {
                    return $user["username"];
                }
            }
        }

        return null;
    }

    function qGetTopicLastPosterUsername($topicId) {
        dbEscape($topicId);

        $sql = "SELECT users_id ";
        $sql .= "FROM posts ";
        $sql .= "WHERE topics_id='{$topicId}' ";
        $sql .= "AND posted=( ";
        $sql .= "   SELECT MAX(posted) ";
        $sql .= "   FROM posts ";
        $sql .= "   WHERE topics_id='{$topicId}' ";
        $sql .= ") ";

        if ($post = executeAndFetchAssoc($sql)) {
            $sql = "SELECT username ";
            $sql .= "FROM users ";
            $sql .= "WHERE id='{$post["users_id"]}' ";

            if ($user = executeAndFetchAssoc($sql)) {
                return $user["username"];
            }
        }

        return null;
    }

    function qCountPostsInTopic($topicId) {
        dbEscape($topicId);

        $sql = "SELECT COUNT(*) AS count ";
        $sql .= "FROM posts ";
        $sql .= "WHERE topics_id='{$topicId}' ";

        return executeAndFetchAssoc($sql)["count"];
    }

    /// POSTS ///

    function qCreateNewPost($topicId, $userId, $content) {
        dbEscape($topicId, $userId, $content);
        $posted = getDatetimeForMysql();

        $sql = "INSERT INTO posts (id, content, posted, topics_id, users_id) VALUES (";
        $sql .= "NULL, '{$content}', '{$posted}', '{$topicId}', '{$userId}'";
        $sql .= ")";

        executeQuery($sql);

        return [
            "id" => getInsertId(),
            "posted" => $posted
        ];
    }

    function qGetPostsByTopicId($id, $sort = ["updated" => "DESC"]) {
        dbEscape($id);

        $sql = "SELECT * ";
        $sql .= "FROM posts ";
        $sql .= "WHERE topics_id='{$id}' ";
        $sql .= orderByStatement($sort);

        return executeAndFetchAssoc($sql, FETCH::ALL);
    }

    function qGetLastPostInfoByForumId($forumId) {
        dbEscape($forumId);

        $sql = "SELECT id, updated ";
        $sql .= "FROM topics ";
        $sql .= "WHERE forums_id='{$forumId}' ";
        $sql .= "ORDER BY updated DESC ";

        if ($lastlyUpdatedTopic = executeAndFetchAssoc($sql)) {
            if ($lastPosterUsername = qGetTopicLastPosterUsername($lastlyUpdatedTopic["id"])) {
                return [
                    "username" => $lastPosterUsername,
                    "date" => convertMysqlDatetimeToPhpDate($lastlyUpdatedTopic["updated"]),
                    "time" => convertMysqlDatetimeToPhpTime($lastlyUpdatedTopic["updated"])
                ];
            }
        }

        return null;
    }

    /// USERS ///

    function qGetUserById($userId) {
        dbEscape($userId);

        $sql = "SELECT * ";
        $sql .= "FROM users ";
        $sql .= "WHERE id='{$userId}' ";

        return executeAndFetchAssoc($sql);
    }

    function qLoginUser($username, $password) {
        dbEscape($username);
        $password = hashPassword($password);

        $sql = "SELECT id ";
        $sql .= "FROM users ";
        $sql .= "WHERE username='{$username}' ";
        $sql .= "   AND password='{$password}' ";

        if ($user = executeAndFetchAssoc($sql)) {
            return $user["id"];
        }

        return null;
    }

    function qRegisterUser($username, $email, $password) {
        dbEscape($username, $email);
        $password = hashPassword($password);
        $joinedDate = getDateForMysql();

        $sql = "INSERT INTO users (id, username, password, email, joinedDate, emailConfirmed) VALUES (";
        $sql .= "   NULL, '{$username}', '{$password}', '{$email}', '{$joinedDate}', '0'";
        $sql .= ")";

        executeQuery($sql);
    }

    function qCheckPasswordForEmail($email, $password) {
        dbEscape($email);
        $password = hashPassword($password);

        $sql = "SELECT id ";
        $sql .= "FROM users ";
        $sql .= "WHERE email='{$email}' ";
        $sql .= "   AND password='{$password}' ";

        return isThereAResult($sql);
    }

    function qConfirmEmail($email) {
        dbEscape($email);

        $sql = "SELECT id ";
        $sql .= "FROM users ";
        $sql .= "WHERE email='{$email}' ";
        $sql .= "   AND emailConfirmed='0' ";

        if ($user = executeAndFetchAssoc($sql)) {
            $sql = "UPDATE users ";
            $sql .= "SET emailConfirmed='1' ";
            $sql .= "WHERE id='{$user["id"]}' ";
            executeQuery($sql);
        }
    }

    function qGetUserIdByEmail($email) {
        dbEscape($email);

        $sql = "SELECT id ";
        $sql .= "FROM users ";
        $sql .= "WHERE email='{$email}' ";

        if ($user = executeAndFetchAssoc($sql)) {
            return $user["id"];
        }

        return null;
    }

    function qSetNewPassword($userId, $password) {
        dbEscape($userId);
        $password = hashPassword($password);

        $sql = "UPDATE users ";
        $sql .= "SET password='{$password}' ";
        $sql .= "WHERE id='{$userId}' ";

        executeQuery($sql);
    }

    function qGetUsernameByEmail($email) {
        dbEscape($email);

        $sql = "SELECT username ";
        $sql .= "FROM users ";
        $sql .= "WHERE email='{$email}' ";

        if ($user = executeAndFetchAssoc($sql)) {
            return $user["username"];
        }

        return null;
    }

    function qGetUserEmailById($userId) {
        dbEscape($userId);

        $sql = "SELECT email ";
        $sql .= "FROM users ";
        $sql .= "WHERE id='{$userId}' ";

        if ($user = executeAndFetchAssoc($sql)) {
            return $user["email"];
        }

        return null;
    }

    function qIsEmailConfirmed($userId) {
        dbEscape($userId);

        $sql = "SELECT emailConfirmed ";
        $sql .= "FROM users ";
        $sql .= "WHERE id='{$userId}' ";

        if ($user = executeAndFetchAssoc($sql)) {
            return $user["emailConfirmed"] === "1";
        }

        return null;
    }

    function qIsEmailTaken($email) {
        dbEscape($email);

        $sql = "SELECT id ";
        $sql .= "FROM users ";
        $sql .= "WHERE email='{$email}' ";

        return isThereAResult($sql);
    }

    function qIsUsernameTaken($username) {
        dbEscape($username);

        $sql = "SELECT id ";
        $sql .= "FROM users ";
        $sql .= "WHERE username='{$username}' ";

        return isThereAResult($sql);
    }
