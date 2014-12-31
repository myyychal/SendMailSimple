<?php

function selectPersons()
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM persons ";

    $ret = $db->query($sql);

    return $ret;
}

function selectPersonById($id)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM persons where id = $id";

    $ret = $db->query($sql);

    return $ret;
}

function selectPersonByEmail($email)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM persons WHERE email = \"$email\"";

    $ret = $db->query($sql);

    return $ret;
}

function selectGroups()
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM groups ";

    $ret = $db->query($sql);

    return $ret;
}

function selectGroupById($id)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM groups where id = $id";

    $ret = $db->query($sql);

    return $ret;
}

function selectProjects()
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM projects ";

    $ret = $db->query($sql);

    return $ret;
}

function selectProjectById($id)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM projects where id=$id";

    $ret = $db->query($sql);

    return $ret;
}

function selectGroupsByUser($userId)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM groups WHERE id IN (SELECT groupId FROM mailinglists WHERE personId = $userId)";

    $ret = $db->query($sql);

    return $ret;
}

function selectUsersByGroup($groupId)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM persons WHERE id IN (SELECT groupId FROM mailinglists WHERE groupId = $groupId)";

    $ret = $db->query($sql);

    return $ret;
}

function selectUnsubscribersByProject($projectId)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM persons WHERE id IN (SELECT personId FROM unsubscribers WHERE projectId = $projectId)";

    $ret = $db->query($sql);

    return $ret;
}

function selectUsersByProject($projectId)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM persons WHERE id IN (SELECT personId FROM mailinglists WHERE groupId IN (SELECT groupId FROM events WHERE projectId = $projectId))";

    $ret = $db->query($sql);

    return $ret;
}

function selectUsersByProjectAndExcludeUnsubscribers($projectId)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM persons WHERE id IN (SELECT personId FROM mailinglists WHERE groupId IN (SELECT groupId FROM events WHERE projectId = $projectId))
                                            AND id NOT IN (SELECT personId FROM unsubscribers WHERE projectId = $projectId)";

    $ret = $db->query($sql);

    return $ret;
}


function selectProjectsByGroup($groupId)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM projects WHERE id IN (SELECT projectId FROM events WHERE groupId = $groupId)";

    $ret = $db->query($sql);

    return $ret;
}

function selectGroupsByProject($projectId)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM groups WHERE id IN (SELECT groupId FROM events WHERE projectId = $projectId)";

    $ret = $db->query($sql);

    return $ret;
}

?>