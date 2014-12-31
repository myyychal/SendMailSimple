<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>SMS - Logout</title>
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

            <h2>Logout</h2>
        </div>
        <div class="content">
            <?php
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                session_unset();
                session_destroy();
                echo "<p>You were logged out.</p>";
            } else {
                echo "<p>You weren't logged in.</p>";
            }
            ?>

            <a class="button-secondary pure-button" href="index.php">Back to menu</a>
        </div>
    </div>
</div>
<script src="js/ui.js"></script>
</body>
</html>