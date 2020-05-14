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
?>

<?php
include "../includes/footer.php";
?>