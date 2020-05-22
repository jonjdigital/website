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

function webQuery($query){
    global $web_con;
    $res = mysqli_query($web_con, $query);
//    var_dump($res);
    if($res){
        return $res;
    }else{
        return mysqli_error($web_con);
    }
}

##get current system maintenance config value
function getMaintVal(){
    global $sys_con;
    $maintQuery = "select * from ".SYST_DB.".system_configs where config = 'maintenance'";
    $res = mysqli_query($sys_con, $maintQuery);
    if($res){
        while($row = mysqli_fetch_assoc($res)){
            return $row['value'];
        }
    }else{
        return mysqli_error($sys_con);
    }
}

function escapeString($str){
    global $user_con;
    return mysqli_real_escape_string($user_con, $str);
}

function debug($var){
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
}

##create a new user
function save_new_user($fname, $sname, $uname, $email, $pswd){
    ###generate hashed password
//    $hash = crypt($pswd, '$6$rounds=1000$J0nJD1g1t4lS1t3s$');
    $hash = password_hash($pswd, 1,["J0nJD1g1t@l","6"]);
    ###generate random token string
    $token = tokenGen();

    ###ESCAPE THE STRINGS FOR SECURITY
    $hash = escapeString($hash);
    $email = escapeString($email);
    $fname = escapeString($fname);
    $sname = escapeString($sname);
    $uname = escapeString($uname);

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
    You have received this email because you have signed up to the website <a href="https://www.jonjdigital.test">JonJDigital.com</a>.
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

function change_email($info, $email){
    $id = $info['user_id'];
    $email = escapeString($email);
    //update the user table with the new email, changing email_validated to 0
    $update_user_sql = "update ".USER_DB.".user set email='$email', email_validated=0 where user_id = $id";
    $res = userQuery($update_user_sql);
    if(!$res){
        echo "<script>alert($res)</script>";
    };
    $token = escapeString(tokenGen());
    $insert_validation_sql = "insert into ".USER_DB.".email_verify ('user_id', 'token') values ('$id','$token')";
    $res1 = userQuery($insert_validation_sql);
    /*if(!$res1) {
        header("Location: https://www.google.com");
    };
    return $insert_validation_sql;*/
    header("Location: $insert_validation_sql");

}

function verify_email($email, $fname, $nemail, $verify_token)
{
    $link = $_SERVER['HTTP_ORIGIN'].'/user/validate.php?token='.$verify_token;
    $mail = new PHPMailer(true);
//    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host = HOST;                    // Set the SMTP server to send through
    $mail->SMTPAuth = true;                                   // Enable SMTP authentication
    $mail->Username = USERNAME;                     // SMTP username
    $mail->Password = PASSWORD;                               // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $mail->Port = PORT;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom(USERNAME, 'Support');
    $mail->addAddress($email);     // Add a recipient
    $mail->addAddress(USERNAME);

    //email content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Verify Email Change';
    $mail->Body =
        "Hi $fname,
        <br><br>
        You have requested a change in your email address.
        <br><br>
        To change your email to:
        <br>
        $nemail
        <br><br>
        Please follow this <a href=$link>link.</a>
        <br><br>
        If the above link doesnt work please copy and paste the below URL into your address bar at the top of your browser.
        <br>
        $link
        <br><br><br>
        If you believe this is in error, please contact an admin at <a href='mailto:help@jonjdigital.com'>help@jonjdigital.com</a>.
        <br><br>
        Kind Regards
        <br><br>
        Jon James";
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

##user log in function##
function login($id, $password){
    #$id is username or email
    $id = escapeString($id);
    ##determine if entered id is username or email
    $first_char = $id[0];
    if($first_char != "@"){
        $user_query = "select * from ".USER_DB.".user where email = '".$id."'";
    }else{
        $uname = substr($id,1);
        //var_dump($uname);
        $user_query = "select * from ".USER_DB.".user where username = '".$uname."'";
    }

    $result = userQuery($user_query);
//    print_r($result);

    while($row = mysqli_fetch_assoc($result)){
        //var_dump($row['user_id']);
        $hash = $row['password_hash'];
        $verify = password_verify($password,$hash);

        if($verify){
            $user_id = $row['user_id'];
            echo $profile_info = "select * from ".USER_DB.".profile where user_id = '".$user_id."'";
            $profile_res = userQuery($profile_info);
            $profile = mysqli_fetch_assoc($profile_res);
            //print_r($profile);

            $fname = $profile['firstname'];
            $sname = $profile['lastname'];
            $uname = $row['username'];
            $user_id = $row['user_id'];
            $_SESSION['user_id'] = $user_id;
            $_SESSION['fname'] = $fname;
            $_SESSION['sname'] = $sname;
            $_SESSION['uname'] = $uname;
            $_SESSION['user_id'] = $user_id;
//            var_dump($_SESSION);
            //echo "<script>alert('Logged In')</script>";
            checkAccess($user_id);
            return true;
        }else{
            return false;
        }
    }
}

function logout(){
    session_destroy();
    header("Location: /");
//    header($_SERVER['HTTP_HOST']);
}

function checkAccess($id){
    $roles = [];
    /**
     * ##access right constants##
    const ACC_OWNER = 0;
    const ACC_PUBLIC = 1;
    const ACC_ADMIN = 2;
    const ACC_CONTENT_CREATOR = 3;
    const ACC_MODERATOR = 4;
     */
    $acc_query = "select * from ".USER_DB.".access_rights where user_id = '".$id."'";
    $acc_res = userQuery($acc_query);
    while($row = mysqli_fetch_assoc($acc_res)){
        if ($row['access_right_id'] == ACC_ADMIN) {
            $roles[] = 'Admin';
        }else if ($row['access_right_id'] == ACC_PUBLIC) {
            $roles[] = 'Public';
        }else if ($row['access_right_id'] == ACC_OWNER) {
            $roles[] = 'Owner';
        }else if ($row['access_right_id'] == ACC_CONTENT_CREATOR) {
            $roles[] = 'Content Creator';
        }else if ($row['access_right_id'] == ACC_MODERATOR) {
            $roles[] = 'Moderator';
        }
    }
    $_SESSION['roles'] = $roles;
}

function getAllUserInfo($id){
    $userInfo = "Select user_id, username, email, status, acc_activated from ".USER_DB.".user where user_id = $id";
    $userRes = userQuery($userInfo);
    $profileInfo = "Select * from ".USER_DB.".profile where user_id = $id";
    $profileRes = userQuery($profileInfo);
    while($row = mysqli_fetch_assoc($userRes)){
        while($row2 = mysqli_fetch_assoc($profileRes)){
                        return array_merge($row, $row2);
        }
    }
}


function getUsersPosts($id,$limit){
    $postQuery = "select * from ".SITE_DB.".posts where user_id = $id limit";
    $postRes = webQuery($postQuery);
    return $postRes;
}

function git_pull(){
    $doc_root = $_SERVER['DOCUMENT_ROOT'];
    //"git -C '/opt/lampp/htdocs/website' pull"
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $command = "cd ".$doc_root." && git pull";
    } else {
        $command = "cd /opt/lampp/htdocs/website && git pull";
    }
    exec($command,$output);
    return $output;
//    return shell_exec($command);
}