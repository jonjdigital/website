<ul class="links">
        <?php
        if(isset($_SESSION['user_id'])){
            echo "<li>";
            echo "<a href='/user/view.php?id=".$_SESSION['user_id']."'>";
            echo "<h3>My Profile</h3>";
            echo "<p>View and Edit your profile here</p>";
            echo "</a>";
            echo "</li>";
        }
        ?>

       <?php
       if(in_array("Content Creator",$_SESSION['roles'])){
           echo "<li>";
           echo "<a href='/post/dashboard.php'>";
           echo "<h3>Post Dashboard</h3>";
           echo "<p>Manage your posts and content through here</p>";
           echo "</a>";
           echo "</li>";
       }
       ?>
        <!--<li>
            <a href="#">
                <h3>Dolor sit amet</h3>
                <p>Sed vitae justo condimentum</p>
            </a>
        </li>
        <li>
            <a href="#">
                <h3>Feugiat veroeros</h3>
                <p>Phasellus sed ultricies mi congue</p>
            </a>
        </li>
        <li>
            <a href="#">
                <h3>Etiam sed consequat</h3>
                <p>Porta lectus amet ultricies</p>
            </a>
        </li>-->
</ul>