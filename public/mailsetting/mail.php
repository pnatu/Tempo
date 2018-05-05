<?php

function smtpmailer123() {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $otp = isset($_POST["otp"]) ? $_POST["otp"] : '';
    $password = isset($_POST["password"]) ? trim($_POST["password"]) : '';
    $body = "";
    //$from = $_POST["from"];
    $subject = $_POST["subject"];

    $attachment = null;

    require_once 'class.phpmailer.php';
    require_once 'class.smtp.php';

    global $error;
    $mail = new PHPMailer(); // create a new object
    $mail->SMTPDebug = 1;

    $mail->IsHTML(true);

    $mail->SetFrom('test@gmail.com', 'Admin');
    $mail->IsSMTP();
    $mail->SMTPAuth = true; // enable SMTP authentication
    $mail->SMTPSecure = "ssl"; // sets the prefix to the server
    //$mail->Host = "secure.emailsrvr.com"; // sets GMAIL as the SMTP server
    $mail->Host = "smtp.gmail.com"; // sets GMAIL as the SMTP server

    $mail->Port = 465; // set the SMTP port for the GMAIL server

    $mail->Username = "1947testemail@gmail.com";  // GMAIL username
    $mail->Password = "1947test";  // GMAIL password
     
    $body = '<!DOCTYPE html>
<html>
    <head>
        <style>
            *{
                font-family: "Calibri",sans-serif;
                font-color:black
            }  
            h3{
                background-color:#888888; 
                color:#fff;
                padding:5px;
            }
            .logo_img{
              margin-left:-10px !important;

            }
            
            p{font-size:14px}
        </style>
    </head>                
    <div style=" padding: 15px 10px;width:90%;height:auto;overflow:hidden;">    
        
        <p>Dear ' . ucfirst($name) . ', </p>';
    if ($password != '')
     $body .=   '<p> Your password is ' . $password . '.</p>';
    else
     $body .=   '<p>Thank you for registering us. Your OTP is ' . $otp . '.</p>';

   $body .= '<p>Sincerely,</p>
            
        <p>The Tempo Team</p>
       </p>
    </div>

</html>';
    $mail->Subject = $subject;
    $mail->Body = $body;

    if ($attachment) {
        if (@file_exists($attachment)) {
            $mail->AddAttachment($attachment);
        }
    }
    $mail->AddAddress($email);
    // $mail->AddCC("info@ngi-med.com", '');

    $mail->Send();
    exit;
}

//sendMail();
smtpmailer123();



