<?php
require '../includes/db.php'; // Include your database connection
session_start();

// Debugging: Check session data
if (!isset($_SESSION['email'])) {
    echo "Email is not set in session. Please log in first.";
    exit();
}

$email = $_SESSION['email']; // Assume email is stored in session after login

// Fetch the user's current details
$stmt = $conn->prepare("SELECT * FROM applicants WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit;
}

// Define allowed wards
$allowed_wards = [
    "Muchia", "Zabi", "Samaru", "Basawa", 
    "Bomo", "Jama’a", "Chikaji", "Dogarawa", 
    "Hanwa", "Jushin Waje", "Unguwan Gabas"
];

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize updated form inputs
    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $othername = htmlspecialchars($_POST['othername']);
    $gender = $_POST['gender'];
    $school_name = htmlspecialchars($_POST['school_name']);
    $department = htmlspecialchars($_POST['department']);
    $course_of_study = htmlspecialchars($_POST['course_of_study']);
    $level = htmlspecialchars($_POST['level']);
    $ward = htmlspecialchars($_POST['ward']);
    $student_type = $_POST['student_type'];
    $residential_address = htmlspecialchars($_POST['residential_address']);

    // Validate the ward
    if (!in_array($ward, $allowed_wards)) {
        die("Invalid ward selected."); // Stop execution if the ward is invalid
    }

    // Update the user's details in the database
    $update_stmt = $conn->prepare("UPDATE applicants 
        SET firstname = ?, lastname = ?, othername = ?, gender = ?, school_name = ?, department = ?, course_of_study = ?, level = ?, ward = ?, student_type = ?, residential_address = ? 
        WHERE email = ?");
    $update_stmt->bind_param(
        "ssssssssssss",
        $firstname, $lastname, $othername, $gender, $school_name, $department, $course_of_study, $level, $ward, $student_type, $residential_address, $email
    );

    if ($update_stmt->execute()) {
        echo "Details updated successfully!";
        header("Location: dashboard.php"); // Redirect to dashboard
        exit();
    } else {
        echo "Failed to update details. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Details</title>
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
            margin-top: 20px;
            color: #333;
        }

        .form-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            opacity: 0.9;
        }

        .form-container input, .form-container select, .form-container textarea, .form-container button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .form-container input[type="radio"] {
            width: auto;
        }

        label {
            margin-right: 15px;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            padding: 12px;
            border-radius: 4px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .form-container textarea {
            height: 100px;
            resize: vertical;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Update Your Details</h2>
        <form method="POST">
            <input type="text" name="firstname" value="<?php echo $user['firstname']; ?>" required placeholder="First Name">
            <input type="text" name="lastname" value="<?php echo $user['lastname']; ?>" required placeholder="Last Name">
            <input type="text" name="othername" value="<?php echo $user['othername']; ?>" placeholder="Other Name">
            
            <div>
                <label>
                    <input type="radio" name="gender" value="Male" <?php echo $user['gender'] == 'Male' ? 'checked' : ''; ?>> Male
                </label>
                <label>
                    <input type="radio" name="gender" value="Female" <?php echo $user['gender'] == 'Female' ? 'checked' : ''; ?>> Female
                </label>
            </div><br>

            <input type="text" name="school_name" value="<?php echo $user['school_name']; ?>" required placeholder="School Name">
            <input type="text" name="department" value="<?php echo $user['department']; ?>" required placeholder="Department">
            <input type="text" name="course_of_study" value="<?php echo $user['course_of_study']; ?>" required placeholder="Course of Study">
            <input type="text" name="level" value="<?php echo $user['level']; ?>" required placeholder="Level">
            <select name="ward" required>
    <?php foreach ($allowed_wards as $allowed_ward): ?>
        <option value="<?php echo $allowed_ward; ?>" 
            <?php echo $user['ward'] === $allowed_ward ? 'selected' : ''; ?>>
            <?php echo $allowed_ward; ?>
        </option>
    <?php endforeach; ?>
</select>


            <select name="student_type" required>
                <option value="New" <?php echo $user['student_type'] == 'New' ? 'selected' : ''; ?>>New</option>
                <option value="Return" <?php echo $user['student_type'] == 'Return' ? 'selected' : ''; ?>>Return</option>
            </select>

            <textarea name="residential_address" required placeholder="Residential Address"><?php echo $user['residential_address']; ?></textarea>

            <button type="submit">Update</button>
        </form>
    </div>
</body>
</html>

