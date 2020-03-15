<?php
include "../includes/header.php";
$info = getAllUserInfo($_GET['id']);
var_dump($info);
?>

<h1><?php echo $info['username']?></h1>

<?php
include "../includes/footer.php";
?>