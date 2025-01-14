<?php
require '../includes/db.php'; // Include the database connection

// Include PHPMailer files
require_once '../PHPMailer/src/PHPMailer.php';
require_once '../PHPMailer/src/Exception.php';
require_once '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Query to get all verified applicants' emails
    $result = $conn->query("SELECT email, firstname FROM applicants WHERE status = 'verified'");

    // Get the current date
    $currentDate = new DateTime();

    // Get the first day of the current month
    $firstDayOfMonth = new DateTime($currentDate->format('Y-m-01'));

    // Calculate the first two weeks of the month
    $twoWeeksLater = (clone $firstDayOfMonth)->modify('+14 days');

    // Format the date range (e.g., 1st to 14th of the current month)
    $dateRange = $firstDayOfMonth->format('F j') . " to " . $twoWeeksLater->format('F j');

    // Initialize PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Server settings for PHPMailer
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'bilalmubarakidris@gmail.com'; // Replace with your email
        $mail->Password = 'aefybzaccbczwkmb';           // Replace with your Gmail App Password
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        // Email content settings
        $mail->setFrom('bilalmubarakidris@gmail.com', 'Scholarship System');
        $mail->isHTML(true);
        $mail->Subject = 'Scholarship Screening Invitation';
        $mail->Body = "Dear Applicant,<br><br>
                       Please attend the screening between <strong>$dateRange</strong>.<br><br>
                       Best regards,<br>Scholarship System.";

        // Loop through each verified applicant
        while ($row = $result->fetch_assoc()) {
            // Add the recipient's email for each applicant
            $mail->addAddress($row['email'], $row['firstname']);
            
            // Send the email
            $mail->send();

            // Clear all addresses for the next iteration
            $mail->clearAddresses();
        }

        echo "Emails sent successfully!";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Call for Screening</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        button {
            padding: 12px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .links {
            margin-top: 30px;
            text-align: center;
        }

        .links a {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 0 10px;
            transition: background-color 0.3s ease;
        }

        .links a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Call for Screening</h2>
        <form method="POST">
            <button type="submit">Send Screening Emails</button>
        </form>
        <div class="links">
            <a href="../index.php">Home</a>
            <a href="dashboard.php">Dashboard</a>
        </div>
    </div>
</body>
</html>

