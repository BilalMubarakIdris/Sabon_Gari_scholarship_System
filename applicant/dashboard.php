<?php
session_start();
require '../includes/db.php';

// Check if the applicant is logged in
if (!isset($_SESSION['applicant_logged_in']) || $_SESSION['applicant_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Fetch applicant details
$applicant_id = $_SESSION['applicant_id'];
$stmt = $conn->prepare("SELECT * FROM applicants WHERE id = ?");
$stmt->bind_param("i", $applicant_id);
$stmt->execute();
$result = $stmt->get_result();
$applicant = $result->fetch_assoc();

// Function to check if file already exists
function checkFileExists($fileName, $applicantId) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM applicants WHERE id = ? AND (indigene_document = ? OR admission_document = ? OR id_card_document = ? OR payment_document = ?)");
    $stmt->bind_param("issss", $applicantId, $fileName, $fileName, $fileName, $fileName);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

// Handle file uploads
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uploadDirectory = '../uploads/';
    $files = $_FILES;

    foreach ($files as $fileKey => $file) {
        // Handle different document types
        $fileName = basename($file["name"]);
        $targetFilePath = $uploadDirectory . $fileName;

        // Check if the file already exists in the database
        if (checkFileExists($fileName, $applicant_id)) {
            echo "The file '$fileName' has already been uploaded.";
        } else {
            if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
                // Update the database with the new file name
                switch ($fileKey) {
                    case 'indigene_document':
                        $stmt = $conn->prepare("UPDATE applicants SET indigene_document = ? WHERE id = ?");
                        break;
                    case 'admission_document':
                        $stmt = $conn->prepare("UPDATE applicants SET admission_document = ? WHERE id = ?");
                        break;
                    case 'id_card_document':
                        $stmt = $conn->prepare("UPDATE applicants SET id_card_document = ? WHERE id = ?");
                        break;
                    case 'payment_document':
                        $stmt = $conn->prepare("UPDATE applicants SET payment_document = ? WHERE id = ?");
                        break;
                }

                if (isset($stmt)) {
                    $stmt->bind_param("si", $fileName, $applicant_id);
                    $stmt->execute();
                    echo "File uploaded successfully.";
                }
            } else {
                echo "There was an error uploading your file.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        header {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
        }

        header h2 {
            margin: 0;
        }

        .navbar {
            display: flex;
            justify-content: center;
            background-color: #0056b3;
            padding: 10px 0;
        }

        .navbar ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 15px;
        }

        .navbar ul li {
            display: inline;
        }

        .navbar ul li a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
        }

        .navbar ul li a:hover {
            background-color: #007bff;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .profile-section {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-photo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #ddd;
            color: #555;
            font-size: 24px;
            font-weight: bold;
            margin: 0 auto;
        }

        img.profile-photo {
            object-fit: cover;
        }

        h3 {
            color: #0056b3;
            margin-bottom: 10px;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        ul li {
            margin-bottom: 8px;
        }

        ul li a {
            color: #0056b3;
            text-decoration: none;
        }

        ul li a:hover {
            text-decoration: underline;
        }

        p {
            margin: 10px 0;
        }

        a.button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }

        a.button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <h2>Welcome, <?php echo htmlspecialchars($applicant['firstname']); ?></h2>
    </header>

    <nav class="navbar">
        <ul>
            <li><a href="../index.php">Home</a></li>
            <li><a href="update_details.php">Update Details</a></li>
            <li><a href="check_status.php">Check Status</a></li>
            <li><a href="upload_documents.php">Upload Documents</a></li>
            <li><a href="provide_account_info.php">Provide Account Information</a></li>
            <li><a href="upload_profile_picture.php">Upload Profile Picture</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="view_messages.php">Messages</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <!-- Profile Section -->
        <div class="profile-section">
    <?php if (!empty($applicant['profile_picture'])): ?>
        <img src="../uploads/<?php echo htmlspecialchars($applicant['profile_picture']); ?>" alt="Profile Photo" class="profile-photo">
    <?php else: ?>
        <div class="profile-photo">
            <?php echo strtoupper(substr($applicant['firstname'], 0, 1) . substr($applicant['lastname'], 0, 1)); ?>
        </div>
    <?php endif; ?>
    <p><a href="upload_profile_picture.php">Upload/Change Profile Picture</a></p>
</div>


        <p>Status: <?php echo htmlspecialchars($applicant['status'] ?? 'Pending'); ?></p>

        <!-- User Information -->
        <h3>Your Information</h3>
        <ul>
            <li>Name: <?php echo htmlspecialchars($applicant['firstname'] . ' ' . $applicant['lastname']); ?></li>
            <li>Email: <?php echo htmlspecialchars($applicant['email']); ?></li>
            <li>School: <?php echo htmlspecialchars($applicant['school_name']); ?></li>
            <li>Department: <?php echo htmlspecialchars($applicant['department']); ?></li>
            <li>Course: <?php echo htmlspecialchars($applicant['course_of_study']); ?></li>
            <li>Level: <?php echo htmlspecialchars($applicant['level']); ?></li>
            <li>Ward: <?php echo htmlspecialchars($applicant['ward']); ?></li>
            <li>Student Type: <?php echo htmlspecialchars($applicant['student_type']); ?></li>
            <li>Residential Address: <?php echo htmlspecialchars($applicant['residential_address']); ?></li>
        </ul>

        <!-- Uploaded Documents -->
        <h3>Uploaded Documents</h3>
        <ul>
        <?php if (!empty($applicant['indigene_document'])): ?>
            <li><a href="../uploads/<?php echo htmlspecialchars($applicant['indigene_document']); ?>" target="_blank">Indigene Document</a></li>
        <?php endif; ?>
        <?php if (!empty($applicant['admission_document'])): ?>
            <li><a href="../uploads/<?php echo htmlspecialchars($applicant['admission_document']); ?>" target="_blank">Admission Document</a></li>
        <?php endif; ?>
        <?php if (!empty($applicant['id_card_document'])): ?>
            <li><a href="../uploads/<?php echo htmlspecialchars($applicant['id_card_document']); ?>" target="_blank">ID Card</a></li>
        <?php endif; ?>
        <?php if (!empty($applicant['payment_document'])): ?>
            <li><a href="../uploads/<?php echo htmlspecialchars($applicant['payment_document']); ?>" target="_blank">Payment Document</a></li>
        <?php endif; ?>
        <?php if (empty($applicant['indigene_document']) && empty($applicant['admission_document']) && empty($applicant['id_card_document']) && empty($applicant['payment_document'])): ?>
            <p>No documents uploaded yet.</p>
        <?php endif; ?>
        </ul>

        <!-- Bank Account Information -->
        <h3>Bank Account Information</h3>
        <?php if ($applicant): ?>
            <p><strong>Bank Name:</strong> <?php echo htmlspecialchars($applicant['bank_name']); ?></p>
            <p><strong>Account Number:</strong> <?php echo htmlspecialchars($applicant['account_number']); ?></p>
            <p><strong>Account Name:</strong> <?php echo htmlspecialchars($applicant['account_name']); ?></p>
        <?php else: ?>
            <p>You haven't provided your bank account details yet.</p>
        <?php endif; ?>

        <a href="provide_account_info.php" class="button">Update Bank Account Info</a>
    </div>
</body>
</html>
