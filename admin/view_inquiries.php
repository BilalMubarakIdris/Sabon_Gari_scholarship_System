<?php
require '../includes/db.php';

// Fetch all messages
$result = $conn->query("SELECT * FROM messages");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reply'])) {
    $reply = $_POST['reply'];
    $message_id = $_POST['message_id'];

    // Update the message with the admin's reply
    $stmt = $conn->prepare("UPDATE messages SET reply = ? WHERE id = ?");
    $stmt->bind_param("si", $reply, $message_id);

    if ($stmt->execute()) {
        echo "Reply sent successfully!";
    } else {
        echo "Failed to send reply. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View Messages</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        td {
            background-color: #f9f9f9;
        }

        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            resize: vertical;
        }

        textarea:focus {
            border-color: #007bff;
            outline: none;
        }

        button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #218838;
        }

        .links {
            margin-top: 20px;
            text-align: center;
        }

        .links a {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 0 10px;
            transition: background-color 0.3s ease;
        }

        .links a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Messages</h2>
        <table>
            <tr>
                <th>Email</th>
                <th>Message</th>
                <th>Reply</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
                <td>
                    <?php if ($row['reply']) { ?>
                        <strong>Reply:</strong> <?php echo nl2br(htmlspecialchars($row['reply'])); ?>
                    <?php } else { ?>
                        <form method="POST">
                            <textarea name="reply" placeholder="Your reply" required></textarea>
                            <input type="hidden" name="message_id" value="<?php echo $row['id']; ?>">
                            <button type="submit">Send Reply</button>
                        </form>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </table>
        <div class="links">
            <a href="../index.php">Home</a>
            <a href="dashboard.php">Dashboard</a>
        </div>
    </div>
</body>
</html>

