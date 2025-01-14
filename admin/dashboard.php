<?php
require '../includes/db.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            max-width: 1200px;
            margin: 50px auto;
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

        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        li {
            margin: 10px 0;
        }

        a {
            display: block;
            padding: 12px;
            color: #007bff;
            text-decoration: none;
            background-color: #f8f9fa;
            border-radius: 4px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #007bff;
            color: white;
        }

        .logout {
            background-color: #dc3545;
            color: white;
        }

        .logout:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Dashboard</h2>
        <ul>
            <li><a href="../index.php">Home</a></li>
            <li><a href="view_applicants.php">View Applicants</a></li>
            <li><a href="call_for_screening.php">Call for Screening</a></li>
            <li><a href="view_successful_applicants.php">View Successful Applicants</a></li>
            <li><a href="add_admin.php">Add Admin</a></li>
            <li><a href="view_inquiries.php">View Comments</a></li>
            <li><a href="logout.php" class="logout">Logout</a></li>
        </ul>
    </div>
</body>
</html>

