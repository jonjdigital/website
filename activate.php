<?php
include "includes/header.php";
global $user_con;
$result_msg = 0;

$token = $_GET['token'];
//echo "<h1>Hello: ".$token."</h1>";

##find the user which corresponds to this token
$query = "select * from ".USER_DB.".user where validation_token ='".$token."'";
$user_info = userQuery($query);

if($user_info) {
    while ($row = mysqli_fetch_assoc($user_info)) {
        $username = $row['username'];
        $email = $row['email'];
        $status = $row['status'];
    }

    if (isset($status)) {
        if ($status == 0) { ##check if Acc is new
            $stat_query = "update " . USER_DB . ".user set status = 1, acc_activated = " . time() . " where validation_token ='" . $token . "'";
            $status_upd = userQuery($stat_query);
            if (!$status_upd) {
                echo "Status Update Failure: " . mysqli_error($user_con);
            }
            ###get user id from token
            $id_query = "select * from ".USER_DB.".user where token = '".$token."'";
            $id_res = userQuery($id_query);
            while($row = mysqli_fetch_assoc($id_res)){
                $id = $row['user_id'];
                $acc_right_query =  "insert into ".USER_DB.".access_rights ('user_id','access_right_id') values ('$id',1)";
                if(!userQuery($acc_right_query)) {
                    echo mysqli_error($user_con);
                }
            }
            $result_msg = 1; ###Acc now activated
        } else if ($status == 1) {
            $result_msg = 2; ###Acc already activated
        } else if(($status == 2)){
            $result_msg = 3; ###Acc disabled
        } else if(($status == 3)){
            $result_msg = 4; ###Acc deleted
            $deleted_date = $row['delete_on'];
        }
    }

}

if(!$user_info){
    echo "MYSQL Error: ".mysql_error($user_con);
}

if($result_msg == 0){
    echo "
    <h3 style='text-align: center'>There has been an error when activating this account. Please contact the following email for assistance
    <br><br><br><br>
    <a href='mailto:".USERNAME.">'>".USERNAME."</a>
    <br><br><br><br>
    Sorry for any inconvenience.
    <br><br>
    Kind Regards,<br>
    Jon James</h3>
    ";
}else if($result_msg == 1){
    echo "<h3 style='text-align: center'>Your account has now been activated.
    <br><br>
    You can now log in using your email/username.
    <br><br>
    Kind Regards,<br>
    Jon James</h3>
    ";

    /**
     * Need to add an email that sends user rules for using the site, and warns that any breaches will result in account suspension
     */
}else if($result_msg == 2){
    echo "<h3 style='text-align: center'>Your account has already been activated.
    <br><br>
    Please log in using your email/username.
    <br><br>
    If you are having any issue logging in then please contact me via email to reset your password.
    <br><br>
    <a href='mailto:".USERNAME.">'>".USERNAME."</a>
    <br><br>
    Kind Regards,<br>
    Jon James</h3>
    ";
}else if($result_msg == 3){
    echo "<h3 style='text-align: center'>Your account has been disabled by an admin.
    <br><br>
    If you believe this is in error, then please contact me via email for help in recovering your account.
    <br><br>
    <a href='mailto:".USERNAME.">'>".USERNAME."</a>
    <br><br>
    Kind Regards,<br>
    Jon James</h3>
    ";
}else if($result_msg == 4){
    echo "<h3 style='text-align: center'>Your account has been Deleted.
    <br><br>
    Unforunately we have deleted all of your data regarding your public profile, and remaining data will be deleted within 6 moths of the below date.
    <br><br>
    ".date('D d M Y',$deleted_date)."
    <br><br>
    Sorry for any inconvenience.
    <br><br>
    <a href='mailto:".USERNAME.">'>".USERNAME."</a>
    <br><br>
    Kind Regards,<br>
    Jon James</h3>
    ";
}
include "includes/footer.php";