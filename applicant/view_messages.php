<?php
require '../includes/db.php';

// Check if the applicant is logged in
session_start();
if (!isset($_SESSION['applicant_logged_in']) || $_SESSION['applicant_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Get the logged-in user's email
$user_email = $_SESSION['email']; // Assuming email is stored in the session

// Fetch inquiries specific to the logged-in user
$stmt = $conn->prepare("SELECT * FROM messages WHERE email = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

// Handle form submission for replies
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reply'])) {
    $reply = $_POST['reply'];
    $inquiry_id = $_POST['inquiry_id'];

    // Update the inquiry with the admin's reply
    $stmt = $conn->prepare("UPDATE messages SET reply = ? WHERE id = ? AND email = ?");
    $stmt->bind_param("sis", $reply, $inquiry_id, $user_email);

    if ($stmt->execute()) {
        echo "<script>alert('Reply sent successfully!');</script>";
    } else {
        echo "<script>alert('Failed to send reply. Please try again.');</script>";
    }

    // Refresh the page to show the updated data
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant - View Inquiries</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('../images/background.jpg') no-repeat center center fixed;
        }

        h2 {
            text-align: center;
            margin-top: 40px;
            color: #333;
        }

        .content-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            opacity: 0.9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            resize: vertical;
        }

        button {
            padding: 12px 20px;
            font-size: 16px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        .links {
            text-align: center;
            margin-top: 20px;
        }

        .links a {
            text-decoration: none;
            color: #007bff;
            font-size: 16px;
        }

        .links a:hover {
            text-decoration: underline;
        }

    </style>
</head>
<body>
<div class="content-container">
        <h2>Your Inquiries</h2>
        <table>
            <tr>
                <th>Email</th>
                <th>Message</th>
                <th>Reply</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
                <td>
                    <?php 
                        if ($row['reply']) {
                            echo nl2br(htmlspecialchars($row['reply']));
                        } else {
                            echo "No reply yet";
                        }
                    ?>
                </td>
                <td>
                    <?php if (empty($row['reply'])) { ?>
                        <!-- Only show reply form if no reply is set -->
                        <form method="POST">
                            <textarea name="reply" placeholder="Your reply" required></textarea>
                            <input type="hidden" name="inquiry_id" value="<?php echo $row['id']; ?>">
                            <button type="submit">Send Reply</button>
                        </form>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </table>
        <div class="links">
            <a href="dashboard.php">Back to Dashboard</a> |
            <a href="contact.php">Send Message</a>
        </div>
    </div>
</body>
</html>

