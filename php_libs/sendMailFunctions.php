<?php

include "selectFunctions.php";
include "utils.php";
include("Mail.php");
include "phpmailer/PHPMailerAutoload.php";

function prepare_data($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function prepare_multicolumn_data($data)
{
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function sendMailPhpMailer($p_email, $p_cc, $p_bcc, $p_subject, $p_message, $p_attachments, $projectId = 0, $attachEmail = "", $additionalMsg = "")
{
    $mail = new PHPMailer(true);
    $mail->CharSet = "UTF-8";

    date_default_timezone_set('Etc/UTC');

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    $mail->Username = "myyychal@gmail.com";

    $file = file_get_contents('../../0192837465', true);
    $mail->Password = $file;

    $mail->setFrom('from@example.com', 'First Last');
    $mail->addReplyTo('replyto@example.com', 'First Last');

    $p_emails = explode(",", $p_email);
    $p_ccs = explode(",", $p_cc);
    $p_bccs = explode(",", $p_bcc);

    if (!empty($p_emails)) {
        foreach ($p_emails as $email) {
            if (strpos($email, '@')) {
                $mail->addAddress($email);
            }
        }
    }

    if (!empty($p_ccs)) {
        foreach ($p_ccs as $email) {
            if (strpos($email, '@')) {
                $mail->addCC($email);
            }
        }
    }

    if (!empty($p_bccs)) {
        foreach ($p_bccs as $email) {
            if (strpos($email, '@')) {
                $mail->addBCC($email);
            }
        }
    }

    if (!empty($p_attachments)) {
        foreach ($p_attachments["error"] as $userfile => $error) {
            if ($error == UPLOAD_ERR_OK) {
                $tmp_name = $p_attachments["tmp_name"][$userfile];
                $name = $p_attachments["name"][$userfile];
                $mail->addAttachment($tmp_name, $name);
            }
        }
    }

    $mail->Subject = $p_subject;

    if (empty($p_message)) {
        $p_message = " ";
    }

    if ($projectId != 0) {
        $unsubscribeLink = curPageURLMain();
        $unsubscribeLink .= "/SendMailSimple/unsubscribeFromLink.php?projectId=$projectId";
        $p_message .= "<br/><br/><br/> Unsubscribe: $unsubscribeLink";
    }

    if (!empty($attachEmail)) {
        $link = md5($attachEmail . "BOMBA");
        $unsubscribeLink = curPageURLMain();
        $unsubscribeLink .= "/SendMailSimple/unsubscribeFromLink.php?email=$link&projectId=$projectId&$additionalMsg=1";
        $p_message = "Unsubscribe: $unsubscribeLink";
    }

    $order = array("\r\n", "\n", "\r");
    $replace = '<br />';
    $p_message = str_replace($order, $replace, $p_message);

    $mail->msgHTML($p_message);

    if ($mail->send()) {
        return true;
    } else {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }

}

function sendMailPEAR($p_email, $p_cc, $p_bcc, $p_subject, $p_message, $p_attachments)
{
    $recipients = $p_email . "," . $p_bcc;
    $headers["From"] = "who@whatever";
    $headers["To"] = $p_email;
    $headers["Reply-To"] = $p_email;
    $headers["Subject"] = $p_subject;
    $mailmsg = $p_message;
    $params['sendmail_path'] = '"C:\\xampp\\sendmail\\sendmail.exe\\';
    $mail_object =& Mail::factory('sendmail', $params);
    $mail_object->send($recipients, $headers, $mailmsg);
}

function sendMail($p_email, $p_cc, $p_bcc, $p_subject, $p_message, $p_attachments)
{
    $email = prepare_data($p_email);
    $ccEmail = prepare_data($p_cc);
    $bccEmail = prepare_data($p_bcc);
    $subject = prepare_data($p_subject);
    $message = prepare_multicolumn_data($p_message);

    $mime_boundary = "==Multipart_Boundary_x" . md5(mt_rand()) . "x";

    $headers = "From: SimpleMailSystem\r\n" .
        "Cc: $ccEmail\r\n" .
        "Bcc: $bccEmail\r\n" .
        "MIME-Version: 1.0\r\n" .
        "Content-Type: multipart/mixed;\r\n" .
        " boundary=\"{$mime_boundary}\"";

    $message = "This is a multi-part message in MIME format.\n\n" .
        "--{$mime_boundary}\n" .
        "Content-Type: text/plain; charset=\"iso-8859-1\"\n" .
        "Content-Transfer-Encoding: 7bit\n\n" .
        $message . "\n\n";

    foreach ($p_attachments["error"] as $userfile => $error) {
        if ($error == UPLOAD_ERR_OK) {
            $tmp_name = $p_attachments["tmp_name"][$userfile];
            $name = $p_attachments["name"][$userfile];
            $type = $p_attachments["type"][$userfile];
            $size = $p_attachments["size"][$userfile];

            if (file_exists($tmp_name)) {

                $file = fopen($tmp_name, 'rb');

                $data = fread($file, filesize($tmp_name));

                fclose($file);

                $data = chunk_split(base64_encode($data));

                $message .= "--{$mime_boundary}\n" .
                    "Content-Type: {$type};\n" .
                    " name=\"{$name}\"\n" .
                    "Content-Disposition: attachment;\n" .
                    " filename=\"{$name}\"\n" .
                    "Content-Transfer-Encoding: base64\n\n" .
                    $data . "\n\n";
            }
        }
    }

    $message .= "--{$mime_boundary}--\n";

    return mail($email, $subject, $message, $headers);
}

function getEmailAddressesFromProject($projectId)
{
    $ret = selectUsersByProjectAndExcludeUnsubscribers($projectId);
    $emailAddresses = "";
    while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
        $emailAddresses = $emailAddresses . "," . $row["email"];
    }
    return $emailAddresses;
}

?>