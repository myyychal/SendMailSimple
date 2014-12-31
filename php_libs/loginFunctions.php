<?php

function checkLoginAndPassword($login, $password)
{
    $hash = "";

    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT id,login,password FROM users WHERE login = \"$login\"";

    $ret = $db->query($sql);
    while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
        $hash = $row["password"];
    }

    if (crypt($password, $hash) === $hash) {
        return true;
    } else {
        return false;
    }

    $db->close();
}

function loginUser($login, $password)
{
    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = $login;
}

?>