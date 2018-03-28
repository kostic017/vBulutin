<?php

    function qCountTableRows($tableName) {
        dbEscape($tableName);

        $sql = "SELECT COUNT(*) as count ";
        $sql .= "FROM {$tableName} ";

        return executeAndFetchAssoc($sql)["count"];
    }

    /// FORUMS ///

    function qIsForumRead($userId, $forumId) {
        dbEscape($userId, $forumId);

        // teme direktno u ovom forumu
        $sql = "SELECT id ";
        $sql .= "FROM topics ";
        $sql .= "WHERE forumId='{$forumId}' ";
        $sql .= "    AND DATEDIFF(CURDATE(), latestPostDT) <= " . GARBAGE_COLLECTION_DAYS;

        if ($topics = executeAndFetchAssoc($sql, FETCH::ALL)) {
            foreach ($topics as $topic) {
                if (!qDidUserReadTopic($userId, $topic["id"])) {
                    return false;
                }
            }
        }

        // teme u child forumima
        $sql = "SELECT id ";
        $sql .= "FROM forums ";
        $sql .= "WHERE parentId='{$forumId}' ";

        if ($children = executeAndFetchAssoc($sql, FETCH::ALL)) {
            foreach ($children as $child) {
                if (!qIsForumRead($userId, $child["id"])) {
                    return false;
                }
            }
        }

        return true;
    }

    function qCountTopicsInForum($forumId) {
        dbEscape($forumId);

        $sql = "SELECT COUNT(*) AS count ";
        $sql .= "FROM topics ";
        $sql .= "WHERE forumId='{$forumId}' ";

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

    function qDidUserReadTopic($userId, $topicId) {
        $flag = qIsTopicReadMarked($userId, $topicId);
        if ($topic = qGetRowById($topicId, "topics")) {
            // Na svakih GARBAGE_COLLECTION_DAYS se cisti tabela readTopics, pa bi ispalo kao da
            // korisnik nije procitao temu. Zato ja kazem da je svaka tema starija od 30 dana procitana.
            $flag = $flag || dateDifference($topic["latestPostDT"]) > GARBAGE_COLLECTION_DAYS;
        }
        return $flag;
    }

    function qIsTopicReadMarked($userId, $topicId) {
        dbEscape($userId, $topicId);

        $sql = "SELECT id ";
        $sql .= "FROM readTopics ";
        $sql .= "WHERE userId='{$userId}' ";
        $sql .= "    AND topicId='{$topicId}' ";

        return isThereAResult($sql);
    }

    function qGetTopicTitleById($topicId) {
        dbEscape($topicId);

        $sql = "SELECT title ";
        $sql .= "FROM topics ";
        $sql .= "WHERE id='{$topicId}' ";

        if ($topic = executeAndFetchAssoc($sql)) {
            return $topic["title"];
        }

        return null;
    }

    function qMarkTopicAsRead($userId, $topicId) {
        dbEscape($userId, $topicId);
        $timeDT = getDatetimeForMysql();

        if (!qIsTopicReadMarked($userId, $topicId)) {
            $sql = "INSERT INTO readTopics (userId, topicId, timeDT) VALUES (";
            $sql .= "'{$userId}', '{$topicId}', '{$timeDT}'";
            $sql .= ")";

            executeQuery($sql);
        }
    }

    function qGetTopicsFromLastVisit($lastVisitDT, $limit = 0) {
        dbEscape($lastVisitDT);

        $sql = "SELECT id, title, firstPostId, startedDT ";
        $sql .= "FROM topics ";
        $sql .= "WHERE startedDT <= '$lastVisitDT' ";
        if ($limit > 0) {
            $sql .= "LIMIT {$limit} ";
        }

        return executeAndFetchAssoc($sql, FETCH::ALL);
    }

    function qCreateNewTopic($forumId, $userId, $title, $content) {
        dbEscape($forumId, $title, $userId); // ne zelimo da eskejpujemo $content dva puta

        $sql = "INSERT INTO topics (id, title, forumId) VALUES (";
        $sql .= "NULL, '{$title}', '{$forumId}'";
        $sql .= ")";

        executeQuery($sql);
        $topicId = getInsertId();

        $post = qCreateNewPost($topicId, $userId, $content);

        $sql = "UPDATE topics SET ";
        $sql .= "   firstPostId = '{$post["id"]}', ";
        $sql .= "   startedDT = '{$post["postedDT"]}', ";
        $sql .= "   latestPostDT = '{$post["postedDT"]}' ";
        $sql .= "WHERE id='{$topicId}' ";

        executeQuery($sql);
        return $topicId;
    }

    function qGetTopicsByForumId($id, $sort = ["latestPostDT" => "DESC"]) {
        dbEscape($id);

        $sql = "SELECT * ";
        $sql .= "FROM topics ";
        $sql .= "WHERE forumId='{$id}' ";
        $sql .= orderByStatement($sort);

        return executeAndFetchAssoc($sql, FETCH::ALL);
    }

    function qGetTopicStarterUsername($topicId) {
        dbEscape($topicId);

        $sql = "SELECT firstPostId ";
        $sql .= "FROM topics ";
        $sql .= "WHERE id='{$topicId}'";

        if ($topics = executeAndFetchAssoc($sql)) {
            $sql = "SELECT userId ";
            $sql .= "FROM posts ";
            $sql .= "WHERE id='{$topics["firstPostId"]}' ";

            if ($post = executeAndFetchAssoc($sql)) {
                $sql = "SELECT username ";
                $sql .= "FROM users ";
                $sql .= "WHERE id='{$post["userId"]}' ";

                if ($user = executeAndFetchAssoc($sql)) {
                    return $user["username"];
                }
            }
        }

        return null;
    }

    function qGetTopicLastPoster($topicId) {
        dbEscape($topicId);

        if ($post = qGetLastPostByTopicId($topicId)) {
            $sql = "SELECT * ";
            $sql .= "FROM users ";
            $sql .= "WHERE id='{$post["userId"]}' ";

            if ($user = executeAndFetchAssoc($sql)) {
                return [
                    "user" => $user,
                    "postId" => $post["id"]
                ];
            }
        }

        return null;
    }

    function qGetLastPostByTopicId($topicId) {
        dbEscape($topicId);

        $sql = "SELECT * ";
        $sql .= "FROM posts ";
        $sql .= "WHERE topicId='{$topicId}' ";
        $sql .= "AND postedDT=( ";
        $sql .= "   SELECT MAX(postedDT) ";
        $sql .= "   FROM posts ";
        $sql .= "   WHERE topicId='{$topicId}' ";
        $sql .= ") ";

        if ($post = executeAndFetchAssoc($sql)) {
            return $post;
        }

        return null;
    }

    function qCountPostsInTopic($topicId) {
        dbEscape($topicId);

        $sql = "SELECT COUNT(*) AS count ";
        $sql .= "FROM posts ";
        $sql .= "WHERE topicId='{$topicId}' ";

        return executeAndFetchAssoc($sql)["count"];
    }

    /// POSTS ///

    function qGetPostsFromLastVisit($lastVisitDT, $limit = 0) {
        dbEscape($lastVisitDT);

        $sql = "SELECT * ";
        $sql .= "FROM posts ";
        $sql .= "WHERE postedDT <= '$lastVisitDT' ";
        if ($limit > 0) {
            $sql .= "LIMIT {$limit} ";
        }

        return executeAndFetchAssoc($sql, FETCH::ALL);
    }

    function qCreateNewPost($topicId, $userId, $content) {
        dbEscape($topicId, $userId, $content);
        $postedDT = getDatetimeForMysql();

        $sql = "INSERT INTO posts (id, content, postedDT, topicId, userId) VALUES (";
        $sql .= "NULL, '{$content}', '{$postedDT}', '{$topicId}', '{$userId}'";
        $sql .= ")";

        executeQuery($sql);

        return [
            "id" => getInsertId(),
            "postedDT" => $postedDT
        ];
    }

    function qAppendToPost($postId, $userId, $content) {
        dbEscape($postId, $userId, $content);
        $appendixDate = getDatetimeForMysql();

        $appendix = "\n### ===== DOPUNA {$appendixDate} ===== \n";
        $appendix .= $content;

        $sql = "UPDATE posts SET ";
        $sql .= "content=CONCAT(content, '{$appendix}') ";
        $sql .= "WHERE id='{$postId}' ";

        executeQuery($sql);
    }

    function qGetPostsByTopicId($id, $sort = ["postedDT" => "ASC"]) {
        dbEscape($id);

        $sql = "SELECT * ";
        $sql .= "FROM posts ";
        $sql .= "WHERE topicId='{$id}' ";
        $sql .= orderByStatement($sort);

        return executeAndFetchAssoc($sql, FETCH::ALL);
    }

    function qGetLastPostInfoByForumId($forumId) {
        dbEscape($forumId);

        $sql = "SELECT id, latestPostDT ";
        $sql .= "FROM topics ";
        $sql .= "WHERE forumId='{$forumId}' ";
        $sql .= "ORDER BY latestPostDT DESC ";

        if ($lastlyUpdatedTopic = executeAndFetchAssoc($sql)) {
            if ($lastPoster = qGetTopicLastPoster($lastlyUpdatedTopic["id"])) {
                return [
                    "username" => $lastPoster["user"]["username"],
                    "date" => convertMysqlDatetimeToPhpDate($lastlyUpdatedTopic["latestPostDT"]),
                    "time" => convertMysqlDatetimeToPhpTime($lastlyUpdatedTopic["latestPostDT"])
                ];
            }
        }

        return null;
    }

    /// USERS ///

    function qGetNewestUser() {
        $sql = "SELECT id, username ";
        $sql .= "FROM users ";
        $sql .= "ORDER BY joinedDT DESC ";

        return executeAndFetchAssoc($sql);
    }

    function qLoginUser($username, $password) {
        dbEscape($username);
        $password = hashPassword($password);

        $sql = "SELECT id, lastVisitDT ";
        $sql .= "FROM users ";
        $sql .= "WHERE username='{$username}' ";
        $sql .= "   AND password='{$password}' ";

        if ($user = executeAndFetchAssoc($sql)) {
            $now = getDatetimeForMysql();

            $sql = "UPDATE users ";
            $sql .= "SET lastVisitDT='{$now}', ";
            $sql .= "    lastActivityDT='{$now}', ";
            $sql .= "    loggedIn='1' ";
            $sql .= "WHERE id='{$user["id"]}' ";
            executeQuery($sql);

            return $user;
        }

        return null;
    }

    function qRegisterUser($username, $email, $password) {
        dbEscape($username, $email);
        $password = hashPassword($password);
        $joinedDT = getDateForMysql();
        $token = generateToken();

        $sql = "INSERT INTO users (id, username, password, email, joinedDT, confirmed, token) VALUES (";
        $sql .= "   NULL, '{$username}', '{$password}', '{$email}', '{$joinedDT}', '0', '{$token}'";
        $sql .= ")";

        executeQuery($sql);
        return $token;
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

    function qConfirmEmail($email, $token) {
        dbEscape($email);

        $sql = "SELECT id ";
        $sql .= "FROM users ";
        $sql .= "WHERE email='{$email}' ";
        $sql .= "    AND confirmed='0' ";
        $sql .= "    AND token='{$token}' ";

        if ($user = executeAndFetchAssoc($sql)) {
            $sql = "UPDATE users ";
            $sql .= "SET confirmed='1', ";
            $sql .= "    token='' ";
            $sql .= "WHERE id='{$user["id"]}' ";
            executeQuery($sql);
            return true;
        }

        return false;
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

    function qGetUsernameById($userId) {
        dbEscape($userId);

        $sql = "SELECT username ";
        $sql .= "FROM users ";
        $sql .= "WHERE id='{$userId}' ";

        if ($user = executeAndFetchAssoc($sql)) {
            return $user["username"];
        }

        return null;
    }

    function qUpdateLastActivityForUser($userId) {
        dbEscape($userId);
        $lastActivityDT = getDatetimeForMysql();

        $sql = "UPDATE users ";
        $sql .= "SET lastActivityDT='{$lastActivityDT}' ";
        $sql .= "WHERE id='{$userId}' ";

        executeQuery($sql);
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

    function qLogoutUser($userId) {
        dbEscape($userId);

        $sql = "UPDATE users ";
        $sql .= "SET loggedIn='0' ";
        $sql .= "WHERE id='{$userId}' ";

        executeQuery($sql);
        unset($_SESSION["userId"]);
    }

    function qIsEmailConfirmedByUserId($userId) {
        dbEscape($userId);

        $sql = "SELECT confirmed ";
        $sql .= "FROM users ";
        $sql .= "WHERE id='{$userId}' ";

        if ($user = executeAndFetchAssoc($sql)) {
            return $user["confirmed"] === "1";
        }

        return null;
    }

    function qIsEmailConfirmedByEmail($email) {
        dbEscape($email);

        $sql = "SELECT confirmed ";
        $sql .= "FROM users ";
        $sql .= "WHERE email='{$email}' ";

        if ($user = executeAndFetchAssoc($sql)) {
            return $user["confirmed"] === "1";
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

    function qGetOnlineUsers() {
        $sql = "SELECT username ";
        $sql .= "FROM users ";
        $sql .= "WHERE loggedIn='1' ";

        return executeAndFetchAssoc($sql, FETCH::ALL) ?? [];
    }

    function qUpdateUserInvisibility($userId, $invisible) {
        dbEscape($userId, $invisible);

        $sql = "UPDATE users ";
        $sql .= "SET invisible='{$invisible}' ";
        $sql .= "WHERE id='{$userId}' ";

        executeQuery($sql);
    }

    function qUpdateUserData($userId, $sex, $birthdate, $birthplace, $residence, $job, $avatar) {
        dbEscape($userId, $sex, $birthdate, $birthplace, $residence, $job, $avatar);

        $sql = "UPDATE users ";
        $sql .= "SET sex='{$sex}', ";
        $sql .= "    birthdate='{$birthdate}', ";
        $sql .= "    birthplace='{$birthplace}', ";
        $sql .= "    residence='{$residence}', ";
        $sql .= "    job='{$job}', ";
        $sql .= "    avatar='{$avatar}' ";
        $sql .= "WHERE id='{$userId}' ";

        executeQuery($sql);
    }

    function qUpdateUserEmail($userId, $email) {
        dbEscape($userId, $email);
        $token = generateToken();

        $sql = "UPDATE users ";
        $sql .= "SET email='{$email}', ";
        $sql .= "    confirmed='0', ";
        $sql .= "    token='{$token}' ";
        $sql .= "WHERE id='{$userId}' ";

        executeQuery($sql);

        return $token;
    }

    function qUpdateUserPassword($userId, $password) {
        dbEscape($userId);
        $password = hashPassword($password);

        $sql = "UPDATE users ";
        $sql .= "SET password='{$password}' ";
        $sql .= "WHERE id='{$userId}' ";

        executeQuery($sql);
    }