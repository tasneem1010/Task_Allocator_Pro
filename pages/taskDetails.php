<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "../php/main.php";

$task = getTaskById($_GET['task_id']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Task Details</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles.css">
</head>

<body>
    <?php include "../php/header.php" ?>

    <main class="main">
        <?php include "../php/navbar.php" ?>

         <section class="content">
            <h1>Task Details</h1>
            <?php if(!empty($task)): ?>

                <table>
                    <thead>
                    <tr>
                        <th>Task ID</th>
                        <th>Title</th>
                        <th>Project</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Start Date</th>
                        <th>Due Date</th>
                        <th>Completion %</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo ($task['id']); ?></td>
                            <td><?php echo ($task['name']); ?></td>
                            <td><?php echo ($task['project_id']); ?></td>
                            <td><?php echo ($task['status']); ?></td>
                            <td><?php echo ($task['priority']); ?></td>
                            <td><?php echo ($task['start_date']); ?></td>
                            <td><?php echo ($task['end_date']); ?></td>
                            <td><?php echo ($task['completion_percentage']); ?>%</td>
                        </tr>
                </tbody>
                </table>
               
                <a href="search.php">Go back to Search</a>
            <?php endif; ?>
        </section>
    </main>

    <?php
    include "../php/footer.php";
    ?>

</body>

</html>