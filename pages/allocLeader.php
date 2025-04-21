<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../php/main.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles.css">
</head>

<body>
<?php include "../php/header.php" ?>

<main class="main">
<?php include "../php/navbar.php" ?>

<section class="content">
<?php 
   $projects = getNoLeaderProjects();
?>
<table class  = "table">
    <tr>
    <th>Project ID </th>
    <th>Project Title</th>
    <th>Start Date</th>
    <th>End Date</th>
    <th>Action</th>
    </tr>
    <?php
    foreach ($projects as $project) {
        echo "<tr>";
        echo "<td>" . $project['project_id'] . "</td>";
        echo "<td>" . $project['title'] . "</td>";
        echo "<td>" . $project['start_date'] . "</td>";
        echo "<td>" . $project['end_date'] . "</td>";
        echo "<td><a href='allocLeader2.php?".$project['project_id']."'>Allocate Team Leader</a></td>";
        echo "</tr>";
    }
    ?>
</table>
</section>
</main>
</section>


<?php
include "../php/footer.php";
?>

</body>
</html>
