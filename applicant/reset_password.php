<?php
require '../includes/db.php';

if (isset($_GET['email']) && isset($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];

    // Verify token
    $stmt = $conn->prepare("SELECT * FROM applicants WHERE email = ? AND reset_token = ?");
    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            // Update the password in the database and clear the token
            $stmt = $conn->prepare("UPDATE applicants SET password = ?, reset_token = NULL WHERE email = ?");
            $stmt->bind_param("ss", $new_password, $email);

            if ($stmt->execute()) {
                echo "Password reset successfully! You can now <a href='login.php'>login</a>.";
            } else {
                echo "Failed to reset password. Please try again.";
            }
        }
    } else {
        echo "Invalid or expired token.";
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form method="POST">
        <input type="password" name="password" placeholder="Enter your new password" required>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
