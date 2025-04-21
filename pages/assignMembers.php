<?php

// ini_set('display_errors', 1);
// error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once '../php/main.php';
$tasks = null;
$message = "";
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['project'])) {
        $projectId = $_POST['project'];
        $_SESSION['projectId'] = $projectId;
        $tasks = getTasks($projectId);
    } else {
        if (empty($_POST['user_id'])) {
            $errors['user_id'] = 'Please Select Team Member';
        }
        if (empty($_POST['role'])) {
            $errors['role'] = 'Please Select Role';
        }
        if (empty($_POST['contribution'])) {
            $errors['contribution'] = ('Please Input Contribution Percentage');
        }else if((getTaskProgress($_POST['task_id'])+$_POST['contribution']) > 100) {
            $errors[] = 'Contribution Exceeds 100 percent';
        }
        if (empty($_POST['start_date'])) {
            $errors['start_date'] = 'Please Select a Date';
        }else if($_POST['start_date'] < getTaskById($_POST['task_id'])['start_date']){
            $errors['start_date'] = "The team member's start date cannot precede the task's start date";
        }

        if (empty($errors)) {
            if (isMemberAssignedTaskAtDate($_POST['user_id'], $_POST['start_date'], $_POST['task_id']) == null) {
                if (empty($errors)) {
                    $_SESSION['add_team_member'] = [
                        'user_id' => $_POST['user_id'],
                        'task_id' => $_POST['task_id'],
                        'role' => $_POST['role'],
                        'contribution' => $_POST['contribution'],
                        'start_date' => $_POST['start_date']
                    ];
                    $memberName = getUserByID($_POST['user_id'])['name'];
                    $taskName = getTaskByID($_POST['task_id'])['name'];
                    addTeamMemberToTask();
                    $message = "Member '" . $memberName . "' has been assigned task '" . $taskName . "' successfully";
                }
            } else {
                $message = "Member is Already assigned the task at that date";
                $errors[] = $message;
            }
            if (!empty($errors)) {
                if (isset($_POST['task_id'])&& isset($projectId)) {
                    $tasks = getTasks($projectId);
                    $_GET['task_id'] = $_POST['task_id'];

                }
            }
        }else{
            if (isset($_POST['task_id'])) {
                $tasks = getTasks($_SESSION['projectId']);
                $_GET['task_id'] = $_POST['task_id'];

            }
        }
    }
}


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
        <?php if (!empty($message)): ?>
            <section>
                <Label name="message" class="message_label">
                    <?php
                    echo $message;
                    ?>
                </Label>
                <a href="home.php">Home</a>
            </section>
        <?php else: ?>


            <?php if (isset($_GET['task_id'])):
                $taskId = $_GET['task_id'];
                $task = getTaskById($taskId);
                $teamMembers = getMembers();
                $roles = ['Developer', 'Designer', 'Tester', 'Analyst', 'Support'];
                ?>
                <form action="assignMembers.php" method="post">
                    <label for="task_id">Task ID:</label>
                    <input type="text" id="task_id" name="task_id" value="<?php echo $task['id']; ?>" class="text__input <?php echo isset($errors['task_id']) ? 'input_error' : '' ?>"
                        readonly><br>

                    <label for="task_name">Task Name:</label>
                    <input type="text" id="task_name" name="task_name" value="<?php echo $task['name']; ?>" class="text__input <?php echo isset($errors['task_name']) ? 'input_error' : '' ?>"
                        readonly><br>

                    <label for="start_date">Start Date:</label>
                    <input type="date" id="start_date" name="start_date" class="date__input <?php echo isset($errors['start_date']) ? 'input_error' : '' ?>"
                        value="<?php echo date('Y-m-d'); ?>"><br>

                        <fieldset  class = "form-container">
                        <legend>Member Data</legend>
                    <label for="user_id">Team Member:</label>
                    <select id="user_id" name="user_id" class = " text__input <?php echo isset($errors['user_id']) ? 'input_error' : '' ?>">
                        <option value="">Select Team Member</option>
                        <?php foreach ($teamMembers as $member): ?>
                            <option value="<?php echo $member['user_id']; ?>"><?php echo $member['name']; ?></option>
                        <?php endforeach; ?>
                    </select><br>

                    <label for="role">Role:</label>
                    <select id="role" name="role" class = "text__input <?php echo isset($errors['role']) ? 'input_error' : '' ?>">
                        <option value="">Select Role</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?php echo $role; ?>"><?php echo $role; ?></option>
                        <?php endforeach; ?>
                    </select><br>

                    <label for="contribution">Contribution Percentage:</label>
                    <input type="number" id="contribution" name="contribution" class="text__input <?php echo isset($errors['contribution']) ? 'input_error' : '' ?>" min="0" max="100"><br>
                    </fieldset>

                    <?php if (!empty($errors)) { ?>
                        <section>
                            <Label name="error" class="error_label">
                                <?php
                                echo reset($errors);
                                ?>
                            </Label>
                        </section>
                    <?php } ?>

                    <button type="submit">Assign Team Member</button>
                </form>
            <?php elseif (!isset($tasks)): ?>
                <?php
                $projects = getProjectsManagedBy($_SESSION['user']['user_id']);
                ?>

                <form action="assignMembers.php" method="post">
                    <label for="project">Select a project:</label>
                    <select name="project" id="project">
                        <?php
                        foreach ($projects as $project) {
                            echo '<option value="' . $project['project_id'] . '">' . $project['title'] . '</option>';
                        }
                        ?>
                    </select>
                    <button type="submit">Select</button>
                </form>

            <?php else: ?>
                <table>
                    <tr>
                        <th>Task ID</th>
                        <th>Task Name</th>
                        <th>Start Date</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Team Allocation</th>
                    </tr>
                    <?php
                    foreach ($tasks as $task) {
                        echo '<tr>';
                        echo '<td>' . $task['id'] . '</td>';
                        echo '<td>' . $task['name'] . '</td>';
                        echo '<td>' . $task['start_date'] . '</td>';
                        echo '<td>' . $task['status'] . '</td>';
                        echo '<td>' . $task['priority'] . '</td>';
                        echo '<td><a href="assignMembers.php?task_id=' . $task['id'] . '">Assign Team Members</a></td>';
                        echo '</tr>';
                    }
                    ?>
                </table>
            <?php endif; ?>

        <?php endif; ?>
        </section>

    </main>

    </section>


    <?php
    include "../php/footer.php";
    ?>

</body>

</html>