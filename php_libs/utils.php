<?php

function loginFirstMsg()
{
    echo "<p>Please log in first to see this page.</br>
            You will be redirected in 2 seconds</p>";
    $url = "login.php";
    header("refresh:2; url=$url");
}

function reloadEditPersonFields($selectedPerson)
{
    global $editName, $editSurname, $editMail;
    $ret = selectPersonById($selectedPerson);
    if ($ret != false) {
        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            $editName = $row["name"];
            $editSurname = $row["surname"];
            $editMail = $row["email"];
        }
    }
}

function reloadEditGroupFields($selectedGroup)
{
    global $editName;
    $ret = selectGroupById($selectedGroup);
    if ($ret != false) {
        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            $editName = $row["name"];
        }
    }
}

function reloadEditProjectFields($selectedGroup)
{
    global $editName, $editMessage, $editHappenDate;
    $ret = selectProjectById($selectedGroup);
    if ($ret != false) {
        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            $editName = $row["name"];
            $editMessage = $row["message"];
            $editHappenDate = $row["happedDate"];
        }
    }
}

function curPageURL()
{
    $pageURL = 'http';
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

function curPageURLMain()
{
    $pageURL = 'http';
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"];
    }
    return $pageURL;
}

?>