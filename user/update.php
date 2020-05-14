<?php
include "../includes/header.php";
$modRoles = [
    'Moderator',
    'Admin',
    'Owner'
];
if(($_SESSION['user_id'] !== $_GET['id'])&&(!in_array("Admin",$_SESSION['roles'])&&!in_array("Owner",$_SESSION['roles']))){
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
    
    echo "<script>alert('Details Submitted')</script>";
}
?>

<h1>Edit User: @<?php echo $info['username']?></h1>

<form action="" method="post"><!-- class="form-inline"-->
    <!--display username-->
    <label for="username">Username (Not Editable):</label>
    <input type="text" id="username" name="username" value="@<?php echo $info['username']?>" disabled>
    <hr>
    <!--display email !! RUN EMAIL CHECK BEFORE SAVING !!-->
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo $info['email']?>">
    <hr>
    <!--display names-->
    <label for="forename">First Name:</label>
    <input type="text" id="forename" name="forename" value="<?php echo $info['firstname']?>">
    <label for="surname">Last Name: </label>
    <input type="text" id="surname" name="surname" value="<?php echo $info['lastname']?>">
    <hr>
    <!--display Password !! CHECK PASSWORD IS CORRECT BEFORE SAVING !!-->
    <label for="password">Please enter your password to confirm these changes:</label>
    <input type="password" id="password" name="password" placeholder="Password" required>
    <br>
    <input type="submit" value="Register" name="submit" id="submit_button" style="width: 100%">

</form>

<?php
include "../includes/footer.php";
?>