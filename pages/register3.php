<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

include "../php/main.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($user = createUser() != null) {
        header('Location: ./home.php');
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
<body> 
    <?php include "../php/header.php" ?>

    
<main class="main">

<section class="content">
    <h2>E-Account Creation</h2>
    <table class="user_table">
        <?php
        $form_data = $_SESSION['user_signUp'];
        foreach ($form_data as $key => $value) {
            if ($key == 'Password') {
                continue;
            }
            echo "<tr><td>$key</td><td>$value</td></tr>";
        }
        ?>
    </table>
    <form action="register.php" method="post">
        <?php
        foreach ($form_data as $key => $value) {
            echo "<input type='hidden' name='$key' value='$value'>"; // hidden input to pass form data back to registration.php : https://stackoverflow.com/questions/10547694/go-back-in-multiple-pages-form 
        }
        ?>
        <input type="hidden" name="submission_type" value="edit"> <!-- indicate that the request is an edit request -->
        <button type="submit" value="Return" onclick="return confirm('Are you sure you want to return to user Information page?')">Return</button>

    </form>
    <form action="register3.php" method="post">
        <button type="submit">Confirm</button>
    </form>
</section>
</main>
</body>

</html>