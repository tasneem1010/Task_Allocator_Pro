<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "../php/main.php";



// filters, user clicks search and results pop up based on selected filters

if($_SERVER['REQUEST_METHOD'] === 'GET')
{
    $tasks = filterTasks(
        isset($_GET['project_id']) ? $_GET['project_id'] : null,
        isset($_GET['priority']) ? $_GET['priority'] : null,
        isset($_GET['status']) ? $_GET['status'] : null,
        isset($_GET['start_date']) ? $_GET['start_date'] : null,
        isset($_GET['end_date']) ? $_GET['end_date'] : null
    );
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Search</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles.css">
</head>

<body>
    <?php include "../php/header.php" ?>

    <main class="main">
        <?php include "../php/navbar.php" ?>

         <section class="content">
            <h1>Search Tasks</h1>

            <form method="get">
                <fieldset class="form-container">
                    <label for="priority">Priority: </label>
                    <select class="text__input" name="priority" id="priority">
                        <option value="">All Priorities</option>
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                    </select>

                    <label for="status">Status: </label>
                    <select class="text__input" name="status" id="status">
                        <option value="">All Statuses</option>
                        <option value="Pending">Pending</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                    </select>


                    <label for="start_date">Start Date: </label>
                    <input type="date" name="start_date" id="start_date" class="text__input">


                    <label for="end_date">End Date: </label>
                    <input type="date" name="end_date" id="end_date" class="text__input">

                    <?php
                    // reminder: get projects based on role
                    $projects = getProjects();
                    ?>

                    <label for="project">Select a project:</label>
                    <select name="project" id="project" class="text__input">
                        <?php
                        foreach ($projects as $project) {
                            echo '<option value="' . $project['project_id'] . '">' . $project['title'] . '</option>';
                        }
                        ?>
                    </select>


                    <button type="submit">Search</button>
                    <br>
                    <a href="search.php">Clear Filters</a>


                </fieldset>
            </form>


            <?php if(!empty($tasks)): ?>

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
                    <?php foreach ($tasks as $task): ?>
                        <tr>
                            <td><a href="taskDetails.php?task_id=<?php echo urlencode($task['id']); ?>"><?php echo ($task['id']); ?></td>
                            <td><?php echo ($task['name']); ?></td>
                            <td><?php echo ($task['project_id']); ?></td>
                            <td><?php echo ($task['status']); ?></td>
                            <td><?php echo ($task['priority']); ?></td>
                            <td><?php echo ($task['start_date']); ?></td>
                            <td><?php echo ($task['end_date']); ?></td>
                            <td><?php echo ($task['completion_percentage']); ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                </table>
               
            <?php endif; ?>
        </section>
    </main>

    <?php
    include "../php/footer.php";
    ?>

</body>

</html>