# PHP Contact Form

A simple contact form built with **PHP** that allows users to send messages via email.  
The project includes **form validation**, **error handling**, and **email delivery** using [PHPMailer](https://github.com/PHPMailer/PHPMailer).

---

## ✨ Features
- Input validation (name, email, message)
- Input sanitization (XSS and SQL injection protection)
- Error and success messages
- Email sending with PHPMailer
- Bootstrap styling for clean UI
- Lightweight and beginner-friendly

---

## 🛠️ Technologies Used
- PHP (Procedural)
- PHPMailer
- HTML5 / CSS3 / Bootstrap
- Git & GitHub

---

## 📂 Project Structure
php-contact-form/
│── index.php # Contact form + PHP logic
│── style.css # Optional custom styling
│── README.md # Documentation
│── /assets # (Optional) images/screenshots

---

## ⚙️ Setup Instructions
1. Clone the repo:
   ```bash
   git clone https://github.com/dikewonsi/Contact-Form-PHPMailer.git
2. Open the project in your local server environment (XAMPP, WAMP, Laragon, etc.).
3. Install PHPMailer
   composer require phpmailer/phpmailer
4. Configure email settings inside index.php:
     $mail->Host = 'smtp.example.com';
     $mail->Username = 'you@example.com';
     $mail->Password = 'yourpassword';
5. Run the app → submit a test message → check your inbox.


👨‍💻 Author

Jeffrey Isibor

GitHub: @Dikewonsi
