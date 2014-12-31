<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <script src="js/checkFields.js"></script>
    <script src="js/uploadList.js"></script>
    <script src="js/utils.js"></script>
    <title>SMS - Send mail</title>
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
include 'php_libs/sendMailFunctions.php';
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

            <h2>Send mail</h2>
        </div>
        <div class="content">
            <?php

            $emailErr = "";
            $email = $ccEmail = $bccEmail = $subject = $message = "";

            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                ?>

                <form class="pure-form pure-form-stacked" name="mailForm" method="post"
                      onsubmit="return checkMailFields()"
                      enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <fieldset>
                        <table class="pure-table mail-table">
                            <tr>
                                <td>
                                    <label for="email">Email</label>
                                    <input placeholder="Email" id="email" name="email" type="text"
                                           value="<?php echo $email; ?>"/>

                                    <p class="error" id="emailErr"></p><input id="toList" name="toList" type="file"
                                                                              value="Upload list"
                                                                              accept="text/plain"
                                                                              onchange="uploadList('toList', 'email')"/>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label for="ccEmail">Cc </label>
                                    <input placeholder="Cc" id="ccEmail" name="ccEmail" type="text"
                                           value="<?php echo $ccEmail; ?>"/>

                                    <p class="error" id="ccEmailErr"></p><input id="ccList" name="ccList" type="file"
                                                                                value="Upload list"
                                                                                accept="text/plain"
                                                                                onchange="uploadList('ccList', 'ccEmail')"/>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label for="bccEmail">Bcc</label>
                                    <input placeholder="Bcc" id="bccEmail" name="bccEmail" type="text"
                                           value="<?php echo $bccEmail; ?>"/>

                                    <p class="error" id="bccEmailErr"></p><input id="bccList" name="bccList" type="file"
                                                                                 value="Upload list"
                                                                                 accept="text/plain"
                                                                                 onchange="uploadList('bccList', 'bccEmail')"/>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label for="subject">Subject</label>
                                    <input placeholder="Subject" id="subject" name="subject" type="text"
                                           value="<?php echo $subject; ?>"/></td>
                            </tr>

                            <tr>
                                <td>
                                    <label for="message">Message</label>
                                    <textarea placeholder="Message" id="message" name="message"
                                              value="<?php echo $message; ?>"></textarea></td>
                            </tr>
                        </table>
                        <table class="pure-table mail-table">
                            <tr>
                                <td>
                                    <label for="file">Attachments</label>

                                    <div id="files">
                                        <input type="file" name="item_file[]"/></br>
                                    </div>
                                    <a class="pure-button" href="javascript:_add_more();" title="Add more">Add more</a>
                                </td>
                            </tr>
                        </table>
                        <p>
                            <input class="button-success pure-button" type="submit" name="send" value="Send"/>
                            <input class="pure-button" type="reset" name="reset" value="Reset"/>
                            <input class="pure-button" type="button" name="cancel" value="Cancel"/>
                        </p>
                    </fieldset>
                </form>

            <?php
            } else {
                loginFirstMsg();
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                if (sendMailPhpMailer($_POST["email"], $_POST["ccEmail"], $_POST["bccEmail"], $_POST["subject"], $_POST["message"], $_FILES["item_file"])) {
                    echo "<script> alert(\"Message successfully sent!\")</script>";
                } else {
                    echo "<script> alert(\"Message delivery failed...\")</script>";
                }

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

