<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<header>
    <?php
    include "home_link.php";
    ?>
    <nav class="header-nav">
        <ul>
            <?php
            if (
                basename($_SERVER['PHP_SELF']) == "home.php"
                || basename($_SERVER['PHP_SELF']) == "login.php"
                || basename($_SERVER['PHP_SELF']) == "register.php"
            ) {
                if (isset($_SESSION['user'])) {
                    echo '<li><a href="profile.php" class = navLink>User Profile</a></li>';
                    echo '<li><a href="logout.php" class = navLink>Logout</a></li>';
                } else {
                    echo '<li><a href="login.php" class = navLink>Login</a></li>';
                    echo '<li><a href="register.php" class = navLink>Sign Up</a></li>';
                }
            }
            ?>
        </ul>
    </nav>
</header>