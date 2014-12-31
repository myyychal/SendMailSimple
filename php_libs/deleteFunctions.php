<?php

function deletePersons($ids)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    foreach ($ids as $id) {
        $sql = "DELETE FROM persons WHERE id=$id";
        $ret = $db->query($sql);
    }

    foreach ($ids as $id) {
        $sql = "DELETE FROM mailinglists WHERE personId=$id";
        $ret = $db->query($sql);
    }

    foreach ($ids as $id) {
        $sql = "DELETE FROM unsubscribers WHERE personId=$id";
        $ret = $db->query($sql);
    }

    $db->close();

    return $ret;
}

function deleteGroups($ids)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    foreach ($ids as $id) {
        $sql = "DELETE FROM groups WHERE id=$id";
        $ret = $db->query($sql);
    }

    foreach ($ids as $id) {
        $sql = "DELETE FROM mailinglists WHERE groupId=$id";
        $ret = $db->query($sql);
    }


    foreach ($ids as $id) {
        $sql = "DELETE FROM events WHERE groupId=$id";
        $ret = $db->query($sql);
    }

    $db->close();

    return $ret;
}

function deleteProjects($ids)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    foreach ($ids as $id) {
        $sql = "DELETE FROM projects WHERE id=$id";
        $ret = $db->query($sql);
    }

    foreach ($ids as $id) {
        $sql = "DELETE FROM events WHERE projectId=$id";
        $ret = $db->query($sql);
    }

    foreach ($ids as $id) {
        $sql = "DELETE FROM unsubscribers WHERE projectId=$id";
        $ret = $db->query($sql);
    }

    $db->close();

    return $ret;
}

function deleteRowFromUnsubscribers($personId, $projectId)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM unsubscribers WHERE personId = \"$personId\" AND projectId=\"$projectId\"";

    $ret = $db->query($sql);
    if ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
        $sql = "DELETE FROM unsubscribers WHERE personId=$personId AND projectId = $projectId";
        try {
            $ret = $db->query($sql);
        } catch (Exception $ex) {
            return false;
        }
        $db->close();
        return true;
    } else {
        return false;
    }
}

function deleteRowsFromMailingListsByPerson($personId, $groupsId)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    foreach ($groupsId as $id) {
        $sql = "DELETE FROM mailinglists WHERE personId=$personId AND groupId = $id";
        $ret = $db->query($sql);
    }

    $db->close();

    return $ret;
}

function deleteRowsFromMailingListsByGroup($groupId, $personsId)
{

    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    foreach ($personsId as $id) {
        $sql = "DELETE FROM mailinglists WHERE groupId=$groupId AND personId = $id";
        $ret = $db->query($sql);
    }

    $db->close();

    return $ret;
}

function deleteRowsFromEventsByGroup($groupId, $projectsId)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    foreach ($projectsId as $id) {
        $sql = "DELETE FROM events WHERE groupId=$groupId AND projectId = $id";
        $ret = $db->query($sql);
    }

    $db->close();

    return $ret;
}

function deleteRowsFromEventsByProject($projectId, $groupsId)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    foreach ($groupsId as $id) {
        $sql = "DELETE FROM events WHERE projectId=$projectId AND groupId = $id";
        $ret = $db->query($sql);
    }

    $db->close();

    return $ret;
}

?>