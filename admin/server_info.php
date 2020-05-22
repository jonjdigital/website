<?php
include "../includes/header.php";

$php_server_user = exec("whoami");
?>
    <h3>Server Information</h3>
<?php
echo "Current User: ". $php_server_user . "<br>";

foreach($_SERVER as $key => $server){
    echo $key . " - " .$server . "<br>";
}

include "../includes/footer.php";