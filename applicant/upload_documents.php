<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['applicant_id'])) {
    header("Location: login.php");
    exit;
}

$applicant_id = $_SESSION['applicant_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $upload_dir = '../uploads/';
    $indigene_doc = $_FILES['indigene_document']['name'];
    $admission_doc = $_FILES['admission_document']['name'];
    $id_card_doc = $_FILES['id_card_document']['name'];
    $last_payment_doc = $_FILES['payment_document']['name'];

    move_uploaded_file($_FILES['indigene_document']['tmp_name'], $upload_dir . $indigene_doc);
    move_uploaded_file($_FILES['admission_document']['tmp_name'], $upload_dir . $admission_doc);
    move_uploaded_file($_FILES['id_card_document']['tmp_name'], $upload_dir . $id_card_doc);
    move_uploaded_file($_FILES['payment_document']['tmp_name'], $upload_dir . $last_payment_doc);

    $stmt = $conn->prepare("UPDATE applicants SET indigene_document = ?, admission_document = ?, id_card_document = ?, payment_document = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $indigene_doc, $admission_doc, $id_card_doc, $last_payment_doc, $applicant_id);

    if ($stmt->execute()) {
        $_SESSION['flash_success'] = "Documents uploaded successfully!";
    } else {
        $_SESSION['flash_error'] = "Failed to upload documents.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Documents</title>
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
            max-width: 500px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            opacity: 0.9;
        }

        .form-container input[type="file"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
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
            margin-top: 20px;
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
    <div class="form-container">
    <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="flash-message flash-success">
            <?php 
            echo $_SESSION['flash_success']; 
            unset($_SESSION['flash_success']); // Clear message
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="flash-message flash-error">
            <?php 
            echo $_SESSION['flash_error']; 
            unset($_SESSION['flash_error']); // Clear message
            ?>
        </div>
    <?php endif; ?>
        <h2>Upload Documents</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="indigene_document" required>
            <input type="file" name="admission_document" required>
            <input type="file" name="id_card_document" required>
            <input type="file" name="payment_document" required>
            <button type="submit">Upload</button>
        </form>
        <div class="links">
            <a href="index.php">Back to Homepage</a>
            <a href="dashboard.php">| Dashboard</a>
        </div>
    </div>

</body>
</html>

