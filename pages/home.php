<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
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
<?php
include "../php/header.php";
echo "<section class='main'>";
include "../php/navbar.php";
?>

<main>

<img src="../20945482.jpg" alt="Image of a person with a laptop" class="main_image">
</main>
</section>


<?php
include "../php/footer.php";
?>

</body>
</html>
