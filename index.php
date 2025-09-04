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
        $success = '';

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
                $mail->Password = 'abcd wxyz yuio asdf';               //SMTP password (Create an App Password, GMail does not allow regular passwords)
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
                $success = "Form Submitted Successfully!";
            } catch (Exception $e) {
                $errors[] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }

            //Example if inserting into DB with PDO (safe against SQLi)
            /*
            $stmt = $pdo->prepare("INSERT INTO contact_form (name, email, message) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $message]);
            */
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Bundle with Popper (for JS features like modals, dropdowns) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <form action="" method="POST" class="w-50 mx-auto mt-5 p-4 border rounded shadow">
            <h3 class="mb-4 text-center">Contact Us</h3>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                <p><?= $error ?></p>
                    <?php endforeach; ?>
                </div>
            <?php elseif ($success): ?>
                <div class="alert alert-success">
                    <p><?= $success ?></p>
                </div>
            <?php endif; ?>

            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Enter your name" value="<?= htmlspecialchars($name ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="text" id="email" name="email" class="form-control" placeholder="Enter your email" value="<?= htmlspecialchars($email ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="message" class="form-label">Message:</label>
                <textarea id="message" name="message" class="form-control" rows="4" placeholder="Write your message"><?= htmlspecialchars($message ?? '') ?></textarea>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Send</button>
            </div>
        </form>
    </body>
</html>