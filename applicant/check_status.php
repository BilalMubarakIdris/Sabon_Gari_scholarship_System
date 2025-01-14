<?php
require '../includes/db.php';

$status_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $status_message = "Invalid email address.";
    } else {
        // Check status in the database
        $stmt = $conn->prepare("SELECT status FROM applicants WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $applicant = $result->fetch_assoc();
            if ($applicant['status'] == 'Verified') {
                $status_message = "Congratulations! You have been shortlisted for the scholarship.";
            } else {
                $status_message = "Your application is still pending.";
            }
        } else {
            $status_message = "No record found for this email.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Check Scholarship Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fa;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('../images/background.jpg') no-repeat center center fixed;
        }

        h2 {
            text-align: center;
            margin-top: 40px;
            color: #333;
        }

        .form-container {
            max-width: 400px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            opacity: 0.9;
        }

        .form-container input, .form-container button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .form-container button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }

        .links {
            text-align: center;
            margin-top: 20px;
        }

        .links a {
            text-decoration: none;
            color: #007bff;
            margin: 0 15px;
            font-size: 16px;
        }

        .links a:hover {
            text-decoration: underline;
        }

        p {
            text-align: center;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Scholarship Status</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Enter Your Email Address" required>
            <button type="submit">Check Status</button>
        </form>
        <p><?php echo $status_message; ?></p>
        <div class="links">
            <a href="../index.php">Back to Homepage</a>
            <a href="dashboard.php">| Dashboard</a>
        </div>
    </div>
    
</body>
</html>

