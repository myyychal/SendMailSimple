<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>SMS - Manage groups</title>
    <script src="js/checkFields.js"></script>
    <script src="js/utils.js"></script>
    <link rel="stylesheet" href="css/pure-min.css">
    <!--[if lte IE 8]>
    <link rel="stylesheet" href="css/layouts/side-menu-old-ie.css">
    <![endif]-->
    <!--[if gt IE 8]><!-->
    <link rel="stylesheet" href="css/layouts/side-menu.css">
    <!--<![endif]-->
</head>
<body>
<?php
include 'php_libs/insertFunctions.php';
include 'php_libs/selectFunctions.php';
include 'php_libs/updateFunctions.php';
include 'php_libs/deleteFunctions.php';
include 'php_libs/utils.php';
session_start();
?>
<?php
$newName = "";
$editName = "";
$errMsg = $errMsg2 = $errMsg3 = "";
$selectedGroup = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["removeGroup"])) {
        if (!empty($_POST['check_list_remove'])) {
            $ids = $_POST['check_list_remove'];
            deleteGroups($ids);
            echo "<script> alert(\"Selected groups were removed.\")</script>";
        }
    } else if (isset($_POST["addGroup"])) {
        $newName = $_POST["newName"];
        if (addGroup($newName)) {
            echo "<script> alert(\"New group was added.\")</script>";
        } else {
            echo "<script> alert(\"New group was not added.\")</script>";
        }
    } else if (isset($_POST["addGroupsToProject"])) {
        if (!empty($_POST['check_list_remove'])) {
            $ids = $_POST['check_list_remove'];
            if (!empty($_POST["chooseProject"])) {
                $projectId = $_POST["chooseProject"];
                if (addGroupsToProject($ids, $projectId)) {
                    echo "<script> alert(\"Chosen groups were added to selected project.\")</script>";
                } else {
                    echo "<script> alert(\"Some of these groups are already linked with selected project.\")</script>";
                }

            }
        }
    } else if (isset($_POST["addPersonsToGroup"])) {
        if (!empty($_POST['check_list_remove'])) {
            $ids = $_POST['check_list_remove'];
            if (!empty($_POST["chooseUser"])) {
                $personId = $_POST["chooseUser"];
                if (addGroupsToPerson($ids, $personId)) {
                    echo "<script> alert(\"Chosen groups were added to selected person.\")</script>";
                } else {
                    echo "<script> alert(\Some of these groups are already linked with selected person\")</script>";
                }

            }
        }
    } else if (isset($_POST["editGroup"])) {
        $selectedGroup = $_POST['selectGroup'];
        reloadEditGroupFields($selectedGroup);
        $editName = $_POST["editName"];
        if (updateGroup($selectedGroup, $editName)) {
            echo "<script> alert(\"Group data was edited and saved.\")</script>";
        } else {
            echo "<script> alert(\"Group data was not edited.\")</script>";
        }
    } else if (isset($_POST["unsubscribeFromGroup"])) {
        $selectedGroup = $_POST['selectGroup'];
        reloadEditGroupFields($selectedGroup);
        if (!empty($_POST['check_list_users'])) {
            $ids = $_POST['check_list_users'];
            deleteRowsFromMailingListsByGroup($selectedGroup, $ids);
        }
    } else if (isset($_POST["unsubscribeFromProject"])) {
        $selectedGroup = $_POST['selectGroup'];
        reloadEditGroupFields($selectedGroup);
        if (!empty($_POST['check_list_projects'])) {
            $ids = $_POST['check_list_projects'];
            if (deleteRowsFromEventsByGroup($selectedGroup, $ids)) {
                echo "<script> alert(\"Selected group was removed from chosen projects.\")</script>";
            }
        }
    } else if (isset($_POST["selectGroup"])) {
        $selectedGroup = $_POST['selectGroup'];
        reloadEditGroupFields($selectedGroup);
    }
}
?>
<div id="layout">
<a href="#menu" id="menuLink" class="menu-link">
    <!-- Hamburger icon -->
    <span></span>
</a>

<div id="menu">
    <div class="pure-menu pure-menu-open">
        <ul>
            <?php
            if (!isset($_SESSION['loggedin'])) {
                ?>
                <li>
                    <a href="login.php">Login</a>
                </li>
            <?php
            } elseif (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                ?>
                <li>
                    <a href="logout.php">Logout</a>
                </li>
            <?php
            }
            ?>
            <li>
                <a href="addUser.php">Add user</a>
            </li>
            <li>
                <a href="managePersons.php">Manage persons</a>
            </li>
            <li>
                <a href="manageGroups.php">Manage groups</a>
            </li>
            <li>
                <a href="manageProjects.php">Manage projects</a>
            </li>
            <li>
                <a href="sendMail.php">Send mail</a>
            </li>
            <li>
                <a href="useMailingProject.php">Use mailing project</a>
            </li>
        </ul>
    </div>
