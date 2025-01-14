<?php
// Function to load and parse the .env file
function loadEnv($filePath)
{
    if (!file_exists($filePath)) {
        throw new Exception("The .env file does not exist.");
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Parse key=value pairs
        [$key, $value] = explode('=', $line, 2);

        // Remove quotes and whitespace
        $key = trim($key);
        $value = trim($value, " \t\n\r\0\x0B\"'");

        // Set as environment variable
        putenv("$key=$value");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

// Load the .env file
loadEnv(__DIR__ . '/.env'); // Adjust the path if .env is not in the root directory
?>
