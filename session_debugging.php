<?php
include "includes/header.php";
if(isset($_SESSION)){
    print_r($_SESSION);
}else{
    echo "No Session Info set";
}

?>

<hr>
<h3>Server Information</h3>
<?php foreach($_SERVER as $key => $server){
    echo $key . " - " .$server . "<br>";
}

include "includes/footer.php";?>
