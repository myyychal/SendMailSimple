<?php

function addUser($username, $password)
{
    global $errMsg;

    $cost = 10;
    $salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
    $salt = sprintf("$2a$%02d$", $cost) . $salt;
    $hash = crypt($password, $salt);

    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT id,login,password FROM users WHERE login = \"$username\"";

    $ret = $db->query($sql);
    if ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
        $errMsg = "There is already a user with this login.";
        return false;
    }

    $sql = "INSERT INTO users VALUES (NULL, \"$username\", \"$hash\")";

    $ret = $db->exec($sql);
    if ($ret > 0) {
        $db->close();
        return true;
    } else {
        $db->close();
        return false;
    }
}

function addPerson($newName, $newSurname, $newMail)
{
    global $errMsg;

    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM persons WHERE email = \"$newMail\"";

    $ret = $db->query($sql);
    if ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
        $errMsg = "There is already a user with this email.";
        return false;
    }

    $sql = "INSERT INTO persons VALUES (NULL, \"$newName\", \"$newSurname\", \"$newMail\")";

    $ret = $db->exec($sql);
    if ($ret > 0) {
        $db->close();
        return true;
    } else {
        $db->close();
        return false;
    }
}

function addPersonToUnsubscribers($personId, $projectId)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM unsubscribers WHERE personId = \"$personId\" AND projectId=\"$projectId\"";

    $ret = $db->query($sql);
    if ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
        return false;
    }

    $sql = "INSERT INTO unsubscribers VALUES (\"$personId\", \"$projectId\")";

    try {
        $ret = $db->exec($sql);
    } catch (Exception $e) {
        return false;
    }

    if ($ret > 0) {
        $db->close();
        return true;
    } else {
        $db->close();
        return false;
    }
}

function addGroup($newName)
{
    global $errMsg;

    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM groups WHERE name = \"$newName\"";

    $ret = $db->query($sql);
    if ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
        $errMsg = "There is already a group with this name.";
        return false;
    }

    $sql = "INSERT INTO groups VALUES (NULL, \"$newName\")";

    $ret = $db->exec($sql);
    if ($ret > 0) {
        $db->close();
        return true;
    } else {
        $db->close();
        return false;
    }
}

function createMailingProject($newName, $newMessage, $newHappenDate)
{
    global $errMsg;

    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM projects WHERE name = \"$newName\"";

    $ret = $db->query($sql);
    if ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
        $errMsg = "There is already a project with this name.";
        return false;
    }

    $sql = "INSERT INTO projects VALUES (NULL, \"$newName\", \"$newMessage\", \"$newHappenDate\")";

    $ret = $db->exec($sql);
    if ($ret > 0) {
        $db->close();
        return true;
    } else {
        $db->close();
        return false;
    }
}

function addPersonsToGroup($personIds, $groupId)
{
    global $errMsg3;
    global $successLogin3;

    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    foreach ($personIds as $id) {

        $sql = "SELECT * FROM mailinglists WHERE personId = \"$id\" AND groupId =\"$groupId\" ";

        $ret = $db->query($sql);
        if ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            $errMsg3 = "Some of these persons are already linked with selected group.";
            return false;
        }

        $sql = "INSERT INTO mailinglists VALUES (\"$id\", \"$groupId\");";
        $ret = $db->exec($sql);
        if (!$ret) {
            $successLogin3 = "Persons were successfully added to selected group.";
        }
    }

    if ($ret > 0) {
        $db->close();
        return true;
    } else {
        $db->close();
        return false;
    }
}

function addGroupsToProject($groupsIds, $projectId)
{
    global $errMsg3;
    global $successLogin3;

    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    foreach ($groupsIds as $id) {

        $sql = "SELECT * FROM events WHERE groupId = \"$id\" AND projectId =\"$projectId\" ";

        $ret = $db->query($sql);
        if ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            $errMsg3 = "Some of these groups are already linked with selected project.";
            return false;
        }

        $sql = "INSERT INTO events VALUES (\"$id\", \"$projectId\");";
        $ret = $db->exec($sql);
        if (!$ret) {
            $successLogin3 = "Groups were successfully added to selected project.";
        }
    }

    if ($ret > 0) {
        $db->close();
        return true;
    } else {
        $db->close();
        return false;
    }
}

function addGroupsToPerson($groupsIds, $personId)
{
    global $errMsg3;
    global $successLogin3;

    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    foreach ($groupsIds as $id) {

        $sql = "SELECT * FROM mailinglists WHERE groupId = \"$id\" AND personId =\"$personId\" ";

        $ret = $db->query($sql);
        if ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            $errMsg3 = "Some of these groups are already linked with selected person.";
            return false;
        }

        $sql = "INSERT INTO mailinglists VALUES (\"$personId\", \"$id\");";
        $ret = $db->exec($sql);
        if (!$ret) {
            $successLogin3 = "Groups were successfully added to selected project.";
        }
    }

    if ($ret > 0) {
        $db->close();
        return true;
    } else {
        $db->close();
        return false;
    }
}

function addProjectToGroup($projectsIds, $groupId)
{
    global $errMsg3;
    global $successLogin3;

    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    foreach ($projectsIds as $id) {

        $sql = "SELECT * FROM events WHERE groupId = \"$groupId\" AND projectId =\"$id\" ";

        $ret = $db->query($sql);
        if ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            $errMsg3 = "Somoe of these projects are already linked with selected group.";
            return false;
        }

        $sql = "INSERT INTO events VALUES (\"$groupId\", \"$id\");";
        $ret = $db->exec($sql);
        if ($ret) {
            $successLogin3 = "Projects were successfully added to selected group.";
        }
    }

    if ($ret > 0) {
        $db->close();
        return true;
    } else {
        $db->close();
        return false;
    }
}

?>