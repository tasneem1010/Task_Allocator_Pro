<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "../php/main.php";



$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
    $_SESSION['form_data'] = array_merge($_SESSION['form_data'] ?? [], $_POST); // Merge posted form data into the session data to get the data back if user wants to go back to this page

}
$form_data = $_SESSION['form_data'] ?? [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' & !empty($_POST)) {

    if (empty($_POST['Name'])) {
        $errors['Name'] = 'Please Input Name'; // Name is empty
    }
    if (empty($_POST['House'])) {
        $errors['House'] = 'Please Input House'; // House is empty
    }
    if (empty($_POST['Street'])) {
        $errors['Street'] = 'Please Input Street'; // Street is empty
    }
    if (empty($_POST['City'])) {
        $errors['City'] = 'Please Input City'; // City is empty
    }
    if (empty($_POST['Country'])) {
        $errors['Country'] = 'Please Input Country'; // Country is empty
    }
    if (empty($_POST['DateOfBirth'])) {
        $errors['DateOfBirth'] = 'Please Input Date of Birth'; // Date of Birth is empty
    }
    if (empty($_POST['IDNumber']) || !is_numeric($_POST["IDNumber"]) || strlen($_POST["IDNumber"]) != 10) {
        $errors['IDNumber'] = "Please Input valid 10 Digit ID Number"; // ID is empty or not a number or not 10 digits
    }
    if (empty($_POST['Email']) || !filter_var($_POST['Email'], FILTER_VALIDATE_EMAIL)) {
        $errors['Email'] = "Email is invalid"; // Email is invalid
    }
    if (emailExists($_POST['Email'])) {
        $errors['Email'] = 'Email already exists'; // Email already exists
    }
    if (empty($_POST["Telephone"]) || !is_numeric($_POST["Telephone"]) || strlen($_POST["Telephone"]) < 10 || strlen($_POST["Telephone"]) > 15) {
        $errors['Telephone'] = "Please Input valid Telephone Number"; // Telephone is empty or not a number or not between 10 and 15 digits
    }
    if (empty($_POST["Role"])) {
        $errors['Role'] = "Please Input Role"; // Role is empty
    }
    if (empty($_POST["Qualification"])) {
        $errors['Qualification'] = "Please Input Qualification"; // Qualifications is empty
    }

    if (empty($_POST["Skills"])) {
        $errors['Skills'] = "Please Input Skills"; // Skills is empty
    }
    if (empty($errors)) {
        $_SESSION['user_signUp'] = [
            'Name' => $_POST['Name'],
            'House' => $_POST['House'],
            'Street' => $_POST['Street'],
            'City' => $_POST['City'],
            'Country' => $_POST['Country'],
            'DateOfBirth' => $_POST['DateOfBirth'],
            'IDNumber' => $_POST['IDNumber'],
            'Email' => $_POST['Email'],
            'Telephone' => $_POST['Telephone'],
            'Role' => $_POST['Role'],
            'Qualification' => $_POST['Qualification'],
            'Skills' => $_POST['Skills']
        ];
        $submission_type = $_POST['submission_type'] ?? 'final';
        if ($submission_type != 'edit') {
            header('Location: register2.php');
            exit();
        }
    } else {
        $_SESSION['form_data'] = $_POST;
    }
} else {
    $_SESSION['form_data'] = []; // Clear form data if not a POST request
}

