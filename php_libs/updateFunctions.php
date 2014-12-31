<?php

function updatePerson($id, $editName, $editSurname, $editMail)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $id = intval($id);

    $sql = "UPDATE persons SET name=\"$editName\", surname=\"$editSurname\", email=\"$editMail\" WHERE id=$id";

    $ret = $db->exec($sql);
    if ($ret > 0) {
        $db->close();
        return true;
    } else {
        $db->close();
        return false;
    }
}

function updateGroup($id, $editName)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $id = intval($id);

    $sql = "UPDATE groups SET name=\"$editName\" WHERE id=$id";

    $ret = $db->exec($sql);
    if ($ret > 0) {
        $db->close();
        return true;
    } else {
        $db->close();
        return false;
    }
}

function updateProject($id, $editName, $editMessage, $editHappenDate)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $id = intval($id);

    $sql = "UPDATE projects SET name=\"$editName\", message=\"$editMessage\", happedDate=\"$editHappenDate\" WHERE id=$id";

    $ret = $db->exec($sql);
    if ($ret > 0) {
        $db->close();
        return true;
    } else {
        $db->close();
        return false;
    }
}

?>