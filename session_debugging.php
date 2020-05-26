<?php
include "includes/header.php";
if(isset($_COOKIE)){
    echo "<h3>Cookie Info:</h3>";
    foreach($_COOKIE as $key => $value){
        echo $key . ": " . $value . "<br>";
    };
}else{
    echo "No Cookie Info set";
}
echo "<hr>";
if(isset($_SESSION)){
    echo "<h3>SESSION Info:</h3>";
    if(isset($_SESSION['roles'])){
        echo "<p>User Roles:</p>";
        foreach($_SESSION['roles'] as $role){
            echo "> ".$role."<br>";
        }
    }
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
