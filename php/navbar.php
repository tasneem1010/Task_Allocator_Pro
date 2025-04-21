<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar">
    <ul>
        <li><a href="home.php" class=navLink>Home</a></li>

        <?php
        if (isset($_SESSION['user'])) {
        $user = $_SESSION['user'];
        
            // echo '<pre>';
            // print_r($user);
            // echo '<pre>';
            echo '<li><a href="logout.php"" class = navLink>Logout</a></li>';
            echo '<li><a href="search.php" class = navLink>Search</a></li>';
         
            if ($user['role'] == 'Manager') {
                echo '<li><a href="addProject.php" class = navLink>Add Project</a></li>';
                echo '<li><a href="allocLeader.php" class = navLink>Allocate Team Leader</a></li>';
            } else if ($user['role'] == 'Project Leader') {
                echo '<li><a href="createTask.php" class = navLink>Create Task</a></li>';
                echo '<li><a href="assignMembers.php" class = navLink>Assign Team Members</a></li>';
            } else if ($user['role'] == 'Team Member') {
                echo '<li><a href="acceptTask.php" class = navLink>Accept Task Assignments</a></li>';
                echo '<li><a href="search.php" class = navLink>Search and Update Task Progress</a></li>';
            }
        } else {
            echo '<li><a href="login.php" class = navLink>Login</a></li>';
            echo '<li><a href="register.php" class = navLink>Sign Up</a></li>';
        }
        ?>
        <li><a href="about.php" class=navLink>About</a></li>
    </ul>
</nav>