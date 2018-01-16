<?php

    function qGetTopicsByForumId($id, $sort = ["started" => "ASC"]) {
        $id = dbEscape($id);
        
        $sql = "SELECT * ";
        $sql .= "FROM topics ";
        $sql .= "WHERE forums_id='{$id}' ";
        $sql .= "ORDER BY ";
        $counter = count($sort);
        foreach($sort as $column => $direction) {
            $sql .= "{$column} {$direction}";
            $sql .= (--$counter > 0) ? ", " : " ";
        }
        
        return execAndFetchAssoc($sql, FETCH::ALL);
    }
    
    function qGetTopicStarterUsername($firstPostId) {
        $firstPostId = dbEscape($firstPostId);
        
        $sql = "SELECT users_id ";
        $sql .= "FROM posts ";
        $sql .= "WHERE id='{$firstPostId}' ";
        
        $userId = execAndFetchAssoc($sql)["users_id"];
        
        $sql = "SELECT username ";
        $sql .= "FROM users ";
        $sql .= "WHERE id='{$userId}' ";
        
        return execAndFetchAssoc($sql)["username"];
    }
    
    function qCountPostsInTopic($topicId) {
        $topicId = dbEscape($topicId);
        
        $sql = "SELECT COUNT(*) AS count ";
        $sql .= "FROM posts ";
        $sql .= "WHERE topics_id='{$topicId}' ";
        
        return execAndFetchAssoc($sql)["count"];
    }