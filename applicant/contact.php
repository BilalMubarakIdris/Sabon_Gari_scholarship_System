<?php
require '../includes/db.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Validate fields
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
    } elseif (empty($message)) {
        echo "Message cannot be empty.";
    } else {
        // Insert the inquiry into the database
        $stmt = $conn->prepare("INSERT INTO messages (email, message) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $message);

        if ($stmt->execute()) {
            echo "Your inquiry has been submitted successfully!";
        } else {
            echo "Failed to submit your inquiry. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
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
            max-width: 600px;
            margin: 40px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            opacity: 0.9;
        }

        .content-container input, .content-container textarea {
            width: 100%;
            padding: 12px;
            margin: 12px 0;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .content-container textarea {
            height: 150px;
            resize: vertical;
        }

        .content-container button {
            padding: 14px 24px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        .content-container button:hover {
            background-color: #0056b3;
        }

        .links {
            margin-top: 20px;
            font-size: 16px;
            text-align: center;
        }

        .links a {
            text-decoration: none;
            color: #007bff;
        }

        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="content-container">
        <h2>Contact Us</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Your Email Address" required>
            <textarea name="message" placeholder="Your Message" required></textarea>
            <button type="submit">Submit</button>
        </form>
        <div class="links">
            <a href="dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>

