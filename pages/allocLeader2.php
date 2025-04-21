<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "../php/main.php";

$message = "";
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'GET' && !empty($_GET)) {
    $_SESSION['project'] = getProject(array_keys($_GET)[0]);
} else if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)) {
    if (empty($_POST['LeaderID'])) {
        $errors['LeaderID']= 'Please Select a Leader';
    } 
    if (empty($errors)) {
        if (allocateProjectLeader($_POST['LeaderID'], $_SESSION['project']['project_id'])) {
            $message = "Leader Allocated Successfully";
        } else {
            $message = "Leader Allocation Failed";
        }
    }
}
$project = $_SESSION['project'];

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
            <fieldset  class = "form-container">
                <legend class="legend">Project Details</legend>
                <form>
                    <section>
                        <label for="ProjectID">Project ID: </label>
                        <br>
                        <input type="text" name="ProjectID" placeholder="ABCD-12345" id="ProjectID" class="text__input"
                            disabled value="<?php echo $project['project_id']; ?>">
                    </section>
                    <section>
                        <label for="ProjectTitle">Project Title: </label>
                        <br>
                        <input type="text" name="ProjectTitle" placeholder="Project Title" id="ProjectTitle"
                            class="text__input" disabled value="<?php echo $project['title']; ?>">
                    </section>
                    <section>
                        <label for="ProjectDescription">Project Description: </label>
                        <br>
                        <textarea name="ProjectDescription" placeholder="Project Description" id="ProjectDescription"
                            class="text__input" disabled><?php echo $project['description']; ?></textarea>
                    </section>
                    <section>
                        <label for="CustomerName">Customer Name: </label>
                        <br>
                        <input type="text" name="CustomerName" placeholder="Customer Name" id="CustomerName"
                            class="text__input" disabled value="<?php echo $project['customer_name']; ?>">
                    </section>
                    <section>
                        <label for="Budget">Budget: </label>
                        <br>
                        <input type="number" name="Budget" placeholder="Budget" id="Budget" min="0" class="text__input"
                            disabled value="<?php echo $project['budget']; ?>">
                    </section>
                    <section>
                        <label for="StartDate">Start Date: </label>
                        <br>
                        <input type="date" name="StartDate" placeholder="Start Date" id="StartDate" class="text__input"
                            disabled value="<?php echo $project['start_date']; ?>">
                    </section>
                    <section>
                        <label for="EndDate">End Date: </label>
                        <br>
                        <input type="date" name="EndDate" placeholder="End Date" id="EndDate" class="text__input" disabled
                            value="<?php echo $project['end_date']; ?>">
                    </section>
                </form>
            </fieldset>
            <fieldset  class = "form-container">
                <legend class="legend">Allocate Team Leader</legend>
                <form action="allocLeader2.php" method="post">
                    <section>
                        <select name="LeaderID" id="LeaderID" class="text__input <?php echo isset($errors['LeaderID']) ? 'input_error' : '' ?>">
                            <option value="">Select Leader</option>
                            <?php
                            $leaders = getLeaders();
                            print_r($project);
                            print_r($leaders);
                            foreach ($leaders as $user) {
                                echo "<option value='" . $user['user_id'] . "'>" . $user['name'] . " - " . $user['user_id'] . "</option>";
                            }
                            ?>
                        </select>
                    </section>
                    <?php if (!empty($errors)) { ?>
                <section>
                    <label name="error" class="error_label">
                        <?php
                        echo reset($errors);
                        ?>
                    </Label>
                </section>
        <?php } ?>
                    <section>
                        <button type="submit" name="allocate">Confirm Allocation</button>
                    </section>
                </form>
            </fieldset>
        <?php endif; ?>
        </section>

    </main>
    </section>


    <?php
    // include "../php/footer.php";
    ?>

</body>

</html>