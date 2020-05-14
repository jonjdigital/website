<?php
include "../includes/header.php";
if(($_SESSION['user_id'] !== $_GET['id'])&&(!in_array("Admin",$_SESSION['roles'])&&!in_array("Moderator",$_SESSION['roles'])&&!in_array("Owner",$_SESSION['roles']))){
    header("Location: /");
}
$info = getAllUserInfo($_GET['id']);
if($info['status'] == "1"){
    $info['status'] = "Active";
}else{
    $info['status'] = "Inactive";
}
var_dump($info);
?>

<h1><?php echo $info['username']?></h1>
    <br>
<h3>Name: <?php echo $info['firstname'] ." ". $info['lastname']?></h3>
<h3>Email: <?php echo $info['email']?></h3>
    <br>
<h3>Account Status: <?php echo $info['status']?></h3>
<h3>Account Activated: <?php echo date('D d M Y', $info['acc_activated'])?></h3>
    <br><br>
<div>
    <button style="width: 33%"><a href="update.php?id=<?php echo $_GET['id']?>">Edit</a></button> <button style="width: 33%">Delete Account</button> <button style="width: 33%">Disable Account</button>
</div>
<?php
include "../includes/footer.php";
?>