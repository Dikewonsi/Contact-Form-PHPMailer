<?php
    //First Load Composer's autoloader
    require 'vendor/autoload.php';

    //Import PHPMailer classes for use
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    //Proceed with form processing
    if($_SERVER["REQUEST_METHOD"] === "POST") {
        $name = trim($_POST["name"] ?? '');
        $email = trim($_POST["email"] ?? '');
        $message = trim($_POST["message"] ?? '');

        $errors = [];

        // 1. Validate fields
        if (empty($name)) {
            $errors[] = "Name is required";
        } else {
            // Strip tags to avoid <script>, encode special chars to avoid XSS
            $name = htmlspecialchars(strip_tags($name), ENT_QUOTES, 'UTF-8');
        }

        if (empty($email)) {
            $errors[] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        } else {
            //Extra santization (XSS Safe)
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        }

        if (empty($message)) {
            $errors[] = "Message is required";
        } else {
            //Remove HTML tags, then escape special chars
            $message = htmlspecialchars(strip_tags($message), ENT_QUOTES, 'UTF-8)');
        }
        
        //2. If no errors -> safely send email or insert into DB
        if (empty($errors)) {
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host     = 'smtp.gmail.com';       //SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'user1@gmail.com';         //SMTP username
                $mail->Password = 'abcd efgh klmn opqr';               //SMTP password (Create an App Password, GMail does not allow regular passwords)
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port     = 587;      

                //Recipients
                $mail->setFrom($email, $name);              //From user submission
                $mail->addAddress('user2@gmail.com');       //Email to receive the message

                //Message
                $mail->isHTML(true);
                $mail->Subject = "New Contact Form Submission";
                $mail->Body = "
                    <h3>You have a new message from your website contact form:</h3>
                    <p><strong>Name:</strong> $name</p>
                    <p><strong>Email:</strong> $email</p>
                    <p><strong>Message:</strong> $message</p>
                ";

                $mail->AltBody = "Name: $name\nEmail: $email\nMessage: $message";

                $mail->send();
                echo "<p style='color: green;'>Form Submitted Successfully!</p>";
            } catch (Exception $e) {
                echo "<p style='color: red;'>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</p>";
            }

            //Example if inserting into DB with PDO (safe against SQLi)
            /*
            $stmt = $pdo->prepare("INSERT INTO contact_form (name, email, message) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $message]);
            */
        } else {
            foreach ($errors as $error) {
                echo "<p style='color: red;'>$error</p>";
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
        <input type="text" id="name" name="name">
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email">
        <br>
        <label for="message">Message:</label>
        <textarea id="message" name="message"></textarea>
        <br>
        <input type="submit" value="Send">
    </form>
</body>
</html>