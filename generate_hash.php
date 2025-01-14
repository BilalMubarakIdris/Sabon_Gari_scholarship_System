<?php
require_once __DIR__ . '/env_loader.php'; // Load environment variables
// Other configuration or setup code

// Generate a hashed password for the admin
$password = 'Admin123'; // Replace this with your desired password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
echo "Hashed Password: " . $hashed_password;
?>



<!-- Add admin login details -->
<!-- INSERT INTO admins (username, password) 
VALUES ('admin', '$2y$10$Qhd0HL0pmQq.sQXZzbw0aOLG7JYsiwX2EWQrSqY7p3TjgFTg/zzVu'); -->
