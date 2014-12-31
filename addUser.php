<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>SMS - Add user</title>
    <script src="js/checkFields.js"></script>
    <link rel="stylesheet" href="css/pure-min.css">
    <!--[if lte IE 8]>
    <link rel="stylesheet" href="css/layouts/side-menu-old-ie.css">
    <![endif]-->
    <!--[if gt IE 8]><!-->
    <link rel="stylesheet" href="css/layouts/side-menu.css">
    <!--<![endif]-->
</head>
<?php
session_start();
?>
<body>
<?php include 'php_libs/insertFunctions.php'; ?>
<?php
$newUsernameErr = $newPasswdErr = "";
$newUsername = $newPassword = "";
$errMsg = $successLogin = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newUsername = $_POST["newUsername"];
    $newPassword = $_POST["newPassword"];
    if (empty($_POST["newUsername"])) {
        $newUsernameErr = "You have to fill in this field.";
    }
    if (empty($_POST["newUsername"])) {
        $newPasswdErr = "You have to fill in this field";
    }
    if (addUser($newUsername, $newPassword)) {
        $successLogin = "New user was added";
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

            <h2>Add user</h2>
        </div>
        <div class="content">

            <form class="pure-form pure-form-stacked" name="addUserForm" method="post"
                  onsubmit="return checkUserFields()"
                  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <p>
                    Username: <input type="text" id="newUsername" name="newUsername" tabindex="1"
                                     value="<?php global $newUsername;
                                     echo $newUsername; ?>"/>
                    <span class="error"><?php echo $newUsernameErr; ?></span>
                </p>

                <p>
                    Password: <input type="password" id="newPassword" name="newPassword" tabindex="2"
                                     value="<?php global $newPassword;
                                     echo $newPassword; ?>"/>
                    <span class="error"><?php echo $newPasswdErr; ?></span>
                </p>

                <p>
                    <input class="button-success pure-button" type="submit" name="addUserButton" value="Add user"
                           tabindex="3"/>
                    <input class="pure-button" type="button" name="cancelButton" value="Cancel" tabindex="4"/>
                </p>

                <p id="errMsg"></p>
            </form>

<span class="error"><?php global $errMsg;
    echo $errMsg ?></span>
<span class="success"><?php global $errMsg;
    echo $successLogin ?></span>

            <p>
                <a class="button-secondary pure-button" href="index.php">Back to menu</a>
            </p>
        </div>
    </div>
</div>
<script src="js/ui.js"></script>
</body>
</html>