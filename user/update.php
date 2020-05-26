<?php
include "../includes/header.php";
$modRoles = [
    'Moderator',
    'Admin',
    'Owner'
];

$email_msg = null;
$fname_msg = null;
$sname_msg = null;

if(($_COOKIE['user_id'] !== $_GET['id'])&&(!in_array("Admin",$_SESSION['roles'])&&!in_array("Owner",$_SESSION['roles']))){
    header("Location: /");
}
$info = getAllUserInfo($_GET['id']);
if($info['status'] == "1"){
    $info['status'] = "Active";
}else{
    $info['status'] = "Inactive";
}

$info['acc_activated'] = date("D d F Y G:i", $info['acc_activated']);

echo "<pre>";
var_dump($info);
echo "</pre>";

if(isset($_POST['submit'])){
    //check details against submitted ones to check differences
    /**
     * email
     * forename
     * surname
     */
    $sub_email = escapeString($_POST['email']);
    $sub_forename = escapeString($_POST['forename']);
    $sub_surname = escapeString($_POST['surname']);

    if($sub_forename != $info['firstname']){
        $sql = "update ".$user_db_name.".profile set firstname = '"
            .$sub_forename."' where user_id = ".$_COOKIE['user_id'];
        userQuery($sql);
        $fname_msg = "<p style='text-align: center; color: darkgreen'>First name updated<br></p>";
        header("Location: ".$_SERVER['REQUEST_URI']);
    }

    if($sub_surname != $info['lastname']){
        $sql = "update ".$user_db_name.".profile set lastname = '"
            .$sub_surname."' where user_id = ".$_COOKIE['user_id'];
        userQuery($sql);
        $sname_msg = "<p style='text-align: center; color: darkgreen'>Last name updated<br></p>";
        header("Location: ".$_SERVER['REQUEST_URI']);
    }

    if($sub_email != $info['email']){
        if(checkEmail($sub_email)){
            $token = change_email($info,$sub_email);
            $email = $info['email'];
            $fname = $info['firstname'];
//            verify_email($email,$fname,$sub_email,$token);
            echo "<script>alert('$token')</script>";

        }
    }
    //echo "<script>alert('Details Submitted')</script>";
}
?>

<h1>Edit User: @<?php echo $info['username']?></h1>

<form action="update.php?id=<?php echo $_GET['id']?>" method="post"><!-- class="form-inline"-->
    <!--display username-->
    <label for="username">Username (Not Editable):</label>
    <input type="text" id="username" name="username" value="@<?php echo $info['username']?>" disabled>
    <hr>
    <!--display email !! RUN EMAIL CHECK BEFORE SAVING !!-->
    <?php if($email_msg !== null)echo $email_msg;?>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo $info['email']?>">
    <hr>
    <!--display names-->
    <?php if($fname_msg !== null)echo $fname_msg;?>
    <label for="forename">First Name:</label>
    <input type="text" id="forename" name="forename" value="<?php echo $info['firstname']?>">
    <?php if($sname_msg !== null)echo $sname_msg;?>
    <label for="surname">Last Name: </label>
    <input type="text" id="surname" name="surname" value="<?php echo $info['lastname']?>">
    <hr>
    <!--display Password !! CHECK PASSWORD IS CORRECT BEFORE SAVING !!-->
    <label for="password">Please enter your password to confirm these changes:</label>
    <input type="password" id="password" name="password" placeholder="Password" required>
    <br>
    <input type="submit" value="Update" name="submit" id="submit_button" style="width: 100%">

</form>

<?php
include "../includes/footer.php";
?>