<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


include "../php/main.php";
$errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (empty($_POST['UserName'])) {
        $errors['UserName'] = 'Please Input UserName'; // UserName is empty
    } else if (strlen($_POST['UserName'] < 6 || strlen($_POST['UserName']) > 13)) {
        $errors['UserName'] = 'Username must be between 6 and 12 characters '; // Username is not between 6 and 20 characters
    } else if (!ctype_alnum($_POST['UserName'])) { // reference: https://www.php.net/manual/en/function.ctype-alnum.php
        $errors['UserName'] = 'Username must be alphanumeric'; // Username is not alphanumeric
    } else if (userExists($_POST['UserName'])) {
        $errors['UserName'] = 'Username already exists'; // Username already exists
    }

    if (empty($_POST['Password'])) {
        $errors['Password'] = 'Please Input Password'; // Password is empty
    } else if (!preg_match('/^(?=.*[a-zA-Z])(?=.*\d).{8,12}$/', $_POST['Password'])) { // reference: https://www.php.net/manual/en/function.preg-match.php
        $errors['Password'] = 'Password must be 8-12 characters long, contain at least one letter, and one digit.'; // Password in invalid
    }

    if (empty($_POST['Confirm'])) {
        $errors['Confirm'] = 'Please Confirm Password'; // Confirm is empty
    }
    if ($_POST["Password"] !== $_POST["Confirm"]) {
        $errors['Confirm'] = 'Passwords do not match'; // Passwords do not match
    }


    if (empty($errors)) {
        $_SESSION['user_signUp']['UserName'] = $_POST['UserName'] ?? '';
        $_SESSION['user_signUp']['Password'] = $_POST['Password'] ?? '';
        header('Location: register3.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body> 
    <?php include "../php/header.php" ?>

    
<main class="main">

<section class="content">
<h2>E-Account Creation</h2>  
<fieldset  class = "form-container">
    <form action="register2.php" method="post">
        <section>
            <label for="UserName">Username: </label>
            <br>
            <input type="text" name="UserName" placeholder="User Name" id="UserName" class="text__input <?php echo isset($errors['UserName']) ? 'input_error' : '' ?>" value="<?php echo $_POST['UserName'] ?? ''; ?>">
        </section>
        <section>
            <label for="Password">Password: </label>
            <br>
            <input type="password" name="Password" placeholder="Password" id="Password" class="text__input <?php echo isset($errors['Password']) ? 'input_error' : '' ?>" value="<?php echo $_POST['Password'] ?? ''; ?>">
        </section>
        <section>
            <label for="Confirm">Confirm Password: </label>
            <br>
            <input type="password" name="Confirm" placeholder="Confirm Password" id="Confirm" class="text__input <?php echo isset($errors['Confirm']) ? 'input_error' : '' ?>" value="<?php echo $_POST['Confirm'] ?? ''; ?>">
        </section>
        <input type="submit" value="Proceed to Confirmation" class="button">
        <section>
        <?php if (!empty($errors)) { ?>
                    <section>
                        <Label name="error" class="error_label">
                            <?php
                            echo reset($errors);
                            ?>
                        </Label>
                    </section>
                <?php } ?>
                </section>
    </form>
</fieldset>
</section>
</main>
</body>
</html>