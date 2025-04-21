<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
ini_set('display_errors', 1);
error_reporting(E_ALL);

include "../php/main.php";

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    if (empty($_POST['username'])) {
        $errors['username'] = 'Please Input Username'; // username is empty
    }
    if (empty($_POST['password'])) {
        $errors['password'] = 'Please Input Password';
    }

    $username = $_POST['username'];
    $password = $_POST['password'];
    if (userLogin($username, $password)) {

        header('Location: home.php');
    } else {
        $errors[] = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../styles.css">
</head>

<body> 
    <?php include "../php/header.php" ?>

    
<main class="main">

<section class="content">
    <h2>Login</h2>
    <fieldset  class = "form-container">
    <form action="login.php" method="post">
        <section>
            <label for="username">Username: </label>
            <br>
            <input type="text" name="username" placeholder="username" id="username" class="text__input <?php echo isset($errors['username']) ? 'input_error' : '' ?>">
        </section>
        <section>
            <label for="password">Password: </label>
            <br>
            <input type="password" name="password" placeholder="Password" id="Password" class="text__input <?php echo isset($errors['password']) ? 'input_error' : '' ?>">
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
        <button type="submit" name="login-submit">Login</button>
    </form>
    </fieldset>
        </section>
    </main>
</body>

</html>