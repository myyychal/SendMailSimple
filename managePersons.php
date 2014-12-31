<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>SMS - Manage persons</title>
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
$newName = $newSurname = $newMail = "";
$editName = $editSurname = $editMail = "";
$errMsg = $errMsg2 = $errMsg3 = "";
$selectedPerson = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["addPerson"])) {
        $newName = $_POST["newName"];
        $newSurname = $_POST["newSurname"];
        $newMail = $_POST["newEmail"];
        if (addPerson($newName, $newSurname, $newMail)) {
            echo "<script> alert(\"New person was added.\")</script>";
        } else {
            echo "<script> alert(\"New person was not added.\")</script>";
        }
    } else if (isset($_POST["editPerson"])) {
        $selectedPerson = $_POST['selectPerson'];
        reloadEditPersonFields($selectedPerson);
        $editName = $_POST["editName"];
        $editSurname = $_POST["editSurname"];
        $editMail = $_POST["editEmail"];
        if (updatePerson($selectedPerson, $editName, $editSurname, $editMail)) {
            echo "<script> alert(\"Person data was edited and saved.\")</script>";
        } else {
            echo "<script> alert(\"Person data was not edited.\")</script>";
        }
    } else if (isset($_POST["removePerson"])) {
        if (!empty($_POST['check_list_remove'])) {
            $ids = $_POST['check_list_remove'];
            deletePersons($ids);
            echo "<script> alert(\"Selected persons were removed.\")</script>";
        }
    } else if (isset($_POST["addPersonsToGroup"])) {
        if (!empty($_POST['check_list_remove'])) {
            $ids = $_POST['check_list_remove'];
            if (!empty($_POST["chooseGroup"])) {
                $groupId = $_POST["chooseGroup"];
                if (addPersonsToGroup($ids, $groupId)) {
                    echo "<script> alert(\"Chosen persons were added to selected group.\")</script>";
                } else {
                    echo "<script> alert(\"Some of these persons are already linked with selected group.\")</script>";
                }
            }
        }
    } else if (isset($_POST["unsubscribePerson"])) {
        $selectedPerson = $_POST['selectPerson'];
        reloadEditPersonFields($selectedPerson);
        if (!empty($_POST['check_list_groups'])) {
            $ids = $_POST['check_list_groups'];
            deleteRowsFromMailingListsByPerson($selectedPerson, $ids);
            echo "<script> alert(\"Selected person was removed from chosen groups.\")</script>";
        }
    } else if (isset($_POST["selectPerson"])) {
        $selectedPerson = $_POST['selectPerson'];
        reloadEditPersonFields($selectedPerson);
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

    <h2>Manage persons</h2>
</div>
<div class="content">
    <?php
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
        ?>
        <div class="pure-menu pure-menu-open pure-menu-horizontal">
            <ul>
                <li><a href="#"><h3 onclick="showAndHideDiv('addPersonDiv', 'allPersonsDiv','manageSelectedPersonDiv')"
                                    onmouseover="" style="cursor: pointer;">Add person</h3></a></li>
                <li><a href="#"><h3 onclick="showAndHideDiv( 'allPersonsDiv', 'addPersonDiv','manageSelectedPersonDiv')"
                                    onmouseover="" style="cursor: pointer;">All persons</h3></a></li>
                <li><a href="#"><h3 onclick="showAndHideDiv('manageSelectedPersonDiv', 'addPersonDiv', 'allPersonsDiv')"
                                    onmouseover="" style="cursor: pointer;">Manage selected
                            person</h3></a></li>
            </ul>
        </div>
        <!-- ------------ Add person -------------------------------------------------------------------->
        <div id="addPersonDiv">
            <form class="pure-form pure-form-stacked" name="addPersonForm" method="post"
                  onsubmit="return checkPersonFields('create')"
                  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <table>
                    <tr>
                        <td>Name:</td>
                        <td><input type="text" name="newName" value="<?php echo $newName; ?>"/></td>
                    </tr>
                    <tr>
                        <td>Surname:</td>
                        <td><input type="text" name="newSurname" value="<?php echo $newSurname; ?>"/></td>
                    </tr>
                    <tr>
                        <td>Mail:</td>
                        <td><input type="email" id="newEmail" name="newEmail" value="<?php echo $newMail; ?>"/>
                    </tr>
                </table>
                <p>
                    <input class="button-success pure-button" type="submit" name="addPerson" value="Add person"/>
                    <input class="pure-button" type="button" name="cancelButton" value="Cancel"/>
                </p>

                <p class="error" id="errMsg"></p>
        </div>
        </form>

        <span class="error"><?php global $errMsg;
            echo $errMsg ?></span>

        <!-- ------------ All persons -------------------------------------------------------------------->

        <div id="allPersonsDiv">

            <form class="pure-form pure-form-stacked" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                  method="post">
                <p>
                    <?php
                    $ret = selectPersons();
                    if ($ret != false) {
                        echo "<table class=\"pure-table\">";
                        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                            echo "<tr>";
                            $rowName = $row["name"];
                            $rowSurname = $row["surname"];
                            $rowEmail = $row["email"];
                            $rowId = $row["id"];
                            echo "<td>$rowName</td> <td>$rowSurname</td> <td>$rowEmail</td> ";
                            echo "<td><input type=\"checkbox\" name=\"check_list_remove[]\" value=\"$rowId\"></td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    }
                    ?>

                <p><input class="button-success pure-button" type="submit" name="removePerson"
                          value="Remove selected persons"/>

                <p>
                </p>

                <p>Choose group:</p>

                <p>
                    <select id="chooseGroup" name="chooseGroup">
                        <?php
                        $ret = selectGroups();
                        if ($ret != false) {
                            while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                                $rowValue = $row["name"];
                                $rowId = $row["id"];
                                echo "<option value=\"$rowId\">$rowValue</option>";
                            }
                        }
                        ?>
                    </select>
                </p>

                <p>
                    <input class="button-success pure-button" type="submit" name="addPersonsToGroup"
                           value="Add selected persons to chosen group"/>
                </p>
            <span class="error"><?php global $errMsg3;
                echo $errMsg3 ?></span>
            </form>
        </div>
        <!-- ------------ Manage selected person -------------------------------------------------------------------->

        <div id="manageSelectedPersonDiv">
            <form class="pure-form pure-form-stacked" name="editPersonForm" method="post"
                  onsubmit="return checkPersonFields('edit')"
                  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                Choose person:
                <select id="selectPerson" name="selectPerson" onChange="this.form.submit()">
                    <?php
                    $ret = selectPersons();
                    if ($ret != false) {
                        echo "<option value=0></option>";
                        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                            $rowValue = $row["name"] . " " . $row["surname"] . " " . $row["email"];
                            $rowId = $row["id"];
                            if ($rowId == $selectedPerson) {
                                echo "<option value=\"$rowId\" selected=\"selected\">$rowValue</option>";
                            } else {
                                echo "<option value=\"$rowId\">$rowValue</option>";
                            }
                        }
                    }
                    ?>
                </select>

                <h4>Edit person</h4>
                <table>
                    <tr>
                        <td>Name:</td>
                        <td><input type="text" id="editName" name="editName" value="<?php echo $editName; ?>"/></td>
                    </tr>
                    <tr>
                        <td>Surname:</td>
                        <td><input type="text" id="editSurname" name="editSurname"
                                   value="<?php echo $editSurname; ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>Mail:</td>
                        <td><input type="email" id="editEmail" name="editEmail" value="<?php echo $editMail; ?>"/>
                    </tr>
                </table>
                <p>
                    <input class="button-success pure-button" type="submit" name="editPerson" value="Edit person"/>
                    <input type="hidden" id="selectedPersonId" name="selectedPersonId"/>
                    <input class="pure-button" type="button" name="cancelButton" value="Cancel"/>
                </p>

                <p class="error" id="errMsg2"></p>
            <span class="error"><?php global $errMsg2;
                echo $errMsg2 ?></span>

                <h4>Groups: </h4>

                <?php
                $ret = selectGroupsByUser($selectedPerson);
                if ($ret != false) {
                    echo "<table class=\"pure-table\">";
                    while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                        echo "<tr>";
                        $rowName = $row["name"];
                        $rowId = $row["id"];
                        echo "<td>$rowName</td>";
                        echo "<td><input type=\"checkbox\" name=\"check_list_groups[]\" value=\"$rowId\"></td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                }
                ?>
                <input class="button-success pure-button" type="submit" name="unsubscribePerson"
                       value="Unsubscribe"/>
            </form>
        </div>

        <?php
        echo "<script>hideAll('manageSelectedPersonDiv', 'allPersonsDiv','addPersonDiv')</script>";
        if (isset($_POST["addPerson"])) {
            echo "<script>reloadShowAndHideDiv('addPersonDiv', 'allPersonsDiv','manageSelectedPersonDiv')</script>";
        } else if (isset($_POST["editPerson"])) {
            echo "<script>reloadShowAndHideDiv('manageSelectedPersonDiv', 'allPersonsDiv','addPersonDiv')</script>";
        } else if (isset($_POST["removePerson"])) {
            echo "<script>reloadShowAndHideDiv('allPersonsDiv', 'addPersonDiv','manageSelectedPersonDiv')</script>";
        } else if (isset($_POST["addPersonsToGroup"])) {
            echo "<script>reloadShowAndHideDiv('allPersonsDiv', 'addPersonDiv','manageSelectedPersonDiv')</script>";
        } else if (isset($_POST["unsubscribePerson"])) {
            echo "<script>reloadShowAndHideDiv('manageSelectedPersonDiv', 'allPersonsDiv','addPersonDiv')</script>";
        } else if (isset($_POST["selectPerson"])) {
            echo "<script>reloadShowAndHideDiv('manageSelectedPersonDiv', 'allPersonsDiv','addPersonDiv')</script>";
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
    var e = document.getElementById("selectPerson").value;
    document.getElementById("selectedPersonId").value = e;
</script>
<script src="js/ui.js"></script>
</body>
</html>