<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "../php/main.php";



$errors = [];
$message = "";



if ($_SERVER['REQUEST_METHOD'] == 'POST' & !empty($_POST)) {

    $_SESSION['project_data'] = $_POST;
    $project_data = $_SESSION['project_data'] ?? [];

    if (empty($_POST['ProjectID'])) {
        $errors['ProjectID'] = 'Please Input Project ID'; // Project ID is empty
    }
    if (empty($_POST['ProjectTitle'])) {
        $errors['ProjectTitle'] = 'Please Input Project Title'; // Project Title is empty
    }
    if (empty($_POST['ProjectDescription'])) {
        $errors['ProjectDescription'] = 'Please Input Project Description'; // Project Description is empty
    }
    if (empty($_POST['CustomerName'])) {
        $errors['CustomerName'] = 'Please Input Customer Name'; // Customer Name is empty
    }
    if (empty($_POST['Budget']) || !is_numeric($_POST['Budget'])) {
        $errors['Budget'] = 'Please Input valid Budget'; // Budget is empty or not a number
    }
    if (empty($_POST['StartDate'])) {
        $errors['StartDate'] = 'Please Input Start Date'; // Start Date is empty
    }
    if (empty($_POST['EndDate'])) {
        $errors['EndDate'] = 'Please Input End Date'; // End Date is empty
    }
    if (!validateID($_POST['ProjectID'])) {
        $errors['ProjectID'] = 'Please Input a valid Project ID'; // Project ID is invalid
    }
    if ($_POST['StartDate'] > $_POST['EndDate']) {
        $errors['EndData'] = 'End Date must be after Start Date'; // End Date is before Start Date
    }

    if (isset($_FILES['ProjectFiles']) && !empty($_FILES['ProjectFiles']['name'][0])) {
        for ($i = 0; $i < count($_FILES['ProjectFiles']['name']); $i++) {
            if ($_FILES['ProjectFiles']['error'][$i] !== UPLOAD_ERR_OK) { // Check for errors
                $errors["ProjectFiles"][] = "Error uploading file: " . $_FILES['ProjectFiles']['name'][$i];
            } else {
                if ($_FILES['ProjectFiles']['size'][$i] > 2000000) {
                    $errors["ProjectFiles"][] = "File size exceeds 2MB: " . $_FILES['ProjectFiles']['name'][$i]; // File size is greater than 2MB
                } else { // if no errors
                    $file = [
                        'project_id' => $_POST['ProjectID'],
                        'name' => $_FILES['ProjectFiles']['name'][$i],
                        'tmp_name' => $_FILES['ProjectFiles']['tmp_name'][$i]
                    ];
                    $_SESSION['ProjectFiles'][] = $file;
                }
            }
        }
    }
    if (empty($errors)) {
        $_SESSION['project_data'] = [
            'ProjectID' => $_POST['ProjectID'],
            'ProjectTitle' => $_POST['ProjectTitle'],
            'ProjectDescription' => $_POST['ProjectDescription'],
            'CustomerName' => $_POST['CustomerName'],
            'Budget' => $_POST['Budget'],
            'StartDate' => $_POST['StartDate'],
            'EndDate' => $_POST['EndDate'],
        ];
        if (createProject()) { // if project is created
            if (isset($_SESSION['ProjectFiles']) && !empty($_SESSION['ProjectFiles'])) { // if files are uploaded
                $uploadPath = '../uploads/';
                foreach ($_SESSION['ProjectFiles'] as $file) {
                    $destination = $uploadPath . basename($file['name']);
                    if (move_uploaded_file($file['tmp_name'], $destination)) { //move file
                        insertFile($file);
                    } else {
                        $errors["ProjectFiles"][] = "Failed to move file: " . $file['name'];
                    }
                }
            }
            if (empty($errors["ProjectFiles"])) {
                $message = 'Project Created Successfully';
            } else {
                $message = 'Project Creation Failed';
            }
        } else {
            $message = 'Project Creation Failed';
        }
    }
} else {
    $_SESSION['project_data'] = []; // Clear form data if not a POST request
}

function validateID($proj_id)
{
    return preg_match('/^[A-Z]{4}-\d{5}$/', $proj_id);
}



$project_data = $_SESSION['project_data'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TAP - insert project</title>
    <link rel="stylesheet" href="../styles.css">
</head>

<body>
    <header>
        <?php
        include "../php/home_link.php";
        ?>
    </header>
    <h2>Project Information</h2>
   
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
            <fieldset class = "form-container">
                <form action="addProject.php" method="post" enctype="multipart/form-data">
                    <section>
                        <label for="ProjectID">Project ID: </label>
                        <br>
                        <input type="text" name="ProjectID" placeholder="ABCD-12345" id="ProjectID"
                            class="text__input <?php echo isset($errors['ProjectID']) ? 'input_error' : '' ?>"
                            value="<?php echo $project_data['ProjectID'] ?? ''; ?>">
                    </section>
                    <section>
                        <label for="ProjectTitle">Project Title: </label>
                        <br>
                        <input type="text" name="ProjectTitle" placeholder="Project Title" id="ProjectTitle"
                            class="text__input <?php echo isset($errors['ProjectTitle']) ? 'input_error' : '' ?>"
                            value="<?php echo $project_data['ProjectTitle'] ?? ''; ?>">
                    </section>
                    <section>
                        <label for="ProjectDescription ">Project Description : </label>
                        <br>
                        <textarea name="ProjectDescription" placeholder="Project Description" id="Project Description"
                            class="text__input <?php echo isset($errors['ProjectDescription']) ? 'input_error' : '' ?>"><?php echo $project_data['ProjectDescription'] ?? ''; ?></textarea>
                    </section>
                    <section>
                        <label for="CustomerName">Customer Name : </label>
                        <br>
                        <input type="text" name="CustomerName" placeholder="Customer Name" id="Custom erName "
                            class="text__input <?php echo isset($errors['CustomerName']) ? 'input_error' : '' ?>"
                            value="<?php echo $project_data['CustomerName'] ?? ''; ?>">
                    </section>
                    <section>
                        <label for="Budget">Budget: </label>
                        <br>
                        <input type="number" name="Budget" placeholder="Budget" id="Budget" min="0"
                            class="text__input <?php echo isset($errors['Budget']) ? 'input_error' : '' ?>"
                            value="<?php echo $project_data['Budget'] ?? ''; ?>">
                    </section>
                    <section>
                        <label for="StartDate">Start Date: </label>
                        <br>
                        <input type="date" name="StartDate" placeholder="Start Date" id="StartDate"
                            class="date__input <?php echo isset($errors['StartDate']) ? 'input_error' : '' ?>"
                            value="<?php echo $project_data['StartDate'] ?? ''; ?>">
                    </section>
                    <section>
                        <label for="EndDate">End Date: </label>
                        <br>
                        <input type="date" name="EndDate" placeholder="End Date" id="EndDate"
                            class="date__input <?php echo isset($errors['EndDate']) ? 'input_error' : '' ?>"
                            value="<?php echo $project_data['EndDate'] ?? ''; ?>">
                    </section>
                    <fieldset class = "form-container">
                        <legend>Project Details Files (up to 3):</legend>
                        <input type="file" name="ProjectFiles[]" multiple accept=".pdf,.png,.docx,.jpg">
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
                    <section>
                        <button type="submit" name="add">Add Project</button>
                    </section>
                </form>
            </fieldset>
        <?php endif; ?>
        </section>
        
    </main>
</body>

</html>