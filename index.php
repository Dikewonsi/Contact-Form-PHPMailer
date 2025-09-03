<?php
    if($_SERVER["REQUEST_METHOD"] === "POST") {
        $name = trim($_POST["name"]);
        $email = trim($_POST["email"]);
        $message = trim($_POST["message"]);

        $errors = [];

        if(empty($name)) $errors[] = "name is required.";
        
        if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors [] = "Valid email is required";

        if(empty($message)) $errors[] = "Message is required.";

        if(empty($errors)) {
            echo "<p style='color:green;'>Form is valid (PHPMailer will go here)</p>";
        } else {
            foreach ($errors as $error) {
                echo "<p style='color:red;'>$error</p>";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
</head>
<body>
    <h1>Contact Us</h1>
    <form action="" method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="message">Message:</label>
        <textarea id="message" name="message" required></textarea>
        <br>
        <input type="submit" value="Send">
    </form>
</body>
</html>