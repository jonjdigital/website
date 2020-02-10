<?php

//include "constants.php";
//require "db.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require $_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php";

##token gen
function tokenGen() {
    $length = rand(25,100);
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

##db query
function userQuery($query){
    global $user_con;
    $res = mysqli_query($user_con, $query);
//    var_dump($res);
    if($res){
        return $res;
    }else{
        return mysqli_error($user_con);
    }
}

##get current system maintenance config value
function getMaintVal(){
    global $sys_con;
    $maintQuery = "select 'maintenance' from ".SYST_DB.".system_maintenance";
    $res = mysqli_query($sys_con, $maintQuery);
    if($res){
        while($row = mysqli_fetch_assoc($res)){
            return $row['maintenance'];
        }
    }else{
        return mysqli_error($sys_con);
    }
}

function escapeString($str){
    global $user_con;
    return mysqli_real_escape_string($user_con, $str);
}

##create a new user
function save_new_user($fname, $sname, $uname, $email, $pswd){
    ###generate hashed password
    $hash = crypt($pswd, '$6$rounds=1000$J0nJD1g1t4lS1t3s$');

    ###generate random token string
    $token = tokenGen();

    ###save the user details to the user database
    $query = "insert into ".USER_DB.".user (username, email, password_hash, validation_token, reset_token) values ('$uname','$email','$hash','$token', null)";
    $insert_res = userQuery($query);

    if($insert_res){
        ###get user info
        $esc_email = escapeString($email);
        $user_query = "select * from ".USER_DB.".user where email = '$esc_email'";
        $user_res = userQuery($user_query);
//        var_dump($user_res);
        while($row = mysqli_fetch_assoc($user_res)){
            $user_id = $row['user_id'];
            $validate = $row['validation_token'];
        }
        $query2 = "insert into ".USER_DB.".profile (user_id, firstname, lastname) values ('$user_id','$fname','$sname')";
        $res2 = userQuery($query2);
//        var_dump($res2);
        if($res2) {
            ##get user profile info
            $profile_query = "select * from ".USER_DB.".profile where user_id = ".$user_id;
            $profile_res = userQuery($profile_query);
            while ($row = mysqli_fetch_assoc($profile_res)) {
                $name = $row['firstname'] . ' ' . $row['lastname'];
            }
            send_new_user_email($email, $name, $uname, $token);
//            var_dump($profile_res);
        }
    }


}

/**
 * Mail Functions
 */

function send_new_user_email($email, $fname, $uname, $token){
    $mail = new PHPMailer(true);
//    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = HOST;                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = USERNAME;                     // SMTP username
    $mail->Password   = PASSWORD;                               // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $mail->Port       = PORT;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom(USERNAME, 'Support');
    $mail->addAddress($email);     // Add a recipient
    $mail->addAddress(USERNAME);

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Welcome to JonJDigital';
    $mail->Body    = '
    Hi '.$fname.',
    <br><br>
    You have received this email because you have signed up to the website <a href="https://www.jonjdigital.com">JonJDigital.com</a>.
    <br><br>
    Please follow this link to activate your account:
    <br>
    <a href="'.$_SERVER['HTTP_ORIGIN'].'/activate.php?token='.$token.'">Activate!</a>
    <br><br>
    If the link above doesnt work, please copy and paste the following into your browers:
    <br>'.$_SERVER['HTTP_ORIGIN'].'/activate.php?token='.$token.'
    <br><br>
    Please find below your login email/username:<br><br>
    Username: @'.$uname.'<br>
    Email: '.$email.'
    <br><br>
    If you did not recall signing up, then please contact <a href="mailto:webmaster@jonjdigital.com">webmaster@jonjdigital.com</a>.
    <br><br>
    Kind Regards,
    <br><br>
    Jon James
    <br><i>System Developer and Admin</i>
    ';
    $mail->AltBody = '
    Hi '.$fname.',
    
    You have received this email because you have signed up to the website JonJDigital.com.
    
    Please follow this link to activate your account:
    '.$_SERVER['HTTP_ORIGIN'].'/activate.php?token='.$token.'">
    
    Please find below your login email/username:
    Username: @'.$uname.'
    Email: '.$email.'
    
    If you did not recall signing up, then please contact webmaster@jonjdigital.com.

    Kind Regards,
    
    Jon James
    System Developer and Admin
    ';

    $mail->send();
}


###User creation functions###
function checkEmail($email){
    $emailQuery = "select * from ".USER_DB.".user where email ='".$email."'";
    $emailRes = userQuery($emailQuery);
    if(mysqli_num_rows($emailRes) >= 1){
        return false;
    }else{
        return true;
    }
}

function checkUsername($username){
    $emailQuery = "select * from ".USER_DB.".user where username ='".$username."'";
    $emailRes = userQuery($emailQuery);
    if(mysqli_num_rows($emailRes) >= 1){
        return false;
    }else{
        return true;
    }
}