<?php
include "../includes/header.php";
$output = null;
if(array_key_exists("gitpull", $_POST)) {
    $output = git_pull();
//    echo "<script>alert($output)</script>";
}
?>
<form method="post">
        <input type="submit" name="gitpull" class="button" value="Update Environment" />
</form>
<?php if(isset($output)){
    echo "<code>".print_r($output)."</code>";
}?>
<?php
include "../includes/footer.php";
?>