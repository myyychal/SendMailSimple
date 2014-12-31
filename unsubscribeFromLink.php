<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>SMS - Unsubscribe from link</title>
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
include 'php_libs/sendMailFunctions.php';
include 'php_libs/deleteFunctions.php';
include 'php_libs/insertFunctions.php';
?>

<?php
$projectId = "";
$projectName = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["projectId"]) && !isset($_GET["email"])) {
        $projectId = $_GET["projectId"];
        $ret = selectProjectById($projectId);
        if ($ret != false) {
            while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                $projectName = $row["name"];
            }
        }
    } else if (isset($_GET["projectId"]) && isset($_GET["email"])) {
        if (isset($_GET["subscribe"])) {
            $emailHashed = $_GET["email"];
            $email = "";
            $ret = selectPersons();
            if ($ret != false) {
                while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                    if ($emailHashed == md5($row["email"] . "BOMBA")) {
                        $email = $row["email"];
                    }
                }
            }
            $projectId = $_GET["projectId"];
            $personId = "";
            $ret = selectPersonByEmail($email);
            if ($ret != false) {
                while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                    $personId = $row["id"];
                }
            }
            if (deleteRowFromUnsubscribers($personId, $projectId)) {
                echo "<script> alert(\"You were successfully subscribed back to mailing project.\")</script>";
            } else {
                echo "<script> alert(\"You were already subscribed to mailing project.\")</script>";
            }

        } else if (isset($_GET["unsubscribe"])) {
            $emailHashed = $_GET["email"];
            $ret = selectPersons();
            if ($ret != false) {
                while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                    if ($emailHashed == md5($row["email"] . "BOMBA")) {
                        $email = $row["email"];
                    }
                }
            }
            $projectId = $_GET["projectId"];
            $personId = "";
            $ret = selectPersonByEmail($email);
            if ($ret != false) {
                while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                    $personId = $row["id"];
                }
            }
            if (addPersonToUnsubscribers($personId, $projectId)) {
                echo "<script> alert(\"You were successfully unsubscribed from mailing project.\")</script>";
            } else {
                echo "<script> alert(\"You were already unsubscribed from mailing project.\")</script>";
            }
        }
    }
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $receivedProjectId = $_POST["projectId"];
    if (isset($_POST["unsubscribe"])) {
        if (sendMailPhpMailer($email, "", "", "Unsubsribe from: $projectName", "", "", $receivedProjectId, $email, "unsubscribe")) {
            echo "<script> alert(\"Message successfully sent!\")</script>";
        } else {
            echo "<script> alert(\"Message delivery failed...\")</script>";
        }
    } else if (isset($_POST["subscribe"])) {
        if (sendMailPhpMailer($email, "", "", "Unsubsribe from: $projectName", "", "", $receivedProjectId, $email, "subscribe")) {
            echo "<script> alert(\"Message successfully sent!\")</script>";
        } else {
            echo "<script> alert(\"Message delivery failed...\")</script>";
        }
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

            <h2>Subscription management</h2>
        </div>
        <div class="content">
            <form name="unsubscribeForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <h3>Mailing project</h3>

                <p>Project: <?php echo $projectName ?></p>

                <p>Email: <input id="email" name="email" type="text" value="<?php echo $email; ?>"/></p>
                <input class="button-warning pure-button" type="submit" name="unsubscribe" value="Unsubscribe"/>
                <input class="button-success pure-button" type="submit" name="subscribe" value="Subscribe back"/>
                <input class="pure-button" type="hidden" name="projectId" value="<?php echo $projectId; ?>"/>
            </form>

            <a class="button-secondary pure-button" href="index.php">Go to main page</a>
        </div>
    </div>
</div>
<script src="js/ui.js"></script>
</body>
</html>