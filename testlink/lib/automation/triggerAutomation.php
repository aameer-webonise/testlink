<?php
require 'PHPMailer-master/PHPMailerAutoload.php';

$projectName=$_POST['projectName'];
$mail = new PHPMailer;
$mail->isSMTP();
$mail->SMTPSecure = 'ssl';
$mail->SMTPAuth = true;
$mail->Host = 'smtp.gmail.com';
$mail->Port = 465;
$mail->Username = 'email@weboapps.com';
$mail->Password = 'weboqa6186';
$mail->setFrom('aameer.ausekar@gmail.com');
$mail->addAddress('email@weboapps.com');
//$mail->addAddress('ausekar9@gmail.com');
$mail->Subject = 'Trigger RP';
//$mail->Body = 'automation for RP by aameer';
if (!$mail->send()) {
    echo "ERROR: " . $mail->ErrorInfo;
} else {
    echo "Your Automation will start in 10 minutes";
}
?>