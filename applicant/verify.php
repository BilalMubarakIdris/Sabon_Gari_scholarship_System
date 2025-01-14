<?php
require '../includes/db.php';

if (isset($_GET['email']) && isset($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];

    // Prepare and execute the query
    $stmt = $conn->prepare("SELECT * FROM applicants WHERE email = ? AND token = ? AND email_verified = 0");
    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a matching applicant is found
    if ($result->num_rows === 1) {
        // Update email_verified status
        $update_stmt = $conn->prepare("UPDATE applicants SET email_verified = 1, token = NULL WHERE email = ?");
        $update_stmt->bind_param("s", $email);
        if ($update_stmt->execute()) {
            echo "Email verified successfully! You can now <a href='login.php'>login</a>.";
        } else {
            echo "Failed to verify email. Please try again.";
        }
    } else {
        echo "Invalid verification link or email already verified.";
    }
} else {
    echo "Invalid request.";
}
?>
