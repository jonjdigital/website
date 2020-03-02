<?php
include "includes/header.php";
if(isset($_POST['submit'])){
    login($_POST['uname'],$_POST['pswd']);
}
?>
<div id="icon" style="font-size: 30px; text-align: center">
    <span class="fa fa-user-alt"></span>
    <h4>Login</h4>
</div>

<div id="form">
    <form action="" method="post">
        <div id="form-uname">
            <label for="uname">Username or Email (Usernames begin with the @ symbol)</label>
            <input type="text" id="uname" name="uname" required <?php if(isset($_POST['uname'])) echo "value='".$_POST['uname']."'"?>><?php if(!empty($usernameCheck))echo $usernameCheck;?>
        </div>
        <br>
        <div id="form-pswd">
            <label for="pswd">Password</label>
            <input type="password" id="pswd" name="pswd" required>
        </div>
        <br>
        <input type="submit" value="Login" name="submit" id="submit_button" style="width: 100%">
    </form>
</div>


<?php
include "includes/footer.php";
?>