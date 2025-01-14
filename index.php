<?php 
require_once 'env_loader.php'; // Load environment variables
?>
<?php include 'includes/header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scholarship Award System</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('images/background.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            text-align: center;
        }

        .container {
            margin-top: 20%;
        }

        h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        p {
            font-size: 1.5rem;
            margin-bottom: 2rem;
        }

        nav {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }

        nav a {
            display: inline-block;
            padding: 10px 20px;
            font-size: 1.2rem;
            color: white;
            text-decoration: none;
            background: rgba(0, 0, 0, 0.7);
            border: 2px solid white;
            border-radius: 5px;
            transition: background 0.3s, transform 0.2s;
        }

        nav a:hover {
            background: white;
            color: black;
            transform: scale(1.1);
        }
        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            text-align: center;
            padding: 10px 0;
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
    <marquee>
<font face="Broadway" color="white" size="3" style="background-color:green;padding:150px;">
For More Information contact this Number Or Our Email Address 07088564343 0r msaniabdulkareem@gmail.com
</font>
</marquee>

        <h1>Welcome to Sabon Gari Scholarship Award System</h1>
        <p>Only Sabon Gari candidates are eligible to apply.</p>
        <nav>
            <a href="applicant/register.php">Apply</a>
            <a href="admin/login.php">Admin Login</a>
            <a href="applicant/login.php">Applicant Login</a>
            <a href="applicant/check_status.php">Check Status</a>
            <a href="applicant/eligibility.php">Eligibility</a>
        </nav>
    </div>
</body>
</html>

<?php include 'includes/footer.php'; ?>
