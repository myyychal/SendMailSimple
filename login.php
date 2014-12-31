<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>SMS - Login</title>
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
<?php include 'php_libs/loginFunctions.php'; ?>

<?php
$errMsg = $loginErr = $passwdErr = "";
$successLogin = "";
$login = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST["login"];
    $password = $_POST["password"];
    if (empty($_POST["login"])) {
        $loginErr = "You have to fill in this field.";
    }
    if (empty($_POST["password"])) {
        $passwdErr = "You have to fill in this field";
    }
    if (checkLoginAndPassword($_POST["login"], $_POST["password"])) {
        loginUser($_POST["login"], $_POST["password"]);

        $url = "index.php";
        $successLogin = "You'll be redirected in 2 seconds";
        header("refresh:2; url=$url");
    } else {
        $errMsg = "Incorrect login or password";
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

            <h2>Login</h2>
        </div>
        <div class="content">
            <form class="pure-form pure-form-stacked" name="loginForm" method="post"
                  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <p>
                    Username: <input type="text" name="login" tabindex="1" value="<?php echo $login; ?>"/>
        <span class="error"><?php global $loginErr;
            echo $loginErr; ?></span>
                </p>

                <p>
                    Password: <input type="password" name="password" tabindex="2" value="<?php echo $password; ?>"/>
        <span class="error"><?php global $passwdErr;
            echo $passwdErr; ?></span>
                </p>

                <p>
                    <input class="button-success pure-button" type="submit" name="loginButton" value="Sign in"
                           tabindex="3"/>
                    <input class="pure-button" type="button" name="cancelButton" value="Cancel" tabindex="4"/>
                </p>
    <span class="error"><?php global $errMsg;
        echo $errMsg ?></span>
    <span class="success"><?php global $successLogin;
        echo $successLogin ?></span>
            </form>

            <a class="button-secondary pure-button" href="index.php">Back to menu</a>
        </div>
    </div>
</div>
<script src="js/ui.js"></script>
</body>
</html>