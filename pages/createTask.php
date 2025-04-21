<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
error_reporting(E_ALL);


include "../php/main.php";


$errors = [];
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)) {

    $_SESSION['task_data'] = $_POST;
    $task_data = $_SESSION['task_data'] ?? [];

    if (empty($_POST['TaskName'])) {
        $errors['TaskName'] = 'Please Input Task Name'; // Task Name is empty
    }
    if (empty($_POST['ProjectID'])) {
        $errors['ProjectID'] = 'Please Select a Project'; // Project ID is empty
    }

    if (empty($_POST['Description'])) {
        $errors['Description'] = 'Please Input Task Description'; // Task Description is empty
    }
    if (empty($_POST['StartDate'])) {
        $errors['StartDate'] = 'Please Input Start Date'; // Start Date is empty
    } else if (!empty($_POST['ProjectID'])) {
        $project = getProject($_POST['ProjectID']);
        if ($_POST['StartDate'] < $project['start_date']) {
            $errors['StartDate'] = 'Start Date must be after Project Start Date'; // Start Date is before Project Start Date
        }
    }

    if (empty($_POST['EndDate'])) {
        $errors['EndDate'] = 'Please Input End Date'; // End Date is empty
    } else if (!empty($_POST['ProjectID'])) {
        $project = getProject($_POST['ProjectID']);
        if ($_POST['EndDate'] > $project['end_date']) {
            $errors['EndDate'] = 'End Date must be before Project End Date'; // Start Date is before Project Start Date
        }
    }
    if (empty($_POST['Effort']) || !is_numeric($_POST['Effort'])) {
        $errors['Effort'] = 'Please Input valid Effort'; // Effort is empty or not a number
    }
    if (empty($_POST['Status'])) {
        $errors['Status'] = 'Please Input Status'; // End Date is empty
    }
    if (empty($_POST['Priority'])) {
        $errors['Priority'] = 'Please Input Priority'; // End Date is empty
    }

    if ($_POST['StartDate'] > $_POST['EndDate']) {
        $errors['EndDate'] = 'End Date must be after Start Date'; // End Date is before Start Date
    }
    if (empty($_POST['Effort']) || !is_numeric($_POST['Effort'])) {
        $errors['Effort'] = 'Please Input valid Effort'; // Effort is empty or not a number
    }

    if (empty($errors)) {

        $_SESSION['task_data'] = [
            'name' => $_POST['TaskName'],
            'project_id' => $_POST['ProjectID'],
            'description' => $_POST['Description'],
            'start_date' => $_POST['StartDate'],
            'end_date' => $_POST['EndDate'],
            'effort' => $_POST['Effort'],
            'status' => $_POST['Status'],
            'priority' => $_POST['Priority'],
        ];
        if (createTask()) {
            $message = 'Task Created Successfully';
        } else {
            $message = 'Task Creation Failed';
        }
    } else {
        $_SESSION['task_data'] = $_POST;
    }
} else {
    $_SESSION['task_data'] = []; // Clear form data if not a POST request
}


$task_data = $_SESSION['task_data'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TAP - Create Task</title>
    <link rel="stylesheet" href="../styles.css">
</head>

<body>
<?php include "../php/header.php" ?>




    <h2>Task Information</h2>
    <main class="main">
        <?php if (!empty($message)): ?>
                <section class="content">
                    <Label name="message" class="message_label">
                        <?php
                        echo $message;
                        ?>
                    </Label>
                    <a href="home.php">Home</a>
                </section>
        <?php else: ?>
            <fieldset  class = "form-container">
                <form action="createTask.php" method="post">
                    <section>
                        <label for="TaskName">Task Name: </label>
                        <br>
                        <input type="text" name="TaskName" placeholder="Task Name" id="TaskName" class="text__input <?php echo isset($errors['TaskName']) ? 'input_error' : '' ?>" value="<?php echo $task_data['TaskName'] ?? ''; ?>">
                    </section>
                    <section>
                        <label for="Description ">Task Description : </label>
                        <br>
                        <textarea name="Description" placeholder="Task Description" id="Description" class="text__input <?php echo isset($errors['Description']) ? 'input_error' : '' ?>"><?php echo $task_data['Description'] ?? ''; ?></textarea>
                    </section>
                    <section>
                        <label for="ProjectName">Project Name: </label>
                        <br>
                        <select name="ProjectID" id="ProjectID" required>
                            <option value="">Select Project</option>
                            <?php
                            $projects = getProjectsManagedBy($_SESSION['user']['user_id'] ?? $_SESSION['user']['UserID']); // get projects managed by user
                            echo "<pre>";
                            print_r($projects);
                            echo "</pre>";
                            foreach ($projects as $project) {
                                echo "<option value='" . $project['project_id'] . "'>" . $project['title'] . "</option>";
                            }
                            ?>
                        </select>
                    </section>
                    <section>
                        <label for="StartDate">Start Date: </label>
                        <br>
                        <input type="date" name="StartDate" placeholder="Start Date" id="StartDate" class="date__input <?php echo isset($errors['StartDate']) ? 'input_error' : '' ?>" value="<?php echo $task_data['StartDate'] ?? ''; ?>">
                    </section>
                    <section>
                        <label for="EndDate">End Date: </label>
                        <br>
                        <input type="date" name="EndDate" placeholder="End Date" id="EndDate" class="date__input <?php echo isset($errors['EndDate']) ? 'input_error' : '' ?>" value="<?php echo $task_data['EndDate'] ?? ''; ?>">
                    </section>
                    <section>
                        <label for="Effort">Effort: </label>
                        <br>
                        <input type="number" name="Effort" placeholder="Effort" id="Effort" min="0" class="text__input <?php echo isset($errors['Effort']) ? 'input_error' : '' ?>" value="<?php echo $task_data['Effort'] ?? ''; ?>">
                    </section>
                    <section>
                        <label for="Status">Status: </label>
                        <br>
                        <select name="Status" id="Status">
                            <option value="Pending" <?php echo (isset($form_data['Status']) && $form_data['Status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                            <option value="In Progress" <?php echo (isset($form_data['Status']) && $form_data['Status'] == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                            <option value="Completed" <?php echo (isset($form_data['Status']) && $form_data['Status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                        </select>
                    </section>
                    <section>
                        <label for="Priority">Priority: </label>
                        <br>
                        <select name="Priority" id="Priority">
                            <option>Select Priority</option>
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                        </select>
                    </section>
                    <?php if (!empty($errors)) { ?>
                            <section>
                                <Label name="error" class="error_label">
                                    <?php
                                    echo reset($errors);
                                    ?>
                                </Label>
                            </section>
                    <?php } ?>
                    <section>
                        <button type="submit" name="add">Add Project</button>
                    </section>
                </form>
            </fieldset>
        <?php endif; ?>
    </main>
</body>

</html>