</div>
<div id="main">
<div class="header">
    <h1>Simple Mail Service</h1>
    <?php
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
        echo "Hello, " . $_SESSION['username'];
    }
    ?>

    <h2>Manage groups</h2>
</div>
<div class="content">
<?php
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    ?>


    <div class="pure-menu pure-menu-open pure-menu-horizontal">
        <ul>
            <li><a href="#"><h3 onclick="showAndHideDiv('addGroupDiv', 'allGroupsDiv', 'manageSelectedGroupDiv')"
                                onmouseover="" style="cursor: pointer;">Add group</h3></a></li>

            <li><a href="#"><h3 onclick="showAndHideDiv('allGroupsDiv', 'addGroupDiv', 'manageSelectedGroupDiv')"
                                onmouseover="" style="cursor: pointer;">All groups</h3></a></li>

            <li><a href="#"><h3 onclick="showAndHideDiv('manageSelectedGroupDiv', 'allGroupsDiv', 'addGroupDiv')"
                                onmouseover="" style="cursor: pointer;">Manage selected
                        group</h3></a></li>
        </ul>
    </div>
    <!-- ------------ Add group -------------------------------------------------------------------->
    <div id="addGroupDiv">
        <form class="pure-form pure-form-stacked" name="addGroupForm" onsubmit="return checkGroupFields()"
              method="post"
              action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <table>
                <tr>
                    <td>Name: <input type="text" id="newName" name="newName" tabindex="1"
                                     value="<?php echo $newName; ?>"/></td>
                </tr>
            </table>
            <p>
                <input class="button-success pure-button" type="submit" name="addGroup" value="Add group"
                       tabindex="4"/>
                <input class="pure-button" type="button" name="cancelButton" value="Cancel" tabindex="5"/>
            </p>

            <p id="errMsg"></p>
        </form>
    </div>
    <span class="error"><?php global $errMsg;
        echo $errMsg ?></span>

    <!-- ------------ All groups -------------------------------------------------------------------->

    <div id="allGroupsDiv">
        <form class="pure-form pure-form-stacked" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
              method="post">
            <?php
            $ret = selectGroups();
            if ($ret != false) {
                echo "<table class=\"pure-table\">";
                while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                    echo "<tr>";
                    $rowName = $row["name"];
                    $rowId = $row["id"];
                    echo "<td>$rowName</td>";
                    echo "<td><input type=\"checkbox\" name=\"check_list_remove[]\" value=\"$rowId\"></td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            ?>
            <p><input class="button-success pure-button" type="submit" name="removeGroup"
                      value="Remove selected groups" tabindex="4"/></p>

            <p>Choose mailing project:</p>

            <select id="chooseProject" name="chooseProject">
                <?php
                $ret = selectProjects();
                if ($ret != false) {
                    while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                        $rowName = $row["name"];
                        $rowId = $row["id"];
                        echo "<option value=\"$rowId\">$rowName</option>";
                    }
                }
                ?>
            </select>

            <p><input class="button-success pure-button" type="submit" name="addGroupsToProject"
                      value="Add selected groups to chosen project"
                      tabindex="5"/></p>

            <p>Choose person:</p>

            <select id="chooseUser" name="chooseUser">
                <?php
                $ret = selectPersons();
                if ($ret != false) {
                    while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                        $rowName = $row["name"];
                        $rowSurname = $row["surname"];
                        $rowId = $row["id"];
                        echo "<option value=\"$rowId\">$rowName $rowSurname</option>";
                    }
                }
                ?>
            </select>
            </br><input class="button-success pure-button" type="submit" name="addPersonsToGroup"
                        value="Add selected groups to chosen person"
                        tabindex="5"/>

            <p>
                        <span class="error"><?php global $errMsg3;
                            echo $errMsg3 ?></span>
            </p>
        </form>
    </div>

    <!-- ------------ Manage selected group -------------------------------------------------------------------->

    <div id="manageSelectedGroupDiv">
        <form class="pure-form pure-form-stacked" id="selectGroupForm" name="selectPersonForm"
              onsubmit="return checkGroupFields()" method="post"
              action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            Choose group:
            <select id="selectGroup" name="selectGroup" onchange="this.form.submit()">
                <?php
                $ret = selectGroups();
                if ($ret != false) {
                    echo "<option value=0></option>";
                    while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                        $rowValue = $row["name"];
                        $rowId = $row["id"];
                        if ($rowId == $selectedGroup) {
                            echo "<option value=\"$rowId\" selected=\"selected\">$rowValue</option>";
                        } else {
                            echo "<option value=\"$rowId\">$rowValue</option>";
                        }
                    }
                }
                ?>
            </select>

            <h4>Edit group</h4>
            <table>
                <tr>
                    <td>Name: <input type="text" id="editName" name="editName" tabindex="1"
                                     value="<?php echo $editName; ?>"/></td>
                </tr>
            </table>
            <p>
                <input class="button-success pure-button" type="submit" name="editGroup" value="Edit group"
                       tabindex="4"/>
                <input class="pure-button" type="hidden" id="selectedGroupId" name="selectedGroupId"/>
                <input class="pure-button" type="button" name="cancelButton" value="Cancel" tabindex="5"/>
            </p>

            <p class="error" id="errMsg2"></p>
            <span class="error"><?php global $errMsg2;
                echo $errMsg2 ?></span>

            <h4>Users: </h4>
            <?php
            $ret = selectUsersByGroup($selectedGroup);
            if ($ret != false) {
                echo "<table class=\"pure-table\">";
                while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                    echo "<tr>";
                    $rowName = $row["name"];
                    $rowSurname = $row["surname"];
                    $rowEmail = $row["email"];
                    $rowId = $row["id"];
                    echo "<td>$rowName</td> <td>$rowSurname</td> <td>$rowEmail </td>";
                    echo "<td><input type=\"checkbox\" name=\"check_list_users[]\" value=\"$rowId\"></td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            ?>
            <input class="button-success pure-button" type="submit" name="unsubscribeFromGroup"
                   value="Remove users from selected group"/>

            <h4>Projects: </h4>
            <?php
            $ret = selectProjectsByGroup($selectedGroup);
            if ($ret != false) {
                echo "<table class=\"pure-table\">";
                while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                    echo "<tr>";
                    $rowName = $row["name"];
                    $rowId = $row["id"];
                    echo "<td>$rowName</td>";
                    echo "<td><input type=\"checkbox\" name=\"check_list_projects[]\" value=\"$rowId\"></td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            ?>

            <input class="button-success pure-button" type="submit" name="unsubscribeFromProject"
                   value="Remove projects from selected group"/>
        </form>
    </div>
    <?php
    echo "<script>hideAll('manageSelectedGroupDiv', 'allGroupsDiv', 'addGroupDiv')</script>";
    if (isset($_POST["removeGroup"])) {
        echo "<script>reloadShowAndHideDiv('allGroupsDiv', 'addGroupDiv','manageSelectedGroupDiv')</script>";
    } else if (isset($_POST["addGroup"])) {
        echo "<script>reloadShowAndHideDiv('addGroupDiv', 'allGroupsDiv','addPersonDiv')</script>";
    } else if (isset($_POST["addGroupsToProject"])) {
        echo "<script>reloadShowAndHideDiv('allGroupsDiv', 'addGroupDiv','manageSelectedGroupDiv')</script>";
    } else if (isset($_POST["addPersonsToGroup"])) {
        echo "<script>reloadShowAndHideDiv('allGroupsDiv', 'addGroupDiv','manageSelectedGroupDiv')</script>";
    } else if (isset($_POST["editGroup"])) {
        echo "<script>reloadShowAndHideDiv('manageSelectedGroupDiv', 'addGroupDiv','allGroupsDiv')</script>";
    } else if (isset($_POST["unsubscribeFromGroup"])) {
        echo "<script>reloadShowAndHideDiv('manageSelectedGroupDiv', 'addGroupDiv','allGroupsDiv')</script>";
    } else if (isset($_POST["unsubscribeFromProject"])) {
        echo "<script>reloadShowAndHideDiv('manageSelectedGroupDiv', 'addGroupDiv','allGroupsDiv')</script>";
    } else if (isset($_POST["selectGroup"])) {
        echo "<script>reloadShowAndHideDiv('manageSelectedGroupDiv', 'addGroupDiv','allGroupsDiv')</script>";
    }
} else {
    loginFirstMsg();
}
?>

<!-- ------------ Back to menu -------------------------------------------------------------------->

<p>
    <a class="button-secondary pure-button" href="index.php">Back to menu</a>
</p>
</div>
</div>
</div>
<script>
    var e = document.getElementById("selectGroup").value;
    document.getElementById("selectedGroupId").value = e;
</script>
<script src="js/ui.js"></script>
</body>
</html>