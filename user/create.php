<?php
include "../includes/header.php";
$emailCheck = null;
$usernameCheck = null;
if(isset($_POST['submit'])){
    if(checkEmail($_POST['email'])){
        if(checkUsername($_POST['uname'])){
            save_new_user($_POST['fname'], $_POST['sname'], $_POST['uname'], $_POST['email'], $_POST['pswd']);
            echo "<p style='color:darkgreen;text-align: center; font-weight:bold'>Account created Successfully. Please check your inbox for an activation email</p>";
        }else{
            $usernameCheck = "<p style='color:red; font-weight:bold;'>This username has already been taken. Please choose a different username</p>";
    }
    }else{
        $emailCheck = "<p style='color:red; font-weight:bold;'>This email is already in use. Please choose a different email</p>";
    }
    #save_new_user($_POST['fname'], $_POST['sname'], $_POST['uname'], $_POST['email'], $_POST['pswd']);
}
?>
<div id="icon" style="font-size: 30px; text-align: center">
    <span class="fa fa-user-alt"></span>
    <h4>New User</h4>
</div>
    <form action="create.php" method="post" name="test">

        <div id="form-email">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" <?php if(isset($_POST['email'])) echo "value='".$_POST['email']."'"?>><?php if(!empty($emailCheck))echo $emailCheck;?>
        </div>
        <div id="form-uname">
            <label for="uname">Username</label>

            <input type="text" id="uname" name="uname" required <?php if(isset($_POST['uname'])) echo "value='".$_POST['uname']."'"?>><?php if(!empty($usernameCheck))echo $usernameCheck;?>
        </div>
        <div id="form-fname">
            <label for="fname">First Name</label>
            <input type="text" id="fname" name="fname" required <?php if(isset($_POST['fname'])) echo "value='".$_POST['fname']."'"?>>
        </div>
        <div id="form-sname">
            <label for="sname">Last Name</label>
            <input type="text" id="sname" name="sname" required <?php if(isset($_POST['sname'])) echo "value='".$_POST['sname']."'"?>>
        </div>
        <div id="form-pswd">
            <label for="pswd">Password</label>
            <input type="password" id="pswd" name="pswd" required>
        </div>
        <div id="form-cpswd">
            <label for="cpswd">Confirm Password</label>
            <input type="password" id="cpswd" name="cpswd" required>
        </div>
        <br>
        <input type="submit" value="Register" name="submit" style="width: 100%">
    </form>
<?php
include "../includes/footer.php";
?>