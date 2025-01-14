<?php
session_start(); // Start session to store temporary data
require '../includes/db.php'; // Include your database connection
// Include PHPMailer files
require_once '../PHPMailer/src/PHPMailer.php';
require_once '../PHPMailer/src/Exception.php';
require_once '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['update_status'])) {
    $applicant_id = $_POST['applicant_id'];
    $status = $_POST['status'];
    $email = $_POST['email'];
    $firstname = $_POST['firstname']; // Get first name from the form

    // Update applicant status
    $stmt = $conn->prepare("UPDATE applicants SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $applicant_id);
    $stmt->execute();

    // Check if the email has already been sent to prevent resending
    if (!isset($_SESSION['email_sent'][$applicant_id])) {
        // Send email notification
        $message = $status === 'verified' 
            ? "Dear $firstname,<br><br>Congrats, your application has been verified!" 
            : "Dear $firstname,<br><br>Sorry, your application has been rejected.";

        // Send email using PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'bilalmubarakidris@gmail.com'; // Replace with your email
            $mail->Password = 'aefybzaccbczwkmb'; // Replace with your Gmail App Password
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            // Recipients
            $mail->setFrom('bilalmubarakidris@gmail.com', 'Scholarship System');
            $mail->addAddress($email); // Send to the applicant's email address

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Scholarship Application Status';
            $mail->Body = $message;

            // Send email
            $mail->send();

            // Mark email as sent by storing in session
            $_SESSION['email_sent'][$applicant_id] = true;

            echo 'Status updated and email sent!';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo 'Email has already been sent for this applicant.';
    }
}

$result = $conn->query("SELECT * FROM applicants");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Applicants</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #f8f9fa;
            color: #333;
        }

        table td a {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        table td a:hover {
            color: #0056b3;
        }

        .links {
            text-align: center;
            margin-top: 30px;
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

        select {
            padding: 8px;
            font-size: 14px;
            margin-right: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        button {
            padding: 8px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #218838;
        }

        .document-list {
            list-style-type: none;
            padding-left: 0;
        }

        .document-list li {
            margin: 5px 0;
        }

        .document-list p {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Applicants</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Gender</th>
                <th>School Name</th>
                <th>Department</th>
                <th>Course of Study</th>
                <th>Level</th>
                <th>Ward</th>
                <th>Student Type</th>
                <th>Residential Address</th>
                <th>Bank Name</th>
                <th>Account Number</th>
                <th>Account Name</th>
                <th>Document</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['firstname'] . ' ' . $row['lastname']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['gender']; ?></td>
                <td><?php echo $row['school_name']; ?></td>
                <td><?php echo $row['department']; ?></td>
                <td><?php echo $row['course_of_study']; ?></td>
                <td><?php echo $row['level']; ?></td>
                <td><?php echo $row['ward']; ?></td>
                <td><?php echo $row['student_type']; ?></td>
                <td><?php echo $row['residential_address']; ?></td>
                <td><?php echo $row['bank_name']; ?></td>
                <td><?php echo $row['account_number']; ?></td>
                <td><?php echo $row['account_name']; ?></td>
                <td>
                    <!-- Display uploaded documents -->
                    <h3>Uploaded Documents</h3>
                    <ul class="document-list">
                        <?php if (!empty($row['indigene_document'])): ?>
                            <li><a href="../uploads/<?php echo htmlspecialchars($row['indigene_document']); ?>" target="_blank">Indigene Document</a></li>
                        <?php endif; ?>
                        <?php if (!empty($row['admission_document'])): ?>
                            <li><a href="../uploads/<?php echo htmlspecialchars($row['admission_document']); ?>" target="_blank">Admission Document</a></li>
                        <?php endif; ?>
                        <?php if (!empty($row['id_card_document'])): ?>
                            <li><a href="../uploads/<?php echo htmlspecialchars($row['id_card_document']); ?>" target="_blank">ID Card</a></li>
                        <?php endif; ?>
                        <?php if (!empty($row['payment_document'])): ?>
                            <li><a href="../uploads/<?php echo htmlspecialchars($row['payment_document']); ?>" target="_blank">Payment Document</a></li>
                        <?php endif; ?>
                        <?php if (empty($row['indigene_document']) && empty($row['admission_document']) && empty($row['id_card_document']) && empty($row['payment_document'])): ?>
                            <p>No documents uploaded yet.</p>
                        <?php endif; ?>
                    </ul>
                </td>
                <td><?php echo $row['status']; ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="applicant_id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="email" value="<?php echo $row['email']; ?>">
                        <input type="hidden" name="firstname" value="<?php echo $row['firstname']; ?>"> <!-- First name added -->
                        <select name="status">
                            <option value="verified" <?php echo ($row['status'] == 'verified') ? 'selected' : ''; ?>>Verify</option>
                            <option value="rejected" <?php echo ($row['status'] == 'rejected') ? 'selected' : ''; ?>>Reject</option>
                        </select>
                        <button type="submit" name="update_status">Update</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>
        <div class="links">
            <a href="../index.php">Home</a>
            <a href="dashboard.php">Dashboard</a>
        </div>
    </div>
</body>
</html>

