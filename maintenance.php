<?php
include "includes/header.php";

$value = getMaintVal();
if($value === 0){
    header("Location: index.php");
}
?>

    <h1 style="text-align: center"><i class="fas fa-tools" style="font-size: 60px; "></i><br>THIS SITE IS CURRENTLY UNDER MAINTENANCE!<br><i class="fas fa-tools" style="font-size: 60px; text-align: center"></i></h1>
    <br><br>
    <h3>Sorry for any inconvenience but this site is currently under maintenance and unavailable to the public. Please check back soon or email <a href="mailto:<?php echo USERNAME?>"><?php echo USERNAME?></a> if you have any questions or require assistance</h3>

<?php
include "includes/footer.php";
?>