$form_data = $_SESSION['form_data'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TAP - Register</title>
    <link rel="stylesheet" href="../styles.css">
</head>

<body> 
    <?php include "../php/header.php" ?>

    
<main class="main">

<section class="content">
    <h2>User Information</h2>
        <fieldset class="form-container">
            <form action="register.php" method="post">
                <section>
                    <label for="Name">Full Name: </label>
                    <br>
                    <input type="text" name="Name" placeholder="Full Name" id="Name"
                        class="text__input <?php echo isset($errors['Name']) ? 'input_error' : '' ?>"
                        value="<?php echo $form_data['Name'] ?? ''; ?>">
                </section>
                <fieldset class="form-container">
                    <legend class="legend">Address: </legend>
                    <section>
                        <label for="House">House: </label>
                        <br>
                        <input type="text" name="House" placeholder="House" id="House"
                            class="text__input <?php echo isset($errors['House']) ? 'input_error' : '' ?>"
                            value="<?php echo $form_data['House'] ?? ''; ?>">
                    </section>
                    <section>
                        <label for="Street">Street: </label>
                        <br>
                        <input type="text" name="Street" placeholder="Street" id="Street"
                            class="text__input <?php echo isset($errors['Street']) ? 'input_error' : '' ?>"
                            value="<?php echo $form_data['Street'] ?? ''; ?>">
                    </section>
                    <section>
                        <label for="City">City: </label>
                        <br>
                        <input type="text" name="City" placeholder="City" id="City"
                            class="text__input <?php echo isset($errors['City']) ? 'input_error' : '' ?>"
                            value="<?php echo $form_data['City'] ?? ''; ?>">
                    </section>
                    <section>
                        <label for="Country">Country: </label>
                        <br>
                        <input type="text" name="Country" placeholder="Country" id="Country"
                            class="text__input <?php echo isset($errors['Country']) ? 'input_error' : '' ?>"
                            value="<?php echo $form_data['Country'] ?? ''; ?>">
                    </section>
                </fieldset>
                <section>
                    <label for="DateOfBirth">Date of Birth: </label>
                    <br>
                    <input type="date" name="DateOfBirth" placeholder="Date of Birth" id="DateOfBirth"
                        class="text__input <?php echo isset($errors['DateOfBirth']) ? 'input_error' : '' ?>"
                        value="<?php echo $form_data['DateOfBirth'] ?? ''; ?>">
                </section>
                <section>
                    <label for="IDNumber">ID Number: </label>
                    <br>
                    <input type="text" name="IDNumber" placeholder="ID Number" id="IDNumber"
                        class="text__input <?php echo isset($errors['IDNumber']) ? 'input_error' : '' ?>"
                        value="<?php echo $form_data['IDNumber'] ?? ''; ?>">
                </section>
                <section>
                    <label for="Email">Email: </label>
                    <br>
                    <input type="email" name="Email" placeholder="example@email.com" id="Email"
                        class="text__input <?php echo isset($errors['Email']) ? 'input_error' : '' ?>"
                        value="<?php echo $form_data['Email'] ?? ''; ?>">
                </section>
                <section>
                    <label for="Telephone">Telephone: </label>
                    <br>
                    <input type="text" name="Telephone" placeholder="Telephone" id="Telephone"
                        class="text__input <?php echo isset($errors['Telephone']) ? 'input_error' : '' ?>"
                        value="<?php echo $form_data['Telephone'] ?? ''; ?>">
                </section>
                <section>
                    <label for="Role">Role: </label>
                    <br>
                    <select name="Role" id="Role"
                        class="text__input <?php echo isset($errors['Role']) ? 'input_error' : '' ?>">
                        <option value="">Select Role</option>
                        <option value="Manager" <?php echo (isset($form_data['Role']) && $form_data['Role'] == 'Manager') ? 'selected' : ''; ?>>Manager</option>
                        <option value="Project Leader" <?php echo (isset($form_data['Role']) && $form_data['Role'] == 'Project Leader') ? 'selected' : ''; ?>>Project Leader</option>
                        <option value="Team Member" <?php echo (isset($form_data['Role']) && $form_data['Role'] == 'Team Member') ? 'selected' : ''; ?>>Team Member</option>
                    </select>
                </section>
                <section>
                    <label for="Qualification">Qualification: </label>
                    <br>
                    <input type="text" name="Qualification" placeholder="Qualification" id="Qualification"
                        class="text__input <?php echo isset($errors['Qualification']) ? 'input_error' : '' ?>"
                        value="<?php echo $form_data['Qualification'] ?? ''; ?>">
                </section>
                <section>
                    <label for="Skills">Skills: </label>
                    <br>
                    <input type="text" name="Skills" placeholder="Skills" id="Skills"
                        class="text__input <?php echo isset($errors['Skills']) ? 'input_error' : '' ?>"
                        value="<?php echo $form_data['Skills'] ?? ''; ?>">
                </section>
                <section>
                    <button type="submit">Proceed</button>
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
            </form>
        </fieldset>
        </section>
        
    </main>
</body>

</html>