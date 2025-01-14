<?php
require '../includes/db.php'; // Include your database connection
session_start();

// Check if the user is logged in
if (!isset($_SESSION['applicant_logged_in']) || $_SESSION['applicant_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$applicant_id = $_SESSION['applicant_id']; // Get the logged-in user's ID

// Retrieve the profile picture from the database if the session doesn't have it
if (!isset($_SESSION['profile_picture'])) {
    $stmt = $conn->prepare("SELECT profile_picture FROM applicants WHERE id = ?");
    $stmt->bind_param("i", $applicant_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $applicant = $result->fetch_assoc();
    if ($applicant && !empty($applicant['profile_picture'])) {
        $_SESSION['profile_picture'] = $applicant['profile_picture']; // Set the session variable if available
    }
}

// Handle form submission for profile picture update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    $file = $_FILES['profile_picture'];
    
    // Check if the file is uploaded successfully
    if ($file['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = mime_content_type($file['tmp_name']);

        // Validate file type
        if (in_array($file_type, $allowed_types)) {
            $target_dir = "../uploads/"; // Directory where the image will be stored
            $file_name = time() . '_' . basename($file['name']); // Rename the file to avoid conflicts
            $target_file = $target_dir . $file_name;

            // Check if the file already exists
            if (!file_exists($target_file)) {
                // Move the uploaded file to the target directory
                if (move_uploaded_file($file['tmp_name'], $target_file)) {
                    
                    // Update the user's profile picture path in the database
                    $stmt = $conn->prepare("UPDATE applicants SET profile_picture = ? WHERE id = ?");
                    $stmt->bind_param("si", $file_name, $applicant_id);

                    if ($stmt->execute()) {
                        // Set the session variable for the profile picture
                        $_SESSION['profile_picture'] = $file_name; // Save the filename in session
                        echo "Profile picture updated successfully!";
                    } else {
                        echo "Failed to update profile picture in the database.";
                    }
                } else {
                    echo "Failed to upload the file. Please try again.";
                }
            } else {
                echo "File already exists. Please try again with a different file.";
            }
        } else {
            echo "Invalid file type. Please upload a valid image (JPEG, PNG, GIF).";
        }
    } else {
        echo "Error uploading file. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Profile Picture</title>
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
            text-align: center;
            opacity: 0.9;
        }

        .content-container img {
            border-radius: 50%;
            margin-bottom: 20px;
        }

        .content-container p {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
        }

        .content-container form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .content-container input[type="file"] {
            margin-bottom: 20px;
            padding: 12px;
            font-size: 16px;
            color: #333;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .content-container button {
            padding: 14px 24px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .content-container button:hover {
            background-color: #0056b3;
        }

        .links {
            margin-top: 20px;
            font-size: 16px;
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

        <h2>Upload Your Profile Picture</h2>
    
        <!-- Display a preview of the current profile picture -->
        <?php if (isset($_SESSION['profile_picture']) && !empty($_SESSION['profile_picture'])): ?>
            <img src="../uploads/<?php echo htmlspecialchars($_SESSION['profile_picture']); ?>" alt="Profile Picture" width="150" height="150">
        <?php else: ?>
            <p>No profile picture uploaded yet.</p>
        <?php endif; ?>
    
        <!-- Form to upload a new profile picture -->
        <form method="POST" enctype="multipart/form-data">
            <label for="profile_picture">Choose Profile Picture:</label>
            <input type="file" name="profile_picture" id="profile_picture" required>
            <button type="submit">Upload</button>
        </form>
        
        <div class="links">
            <a href="dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>

