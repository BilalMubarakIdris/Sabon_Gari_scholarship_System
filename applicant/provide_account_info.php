<?php
require '../includes/db.php'; // Include your database connection
session_start();

// Check if the user is logged in
if (!isset($_SESSION['applicant_logged_in']) || $_SESSION['applicant_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$applicant_id = $_SESSION['applicant_id']; // Get the logged-in user's ID

// Fetch the user's current bank details from the database
$stmt = $conn->prepare("SELECT bank_name, account_number, account_name FROM applicants WHERE id = ?");
$stmt->bind_param("i", $applicant_id);
$stmt->execute();
$result = $stmt->get_result();
$applicant_data = $result->fetch_assoc();

// Handle form submission to update bank details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bank_name = htmlspecialchars($_POST['bank_name']);
    $account_number = htmlspecialchars($_POST['account_number']);
    $account_name = htmlspecialchars($_POST['account_name']);

    // Update the bank details in the database
    $update_stmt = $conn->prepare("UPDATE applicants SET bank_name = ?, account_number = ?, account_name = ? WHERE id = ?");
    $update_stmt->bind_param("sssi", $bank_name, $account_number, $account_name, $applicant_id);

    if ($update_stmt->execute()) {
        $_SESSION['success_message'] = "Account details updated successfully!";
        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Failed to update account details. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Provide Account Info</title>
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
            max-width: 600px;
            margin: 40px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            opacity: 0.9;
        }

        .form-container label {
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
            color: #333;
        }

        .form-container input {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #f9f9f9;
            font-size: 16px;
            box-sizing: border-box;
        }

        .form-container button {
            width: 100%;
            padding: 14px;
            background-color: #007bff;
            color: white;
            border: none;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
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
            font-size: 16px;
        }

        .success-message {
            color: green;
            text-align: center;
            font-size: 16px;
        }

        .error-message {
            color: red;
            text-align: center;
            font-size: 16px;
        }
    </style>
</head>
<body>

    <!-- Form to provide/update bank details -->
    <div class="form-container">
    <h2>Provide Bank Account Information</h2>

    <?php
    // Display success or error messages if available
    if (isset($_SESSION['success_message'])) {
        echo "<p class='success-message'>".$_SESSION['success_message']."</p>";
        unset($_SESSION['success_message']);
    }

    if (isset($_SESSION['error_message'])) {
        echo "<p class='error-message'>".$_SESSION['error_message']."</p>";
        unset($_SESSION['error_message']);
    }
    ?>
        <form method="POST">
            <label for="bank_name">Bank Name:</label>
            <input type="text" name="bank_name" id="bank_name" value="<?php echo htmlspecialchars($applicant_data['bank_name'] ?? ''); ?>" required><br>

            <label for="account_number">Account Number:</label>
            <input type="text" name="account_number" id="account_number" value="<?php echo htmlspecialchars($applicant_data['account_number'] ?? ''); ?>" required><br>

            <label for="account_name">Account Name:</label>
            <input type="text" name="account_name" id="account_name" value="<?php echo htmlspecialchars($applicant_data['account_name'] ?? ''); ?>" required><br>

            <button type="submit">Save Account Info</button>
        </form>
        <div class="links">
            <a href="index.php">Back to Homepage</a>
            <a href="dashboard.php">| Dashboard</a>
        </div>
    </div>

</body>
</html>
