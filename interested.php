<?php
include "includes/header.php"
?>
    <div id="icon" style="font-size: 30px; text-align: center">
        <span class="fa fa-envelope"></span>
        <h4>Register Interest</h4>
        <p>Thank you for showing interest in my site. This site is currently in development, but if you wish to be notified by name when it becomes live, please enter your name and email below. Thank you for visiting.</p>
    </div>

    <div id="form">
        <form action="" method="post">
            <div id="form-email">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>
            <br>
            <div id="form-name">
                <label for="name">Email Address</label>
                <input type="text" id="name" name="name" required>
            </div>
            <br>
            <input type="submit" value="Register Interest" name="submit" id="submit_button" style="width: 100%">
        </form>
    </div>
<?php
include "includes/footer.php"
?>