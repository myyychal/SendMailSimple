<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>SMS - Manage projects</title>
    <script src="js/checkFields.js"></script>
    <script src="js/utils.js"></script>
    <link rel="stylesheet" href="css/pure-min.css">
    <!--[if lte IE 8]>
    <link rel="stylesheet" href="css/layouts/side-menu-old-ie.css">
    <![endif]-->
    <!--[if gt IE 8]><!-->
    <link rel="stylesheet" href="css/layouts/side-menu.css">
    <!--<![endif]-->
    <script src="//tinymce.cachefly.net/4.1/tinymce.min.js"></script>
    <script>tinymce.init({selector: 'textarea'});</script>
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

    <h2>Manage projects</h2>
</div>
<div class="content">
<?php
$newName = $newMessage = $newHappenDate = "";
$editName = $editMessage = $editHappenDate = "";
$errMsg = $errMsg2 = $errMsg3 = "";
$selectedProject = 0;

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    if (isset($_POST["addProject"])) {
        $newName = $_POST["newName"];
        $newMessage = $_POST["newMessage"];
        $newHappenDate = $_POST["newHappenDate"];
        if (empty($_POST["newName"])) {
            $newMailErr = "You have to fill in this field.";
        }
        if (createMailingProject($newName, $newMessage, $newHappenDate)) {
            echo "<script> alert(\"New mailing project was added.\")</script>";
        } else {
            echo "<script> alert(\"New mailing project was not added.\")</script>";
        }
    } else if (isset($_POST["removeProject"])) {
        if (!empty($_POST['check_list_remove'])) {
            $ids = $_POST['check_list_remove'];
            deleteProjects($ids);
            echo "<script> alert(\"Selected mailing projects were removed.\")</script>";
        }
    } else if (isset($_POST["addGroupToProject"])) {
        if (!empty($_POST['check_list_remove'])) {
            $ids = $_POST['check_list_remove'];
            if (!empty($_POST["chooseGroup"])) {
                $projectId = $_POST["chooseGroup"];
                if (addGroupsToProject($ids, $projectId)) {
                    echo "<script> alert(\"Chosen groups were added to selected mailing project.\")</script>";
                } else {
                    echo "<script> alert(\"Some of these groups are already linked with selected project.\")</script>";
                }
            }
        }
    } else if (isset($_POST["editProject"])) {
        $selectedProject = $_POST['selectProject'];
        reloadEditProjectFields($selectedProject);
        $editName = $_POST["editName"];
        $editMessage = $_POST["editMessage"];
        $editHappenDate = $_POST["editHappenDate"];
        if (updateProject($selectedProject, $editName, $editMessage, $editHappenDate)) {
            echo "<script> alert(\"Mailing project data was edited and saved.\")</script>";
        } else {
            echo "<script> alert(\"Mailing project data was not edited.\")</script>";
        }
    } else if (isset($_POST["unsubscribeGroup"])) {
        $selectedProject = $_POST['selectProject'];
        reloadEditProjectFields($selectedProject);
        if (!empty($_POST['check_list_groups'])) {
            $ids = $_POST['check_list_groups'];
            deleteRowsFromEventsByProject($selectedProject, $ids);
            echo "<script> alert(\"Selected project was removed from chosen groups.\")</script>";
        }
    } else if (isset($_POST["selectProject"])) {
        $selectedProject = $_POST['selectProject'];
        reloadEditProjectFields($selectedProject);
    }
    ?>

    <div class="pure-menu pure-menu-open pure-menu-horizontal">
        <ul>
            <li><a href="#"><h3 onclick="showAndHideDiv('addProjectDiv', 'allProjectsDiv', 'manageSelectedProjectDiv')"
                                onmouseover="" style="cursor: pointer;">Add mailing project</h3></a></li>

            <li><a href="#"><h3 onclick="showAndHideDiv('allProjectsDiv', 'addProjectDiv', 'manageSelectedProjectDiv')"
                                onmouseover="" style="cursor: pointer;">All projects</h3></a></li>
            <li><a href="#"><h3 onclick="showAndHideDiv('manageSelectedProjectDiv', 'addProjectDiv', 'allProjectsDiv')"
                                onmouseover="" style="cursor: pointer;">Manage selected
                        project</h3></a></li>
        </ul>
    </div>

    <!-- ------------ Add mailing project -------------------------------------------------------------------->
    <div id="addProjectDiv">
        <form class="pure-form pure-form-stacked" name="addProjectForm" method="post"
              onsubmit="return checkProjectFields()"
              action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <table>
                <tr>
                    <td>Name:</td>
                    <td><input type="text" id="newName" name="newName" tabindex="1"
                               value="<?php echo $newName; ?>"/></td>
                            <span class="error"><?php global $newMailErr;
                                echo $newMailErr; ?></span>
                </tr>
                <tr>
                    <td>Message:</td>
                    <td><textarea name="newMessage" tabindex="2" value="<?php echo $newMessage; ?>"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>Date:</td>
                    <td><input type="date" name="newHappenDate" tabindex="3"
                               value="<?php echo $newHappenDate; ?>"/></td>
                </tr>
            </table>
            <p>
                <input class="button-success pure-button" type="submit" name="addProject"
                       value="Create new mailing project" tabindex="4"/>
                <input class="pure-button" type="button" name="cancelButton" value="Cancel" tabindex="5"/>
            </p>
        </form>

                <span class="error"><?php global $errMsg;
                    echo $errMsg ?></span>
                <span class="success"><?php global $successLogin;
                    echo $successLogin ?></span>

    </div>

    <!-- ------------ All projects -------------------------------------------------------------------->

    <div id="allProjectsDiv">
        <form class="pure-form pure-form-stacked" method="post"
              action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <?php
            $ret = selectProjects();
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
            <p><input class="button-success pure-button" type="submit" name="removeProject"
                      value="Remove selected projects" tabindex="4"/></p>

            <p>Choose groups:</p>

            <select id="chooseGroup" name="chooseGroup">
                <?php
                $ret = selectGroups();
                if ($ret != false) {
                    while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                        $rowName = $row["name"];
                        $rowId = $row["id"];
                        echo "<option value=\"$rowId\">$rowName</option>";
                    }
                }
                ?>
            </select>

            <p><input class="button-success pure-button" type="submit" name="addGroupToProject"
                      value="Add selected projects to chosen group"
                      tabindex="5"/></p>

            <p>
                        <span class="error"><?php global $errMsg3;
                            echo $errMsg3 ?></span>
            <span class="success"><?php global $successLogin3;
                echo $successLogin3 ?></span>
            </p>

        </form>
    </div>

    <!-- ------------ Manage selected project -------------------------------------------------------------------->


    <div id="manageSelectedProjectDiv">
        <form class="pure-form pure-form-stacked" id="selectPersonForm" name="selectPersonForm" method="post">
            Choose project:
            <select id="selectProject" name="selectProject" onChange="this.form.submit()">
                <?php
                $ret = selectProjects();
                if ($ret != false) {
                    while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                        echo "<option value=0></option>";
                        $rowValue = $row["name"];
                        $rowId = $row["id"];
                        if ($rowId == $selectedProject) {
                            echo "<option value=\"$rowId\" selected=\"selected\">$rowValue</option>";
                        } else {
                            echo "<option value=\"$rowId\">$rowValue</option>";
                        }
                    }
                }
                ?>
            </select>

            <table>
                <tr>
                    <td>Name:</td>
                    <td><input type="text" id="editName" name="editName" tabindex="1"
                               value="<?php echo $editName; ?>"/></td>
                            <span class="error"><?php global $editMailErr;
                                echo $editMailErr; ?></span>
                </tr>
                <tr>
                    <td>Message:</td>
                    <td><textarea name="editMessage" tabindex="2"><?php echo $editMessage ?></textarea></td>
                </tr>
                <tr>
                    <td>Date:</td>
                    <td><input type="date" name="editHappenDate" tabindex="3"
                               value="<?php echo $editHappenDate; ?>"/></td>
                </tr>
            </table>
            <p>
                <input class="button-success pure-button" type="submit" name="editProject"
                       value="Edit mailing project" tabindex="4"/>
                <input class="pure-button" type="button" name="cancelButton" value="Cancel" tabindex="5"/>
            </p>

            <p class="error" id="errMsg2"></p>
            <span class="error"><?php global $errMsg2;
                echo $errMsg2 ?></span>
            <span class="success"><?php global $successLogin2;
                echo $successLogin2 ?></span>

            <h4>Groups: </h4>
            <?php
            $ret = selectGroupsByProject($selectedProject);
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
            <input class="button-success pure-button" type="submit" name="unsubscribeGroup"
                   value="Remove groups from selected project"/>

            <h4>Unsubscribed users: </h4>
            <?php
            $ret = selectUnsubscribersByProject($selectedProject);
            if ($ret != false) {
                echo "<table class=\"pure-table\">";
                while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                    echo "<tr>";
                    $rowName = $row["name"];
                    $rowSurname = $row["surname"];
                    $rowEmail = $row["email"];
                    $rowId = $row["id"];
                    echo "<td>$rowName</td> <td>$rowSurname</td> <td>$rowEmail</td> ";
                    echo "<td><input type=\"checkbox\" name=\"check_list_unsubscribers[]\" value=\"$rowId\"></td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            ?>
        </form>
    </div>

    <?php
    echo "<script>hideAll('manageSelectedProjectDiv', 'addProjectDiv', 'allProjectsDiv')</script>";
    if (isset($_POST["addProject"])) {
        echo "<script>reloadShowAndHideDiv('addProjectDiv', 'allProjectsDiv', 'manageSelectedProjectDiv')</script>";
    } else if (isset($_POST["removeProject"])) {
        echo "<script>reloadShowAndHideDiv('allProjectsDiv', 'addProjectDiv', 'manageSelectedProjectDiv')</script>";
    } else if (isset($_POST["addGroupToProject"])) {
        echo "<script>reloadShowAndHideDiv('allProjectsDiv', 'addProjectDiv', 'manageSelectedProjectDiv')</script>";
    } else if (isset($_POST["editProject"])) {
        echo "<script>reloadShowAndHideDiv('manageSelectedProjectDiv', 'addProjectDiv', 'allProjectsDiv')</script>";
    } else if (isset($_POST["unsubscribeGroup"])) {
        echo "<script>reloadShowAndHideDiv('manageSelectedProjectDiv', 'addProjectDiv', 'allProjectsDiv')</script>";
    } else if (isset($_POST["selectProject"])) {
        echo "<script>reloadShowAndHideDiv('manageSelectedProjectDiv', 'addProjectDiv', 'allProjectsDiv')</script>";
    }
} else {
    loginFirstMsg();
}
?>

<p>
    <a class="button-secondary pure-button" href="index.php">Back to menu</a>
</p>
</div>
</div>
</div>
<script src="js/ui.js"></script>
</body>
</html>