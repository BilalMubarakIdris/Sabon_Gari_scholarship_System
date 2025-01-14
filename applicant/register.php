<?php
require_once '../env_loader.php'; 
require '../includes/db.php'; // Include your database connection
// Include PHPMailer files
require_once '../PHPMailer/src/PHPMailer.php';
require_once '../PHPMailer/src/Exception.php';
require_once '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize form inputs
    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $othername = htmlspecialchars($_POST['othername']);
    $gender = $_POST['gender'];
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $school_name = htmlspecialchars($_POST['school_name']);
    $department = htmlspecialchars($_POST['department']);
    $course_of_study = htmlspecialchars($_POST['course_of_study']);
    $level = htmlspecialchars($_POST['level']);
    $ward = htmlspecialchars($_POST['ward']);
    $student_type = $_POST['student_type'];
    $residential_address = htmlspecialchars($_POST['residential_address']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $token = md5($email . time()); // Generate an email verification token

        // Define allowed wards
    $allowed_wards = [
        "Muchia", "Zabi", "Samaru", "Basawa", 
        "Bomo", "Jama’a", "Chikaji", "Dogarawa", 
        "Hanwa", "Jushin Waje", "Unguwan Gabas"
    ];

    if (!in_array($ward, $allowed_wards)) {
        die("Invalid ward selected.");
    }

    // Insert data into the database
    $stmt = $conn->prepare("INSERT INTO applicants 
        (firstname, lastname, othername, gender, email, school_name, department, course_of_study, level, ward, student_type, residential_address, password, token) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "ssssssssssssss", 
        $firstname, $lastname, $othername, $gender, $email, $school_name, $department, $course_of_study, $level, $ward, $student_type, $residential_address, $password, $token
    );

    if ($stmt->execute()) {
        // Email sending using PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = getenv('SMTP_HOST');
            $mail->SMTPAuth = filter_var(getenv('SMTP_AUTH'), FILTER_VALIDATE_BOOLEAN); // Convert "true"/"false" strings to boolean
            $mail->Username = getenv('SMTP_USERNAME');
            $mail->Password = getenv('SMTP_PASSWORD');
            $mail->SMTPSecure = getenv('SMTP_SECURE');
            $mail->Port = (int)getenv('SMTP_PORT');
            // Recipients
            $mail->setFrom(getenv('MAIL_FROM_ADDRESS'), getenv('MAIL_FROM_NAME'));
            $mail->addAddress($email); // Send to the applicant's email address

            // Email content
            $verification_link = "http://localhost/scholarship/applicant/verify.php?email=$email&token=$token";
            $mail->isHTML(true);
            $mail->Subject = 'Email Verification';
            $mail->Body    = "Dear $firstname,<br><br>
                Thank you for registering.<br>
                Click <a href='$verification_link'>here</a> to verify your email address.<br><br>
                Best regards,<br>Scholarship System.";
            
            // Enable SMTP debugging
            // $mail->SMTPDebug = 2; // Debug level: 2
            // $mail->Debugoutput = 'html';
            $mail->send();
            $_SESSION['flash_success']= 'Registration successful! A verification email has been sent to your email address.';
        } catch (Exception $e) {
            $_SESSION['flash_error']= "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Failed to register. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scholarship Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f4f4f4;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('../images/bg_register.jpg') no-repeat center center fixed;
        }
        form {
            background: #fff;
            opacity: 0.9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 100%;
            opacity: 0.9;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .form-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .form-group.full-width {
            grid-template-columns: 1fr;
        }
        input, select, textarea, button {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        textarea {
            resize: none;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            padding: 12px;
            font-size: 16px;
            transition: background 0.3s ease;
        }
        button:hover {
            background-color: #45a049;
        }
        .flash-message {
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            font-size: 16px;
        }
        .flash-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .flash-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    
    <form method="POST">
        <?php if (isset($_SESSION['flash_success'])): ?>
                <div class="flash-message flash-success">
                    <?php echo $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
                </div>
            <?php endif; ?>
        
            <?php if (isset($_SESSION['flash_error'])): ?>
                <div class="flash-message flash-error">
                    <?php echo $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
                </div>
            <?php endif; ?>
        <h2>Scholarship Application Registration</h2>
        <div class="form-group">
            <input type="text" name="firstname" placeholder="First Name" required>
            <input type="text" name="lastname" placeholder="Last Name" required>
        </div>
        <div class="form-group">
            <input type="text" name="othername" placeholder="Other Name">
        </div>
        <div class="form-group full-width">
            <label>
                <input type="radio" name="gender" value="Male" required> Male
            </label>
            <label>
                <input type="radio" name="gender" value="Female" required> Female
            </label>
        </div>
        <div class="form-group">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="text" name="school_name" placeholder="School Name" required>
        </div>
        <div class="form-group">
            <input type="text" name="department" placeholder="Department" required>
            <input type="text" name="course_of_study" placeholder="Course of Study" required>
        </div>
        <div class="form-group">
            <input type="text" name="level" placeholder="Level" required>
            <input type="password" name="password" placeholder="Password" required>
        </div>
        </div>
        <div class="form-group full-width">
        <label for="ward">Student Type:</label>
            <select name="student_type" required>
                <option value="New">New</option>
                <option value="Return">Return</option>
            </select>
        </div>
        <div class="form-group full-width">
    <label for="ward">Ward</label>
    <select name="ward" id="ward" required>
        <option value="Muchia">Muchia</option>
        <option value="Zabi">Zabi</option>
        <option value="Samaru">Samaru</option>
        <option value="Basawa">Basawa</option>
        <option value="Bomo">Bomo</option>
        <option value="Jama’a">Jama’a</option>
        <option value="Chikaji">Chikaji</option>
        <option value="Dogarawa">Dogarawa</option>
        <option value="Hanwa">Hanwa</option>
        <option value="Jushin Waje">Jushin Waje</option>
        <option value="Unguwan Gabas">Unguwan Gabas</option>
    </select>
</div>
        <div class="form-group full-width">
            <textarea name="residential_address" placeholder="Residential Address" required></textarea>
        </div>
        <div class="form-group full-width">
            <button type="submit">Register</button>
        </div>
        <p>Already have and account? <a href="login.php">Login</a></p>
    </form>
</body>
</html>
